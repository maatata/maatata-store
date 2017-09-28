<?php

namespace App\Yantrana\Support;

use Mail;
use App\Yantrana\Components\User\Repositories\UserRepository;

/**
 * This MailService class for manage globally -
 * mail service in application.
 *---------------------------------------------------------------- */
class MailService
{
    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository - User Repository
     *-----------------------------------------------------------------------*/
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * This method use for send mail to given recipients.
     *
     * @param array $mailData
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function send($mailData = [])
    {
        extract($mailData);

        // Generating email view as html file instead of sending __email.html
        if (env('MAIL_VIEW_DEBUG', false) == true) {
            $emailsTemplate = isset($messageData['emailsTemplate']) ? $messageData['emailsTemplate'] : $view;

            $emailViewToGenerate = fopen(public_path('__email.html'), 'w') or die('Unable to open file!');
            $prependString = '<style>body{ margin:0;}</style><div style="padding:4px; text-align:center;background: #F3F3D9;
    margin: 0;margin-bottom: 20px;">Generated for <strong>'.$emailsTemplate.'</strong> on '.formatDateTime(date('c')).'</div><br>';
            $mailTemplateData = view($view, $messageData);

            fwrite($emailViewToGenerate, $prependString.$mailTemplateData->render());
            fclose($emailViewToGenerate);

            $_SESSION['__emailDebugView'] = url('__email.html');

            return true;
        }

        $mailRecipients = [
            'recipients' => (!empty($recipients)) ? $recipients : '',
            'cc' => (!empty($cc)) ? $cc : '',
            'bcc' => (!empty($bcc)) ? $bcc : '',
        ];

        //get recipients
        $recipients = $this->getMailRecipents($mailRecipients);

        $mailFrom = isset($from) ? $from : config('__tech.mail_from');

        $emailSent = Mail::send($view, $messageData,
            function ($message) use ($recipients, $subject, $mailFrom) {

                // Check for if direct recipients exist
                if (!empty($recipients['to'])) {
                    if (is_array($recipients['to']) and  isset($recipients['to'][1])) {
                        $message->to($recipients['to'][0], $recipients['to'][1]);
                    } else {
                        $message->to($recipients['to']);
                    }
                }

                // Check for if carbon copy recipients exist
                if (!empty($recipients['cc'])) {
                    $message->cc($recipients['cc']);
                }

                // Check for if blind carbon copy recipients exist
                if (!empty($recipients['bcc'])) {
                    $message->bcc($recipients['bcc']);
                }

                // Check for if sender is array collection
                if (is_array($mailFrom)) {
                    $message->from($mailFrom[0], $mailFrom[1]);
                } else {
                    $message->from($mailFrom);
                }

                $message->subject($subject);

        });

        if (empty($emailSent->failedRecipients)) {
            return true;
        }

        return false;
    }

    /**
     * This method use for get recipients.
     *
     * @param array $recipients
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getMailRecipents($getRecipients = [])
    {
        $mailRecipents = [];

        $mailRecipents['to'] = $mailRecipents['cc'] =
        $mailRecipents['bcc'] = [];

        if (!is_array($getRecipients['recipients'])) {

            // get commas separated recipients using getRecipentsArray
            $mailRecipents['to'] = $this->getRecipents($getRecipients['recipients']);
        } else {
            // check direct recipients
            if (isset($getRecipients['recipients'])) {
                $mailRecipents['to'] = $this->getRecipents(
                                            $getRecipients['recipients']
                                        );
            }
        }

        // check carbon copy recipients
        if (isset($getRecipients['cc'])) {
            $mailRecipents['cc'] = $this->getRecipents(
                                        $getRecipients['cc']
                                    );
        }

        // check blind carbon copy recipients
        if (isset($getRecipients['bcc'])) {
            $mailRecipents['bcc'] = $this->getRecipents(
                                            $getRecipients['bcc']
                                        );
        }

        return $mailRecipents;
    }

    /**
     * This method use for explode commas separated values in array.
     * 
     * @param sting $recipentString.
     *
     * @return array.
     */
    protected function getRecipents($recipentString = null)
    {
        $recipentsArray = [];
        if (!empty($recipentString)) {
            $recipentsArray = explode(',', $recipentString);
        }

        return $recipentsArray;
    }

