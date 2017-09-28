<?php
/*
* PaypalEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Blueprints\PaypalEngineBlueprint;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderPaymentsRepository;
use Request;

class PaypalEngine implements PaypalEngineBlueprint
{
    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * @var OrderEngine
     */
    protected $orderEngine;

    /**
     * @var Holds Order Eloquent Object
     */
    protected $orderObj;

    /**
     * @var orderDetails will store once IPN Request get verified as array
     */
    protected $orderDetails;

    /**
     * @var Holds IPN Information
     */
    protected $ipnInformation;

    /**
     * @var Holds Infor Errors
     */
    protected $infoErrors;

    /**
     * @var OrderPaymentsRepository - OrderPayments Repository
     */
    protected $orderPaymentsRepository;

    /**
     * @var OrderPaymentsEngine - OrderPayments Engine
     */
    protected $orderPaymentsEngine;

    /**
     * Constructor.
     *
     * @param OrderRepository         $orderRepository         - Order Repository
     * @param OrderEngine             $orderEngine             - PayPal Repository
     * @param OrderPaymentsRepository $orderPaymentsRepository - Order Payment Repository
     * @param OrderPaymentsEngine     $orderPaymentsEngine     - Order Payment Engine
     *-----------------------------------------------------------------------*/
    public function __construct(OrderRepository $orderRepository,
                            OrderEngine  $orderEngine,
                            OrderPaymentsRepository $orderPaymentsRepository,
                            OrderPaymentsEngine $orderPaymentsEngine)
    {
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        define('IPN_LOG_FILE', './storage/logs/paypal-ipn.log');

        $this->orderRepository = $orderRepository;
        $this->orderEngine = $orderEngine;
        $this->orderPaymentsEngine = $orderPaymentsEngine;
        $this->orderPaymentsRepository = $orderPaymentsRepository;
    }

    /**
     * process IPN Notification Request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function processIpnRequest()
    {
        // Defined IPN & other responses codes as below
        // 1 - all OK you can update order
        // ERR_IPN_NOT_COMPLETED - The payment_status is not Completed
        // ERR_IPN_TXN_EXIST - txn_id has already been previously processed
        // ERR_IPN_EMAIL_MISMATCH - receiver_email is not valid Business PayPal email
        // ERR_IPN_AMOUNT_MISMATCH - payment_amount is not correct
        // ERR_IPN_CURRENCY_MISMATCH - payment_currency is not correct
        // ERR_IPN_INVALID - Not Verified / INVALID
        // ERR_IPN_NOTHING - Nothing
        // ERR_IPN_ORDER_NOT_FOUND - order not found
        // ERR_IPN_FAILD / null - Connection failed - Can't connect to PayPal to validate IPN message

        $validatedIpnRequest = $this->validateIpnRequest(); //validateIpnRequest / validateIpnInformation

        // Updated Payment Record & Notify concerned persons.
        if ($validatedIpnRequest === true) {
            $this->orderPaymentsRepository->storePayPalPayment($this->orderDetails['_id'], $this->ipnInformation);
            // // mark order payment as completed
            $this->orderRepository->updateOrderPaymentStatus($this->orderObj, 2);

            return $this->orderPaymentsEngine->notifyPaymentConfirmation($this->orderDetails, $this->ipnInformation);
        }

        if (is_array($validatedIpnRequest) and in_array('ERR_IPN_NOT_COMPLETED', $validatedIpnRequest)) {

            // mark order payment as pending
            $this->orderRepository->updateOrderPaymentStatus($this->orderObj, 4);
        }

        // Notify Admin about Payment Failure & Notify concerned persons.
        return $this->orderPaymentsEngine->notifyPaymentFailure($validatedIpnRequest, $this->ipnInformation);
    }

    /**
     * Process IPN Validations.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    protected function validateIpnRequest()
    {
        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
        $rawPostData = file_get_contents('php://input');
        $rawPostArray = explode('&', $rawPostData);
        $myPost = array();
        foreach ($rawPostArray as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $getMagicQuotesExists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($getMagicQuotesExists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // Post IPN data back to PayPal to validate the IPN data is genuine
        // Without this step anyone can fake IPN data
        if (env('USE_PAYPAL_SANDBOX', false) == true) {
            $paypalUrl = config('__tech.paypal_urls.sandbox');
        } else {
            $paypalUrl = config('__tech.paypal_urls.production');
        }
        $ch = curl_init($paypalUrl);
        if ($ch == false) {
            return false;
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        if (env('APP_DEBUG') == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }
        // CONFIG: Optional proxy configuration
        //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        // Set TCP timeout to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // of the certificate as shown below. Ensure the file is readable by the webserver.
        // This is mandatory for some environments.
        //$cert = __DIR__ . "./cacert.pem";
        //curl_setopt($ch, CURLOPT_CAINFO, $cert);
        $res = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            // cURL error

            if (env('APP_DEBUG') == true) {
                error_log(date('[Y-m-d H:i e] ')."Can't connect to PayPal to validate IPN message: ".curl_error($ch).PHP_EOL, 3, IPN_LOG_FILE);
            }
            curl_close($ch);
            // connection failed
            if (env('APP_DEBUG') == true) {
                return 'ERR_IPN_FAILD';
            }
            exit;
        } else {
            // Log the entire HTTP response if debug is switched on.
                if (env('APP_DEBUG') == true) {
                    error_log(date('[Y-m-d H:i e] ').'HTTP request of validation request:'.curl_getinfo($ch, CURLINFO_HEADER_OUT)." for IPN payload: $req".PHP_EOL, 3, IPN_LOG_FILE);
                    error_log(date('[Y-m-d H:i e] ')."HTTP response of validation request: $res".PHP_EOL, 3, IPN_LOG_FILE);
                }
            curl_close($ch);
        }
        // Inspect IPN validation result and act accordingly
        // Split response headers and payload, a better way for strcmp
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));
        if (strcmp($res, 'VERIFIED') == 0) {
            // check whether the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment and mark item as paid.
            // assign posted variables to local variables
            //$item_name = $_POST['item_name'];
            //$item_number = $_POST['item_number'];
            //$payment_status = $_POST['payment_status'];
            //$payment_amount = $_POST['mc_gross'];
            //$payment_currency = $_POST['mc_currency'];
            //$txn_id = $_POST['txn_id'];
            //$receiver_email = $_POST['receiver_email'];
            //$payer_email = $_POST['payer_email'];

            return $this->validateIpnInformation();

            if (env('APP_DEBUG') == true) {
                error_log(date('[Y-m-d H:i e] ')."Verified IPN: $req ".PHP_EOL, 3, IPN_LOG_FILE);
            }
        } elseif (strcmp($res, 'INVALID') == 0) {
            // log for manual investigation
            // Add business logic here which deals with invalid IPN messages

            // return response for INVALID
            return 'ERR_IPN_INVALID';

            if (env('APP_DEBUG') == true) {
                error_log(date('[Y-m-d H:i e] ')."Invalid IPN: $req".PHP_EOL, 3, IPN_LOG_FILE);
            }
        }

        return 'ERR_IPN_NOTHING';
    }

    /**
     * Validate IPN Request Information.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    protected function validateIpnInformation()
    {

        // get IPN request
        $this->ipnInformation = Request::all();

        // store order information
        $this->orderObj = $this->orderEngine->getByIdOrUid($this->ipnInformation['invoice']);

        // order not found
        if (__isEmpty($this->orderObj)) {
            return 'ERR_IPN_ORDER_NOT_FOUND';
        }

        $this->orderDetails = $this->orderObj->toArray();

        // check whether the payment_status is Completed
        if ($this->ipnInformation['payment_status'] != 'Completed') {
            $this->infoErrors[] = 'ERR_IPN_NOT_COMPLETED';
        }
        // check that txn_id has not been previously processed
        if ($this->orderPaymentsEngine->isTxnExists($this->ipnInformation['txn_id'], 1) == true) {
            $this->infoErrors[] = 'ERR_IPN_TXN_EXIST';
        }
        // check that receiver_email match with order business email
        if (($this->validateOrderItem('business_email', $this->ipnInformation['receiver_email']) == false)) {
            // set other emails
            $otherValidBusinessEmails = [
                getStoreSettings('paypal_email'),
                getStoreSettings('business_email'),
            ];
            // check that receiver_email match with site business email
            if (!in_array($this->ipnInformation['receiver_email'], $otherValidBusinessEmails)) {
                $this->infoErrors[] = 'ERR_IPN_EMAIL_MISMATCH';
            }
        }
        // check that payment_amount - mc_gross are correct
        if ($this->validateOrderItem('total_amount', $this->ipnInformation['mc_gross']) == false) {
            $this->infoErrors[] = 'ERR_IPN_AMOUNT_MISMATCH';
        }
        // check that payment_currency - mc_currency  are correct
        if ($this->validateOrderItem('currency_code', $this->ipnInformation['mc_currency']) == false) {
            $this->infoErrors[] = 'ERR_IPN_CURRENCY_MISMATCH';
        }

        if (!empty($this->infoErrors)) {
            return $this->infoErrors;
        }

        // it seems to be all ok!!
        return true;
    }

    /**
     * Check that item is correct using order.
     *
     * @param string $item - Item Name
     * @param mixed  $item - IPN Order Info Item
     * 
     * @return mixed
     *-----------------------------------------------------------------------*/
    protected function validateOrderItem($itemName, $ipnInfoItem)
    {
        // check & return if requested item exists in order
        if (array_key_exists($itemName, $this->orderDetails)) {
            // check if it is same as in the order
            return $this->orderDetails[$itemName] == $ipnInfoItem;
        }

        // not a valid order item
        return false;
    }
}