    /**
     * Notify Customer.
     * 
     * @param sting $subject.
     * @param sting $emailView.
     * @param array $messageData.
     * @param mixed $customerEmailOrId.
     *
     * @return array.
     */
    public function notifyCustomer($subject, $emailView, $messageData = [], $customerEmailOrId = null)
    {
        $customerName = isset($messageData['name']) ? $messageData['name'] : null;

        if (isLoggedIn()) {
            $userAuthInfo = getUserAuthInfo();
            $customerEmail = $userAuthInfo['profile']['email'];
            $customerName = $userAuthInfo['profile']['full_name'];
        }
        // if customer email or id sent
        if ($customerEmailOrId) {
            // set it as customer email address
            $customerEmail = $customerEmailOrId;

            // if its a user id then find user & get email address of it
            if (is_numeric($customerEmailOrId)) {
                $userInfo = $this->userRepository->fetchByID($customerEmailOrId);

                $customerEmail = $userInfo->email;
                $customerName = $userInfo->fname.' '.$userInfo->lname;
            }
        }

        if (!$customerEmail) {
            throw new Exception('Customer Email is required');
        }

        if (!$customerName) {
            $customerName = $customerEmail;
        }

        $messageData['emailsTemplate'] = 'emails.'.$emailView;
        $messageData['mailForAdmin'] = false;
        $messageData['mailForCustomer'] = true;

        $subjectLine = '[ '.getStoreSettings('store_name').' ] ';

        if (!empty($messageData) and isset($messageData['orderData'])) {
            $subjectLine .= '[ Order# '.$messageData['orderData']['orderUID'].' ] ';
        }

        return $this->send([
                'recipients' => $customerEmail,
                'replyTo' => getStoreSettings('business_email'),
                'subject' => $subjectLine.$subject,
                'view' => 'emails.index',
                'from' => config('__tech.mail_from'),
                'messageData' => $messageData,
            ]);
    }

    /**
     * Notify Administrator.
     * 
     * @param sting $subject.
     * @param sting $emailView.
     * @param array $messageData.
     *
     * @return array.
     */
    public function notifyAdmin($subject, $emailView, $messageData = [], $messageType = 1)
    {
        $messageData['emailsTemplate'] = 'emails.'.$emailView;
        $messageData['name'] = getStoreSettings('store_name').' Administrator';
        $messageData['mailForAdmin'] = true;
        $messageData['mailForCustomer'] = false;

        $adminEmails = [
            1 => getStoreSettings('business_email'),
            2 => getStoreSettings('contact_email'),
        ];

        $subjectLine = '[ '.getStoreSettings('store_name').' ] ';

        if (!empty($messageData) and isset($messageData['orderData'])) {
            $subjectLine .= '[ Order# '.$messageData['orderData']['orderUID'].' ] ';
        }

        return $this->send([
                'recipients' => $adminEmails[$messageType],
                'subject' => $subjectLine.$subject,
                'view' => 'emails.index',
                'from' => config('__tech.mail_from'),
                'messageData' => $messageData,
            ]);
    }


    /**
     * Notify Customer.
     * 
     * @param sting $subject.
     * @param sting $emailView.
     * @param array $messageData.
     * @param mixed $customerEmailOrId.
     *
     * @return array.
     */
    public function notifyToUser($subject, $emailView, $messageData = [], $customerEmailOrId = null)
    {
        $customerName = isset($messageData['name']) ? $messageData['name'] : null;

        if (isLoggedIn()) {
            $userAuthInfo = getUserAuthInfo();
            $customerEmail = $userAuthInfo['profile']['email'];
            $customerName = $userAuthInfo['profile']['full_name'];
        }
        // if customer email or id sent
        if ($customerEmailOrId) {
            // set it as customer email address
            $customerEmail = $customerEmailOrId;

            // if its a user id then find user & get email address of it
            if (is_numeric($customerEmailOrId)) {
                $userInfo = $this->userRepository->fetchByID($customerEmailOrId);

                $customerEmail = $userInfo->email;
                $customerName = $userInfo->fname.' '.$userInfo->lname;
            }
        }

        if (!$customerEmail) {
            throw new Exception('Customer Email is required');
        }

        if (!$customerName) {
            $customerName = $customerEmail;
        }

        $messageData['emailsTemplate'] = 'emails.'.$emailView;
        $messageData['mailForAdmin'] = false;
        $messageData['mailForCustomer'] = true;

        $subjectLine = '[ '.getStoreSettings('store_name').' ] ';

        /*if (!empty($messageData) and isset($messageData['orderData'])) {
            $subjectLine .= '[ Order# '.$messageData['orderData']['orderUID'].' ] ';
        }*/

        return $this->send([
                'recipients'  => $customerEmail,
                'replyTo' 	  => getStoreSettings('business_email'),
                'subject' 	  => $subjectLine.$subject,
                'view' 		  => 'emails.index',
                'from' 		  => config('__tech.mail_from'),
                'messageData' => $messageData,
            ]);
    }
}
