<?php
/*
* UserController.php - Controller file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Controllers;

use JavaScript;
use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\User\UserEngine;
use App\Yantrana\Components\User\Requests\UserLoginRequest;
use App\Yantrana\Components\User\Requests\UserForgotPasswordRequest;
use App\Yantrana\Components\User\Requests\UserResetPasswordRequest;
use App\Yantrana\Components\User\Requests\UserUpdatePasswordRequest;
use App\Yantrana\Components\User\Requests\UserChangeEmailRequest;
use App\Yantrana\Components\User\Requests\UserRegisterRequest;
use App\Yantrana\Components\User\Requests\UserProfileUpdateRequest;
use App\Yantrana\Components\User\Requests\UserContactRequest;
use App\Yantrana\Components\User\Requests\UserResendActivationEmailRequest;
use App\Yantrana\Components\User\Requests\UserChangePasswordRequest;

class UserController extends BaseController
{
    /**
     * @var UserEngine - User Engine
     */
    protected $userEngine;

    /**
     * Constructor.
     *
     * @param UserEngine $userEngine - User Engine
     *-----------------------------------------------------------------------*/
    public function __construct(UserEngine $userEngine)
    {
        $this->userEngine = $userEngine;
    }

    /**
     * Handle datatable source data request.
     *
     * @param number $status
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function index($status)
    {
        $userCollection = $this->userEngine->prepareUsersList($status);

        $requireColumns = [
            'creation_date' => function ($key) {

                return formatStoreDateTime($key['created_at']);

            },
            'last_login' => function ($key) {

                if (!__isEmpty($key['last_login'])) {
                    return formatStoreDateTime($key['last_login']);
                }

            },
            'id',
            'status',
            'role',
            'name',
            'email',
        ];

        return __dataTable($userCollection, $requireColumns);
    }

    /**
     * Get login attempts for this client ip.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function loginAttempts()
    {
        $processReaction = $this->userEngine->prepareLoginAttempts();

        return __processResponse($processReaction, [],
            $processReaction['data']);
    }

    /**
     * Show login view.
     *---------------------------------------------------------------- */
    public function login()
    {
        $breadCrumb = $this->userEngine->breadcrumbGenerate('login');

        return $this->loadPublicView('user.login', $breadCrumb['data']);
    }

    /**
     * Authenticate user based on post form data.
     *
     * @param object UserLoginRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function loginProcess(UserLoginRequest $request)
    {
        $processReaction = $this->userEngine->processLogin($request->all());

        return __processResponse($processReaction, [
                1 => __('Welcome, you are logged in successfully.'),
                2 => __('Authentication failed. Please check your 
                email/password & try again.'),
            ], [], true);
    }

    /**
     * Perform user logout action.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function logout()
    {
        $processReaction = $this->userEngine->processLogout();

        return redirect()->route('user.login');
    }

    /**
     * Handle forgot password view request.
     *---------------------------------------------------------------- */
    public function forgotPassword()
    {
        $breadCrumb = $this->userEngine
                           ->breadcrumbGenerate('forgot-password');

        return $this->loadPublicView('user.forgot-password', $breadCrumb['data']);
    }

    /**
     * Handle user forgot password request.
     *
     * @param object UserForgotPasswordRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function forgotPasswordProcess(UserForgotPasswordRequest $request)
    {
        $processReaction = $this->userEngine
                                ->sendPasswordReminder(
                                    $request->input('email')
                                );

        return __processResponse($processReaction, [
                1 => __('We have e-mailed your password reset link.'),
                2 => __('Invalid Request.'),
            ]);
    }

    /**
     * Handle forgot password success view request.
     *---------------------------------------------------------------- */
    public function forgotPasswordSuccess()
    {
        return $this->loadPublicView('user.forgot-password-success');
    }

    /**
     * Render reset password view.
     *
     * @param string $reminderToken
     *---------------------------------------------------------------- */
    public function restPassword($reminderToken)
    {
        $processReaction = $this->userEngine
                                ->varifyPasswordReminderToken($reminderToken);

        $breadCrumb = $this->userEngine
                           ->breadcrumbGenerate('reset-password');

        if ($processReaction['reaction_code'] === 1) {
            Javascript::put(['passwordReminderToken' => $reminderToken]);

            return $this->loadPublicView('user.reset-password', $breadCrumb['data']);
        }

        return error404();
    }

    /**
     * Handle reset password request.
     *
     * @param object UserResetPasswordRequest $request
     * @param string                          $reminderToken
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function restPasswordProcess(UserResetPasswordRequest $request,
        $reminderToken)
    {
        $processReaction = $this->userEngine
                                ->processResetPassword(
                                    $request->all(),
                                    $reminderToken
                                );

        return __processResponse($processReaction, [
                1 => __('Password reset successfully.'),
                2 => __('Password not updated.'),
                18 => __('Invalid Request.'),
            ]);
    }

    /**
     * Render update password view.
     *
     * @return view
     *---------------------------------------------------------------- */
    public function changePassword()
    {
        $breadCrumb = $this->userEngine->breadcrumbGenerate('change-password');

        return $this->loadPublicView('user.change-password', $breadCrumb['data']);
    }

    /**
     * Handle change password request.
     *
     * @param object UserUpdatePasswordRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function changePasswordProcess(UserUpdatePasswordRequest $request)
    {
        $processReaction = $this->userEngine
                                ->processUpdatePassword(
                                    $request->only(
                                        'new_password',
                                        'current_password'
                                    )
                                );

        return __processResponse($processReaction, [
                1 => __('Password updated successfully.'),
                3 => __('Current password is incorrect.'),
                14 => __('Password not updated.'),
            ], null, true);
    }

    /**
     * Render change email view.
     *---------------------------------------------------------------- */
    public function changeEmail()
    {
        $breadCrumb = $this->userEngine
                           ->breadcrumbGenerate('change-email');

        return $this->loadPublicView('user.change-email', $breadCrumb['data']);
    }

    /**
     * Handle change email request.
     *
     * @param object UserChangeEmailRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function changeEmailProcess(UserChangeEmailRequest $request)
    {
        $processReaction = $this->userEngine
                                ->sendNewEmailActivationReminder(
                                    $request->only(
                                        'new_email',
                                        'current_password'
                                    )
                                );

        return __processResponse($processReaction, [
                1 => __('New email activation link has been sent 
                        to your new email address, please check your email.'),
                2 => __('Email not send.'),
                3 => __('Authentication failed. Please check your 
                        password.'),
            ]);
    }

    /**
     * Handle new email activation request.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function newEmailActivation($userID, $activationKey)
    {
        $processReaction = $this->userEngine
                                ->newEmailActivation(
                                    $userID,
                                    $activationKey
                                );

        // Check if activation process succeed
        if ($processReaction['reaction_code'] === 1) {
            return redirect()->route('user.profile')->with([
                'success' => true,
                'message' => __('Your new email activated successfully.'),
             ]);
        }

        return  error404();
    }

    /**
     * Show registration view.
     *---------------------------------------------------------------- */
    public function register()
    {
        $breadCrumb = $this->userEngine
                           ->breadcrumbGenerate('register');

        return $this->loadPublicView('user.register', $breadCrumb['data']);
    }

    /**
     * Handle user register process request.
     *
     * @param object UserRegisterRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function registerProcess(UserRegisterRequest $request)
    {
        $processReaction = $this->userEngine->processRegister($request->all());

        return __processResponse($processReaction, [
                1 => __('User register successfully.'),
                2 => __('User register failed.'),
                3 => __('your account has been already exist, please contact system administrator.'),
            ]);
    }

    /**
     * Handle registration success view request.
     *---------------------------------------------------------------- */
    public function registerSuccess()
    {
        return $this->loadPublicView('user.register-success');
    }

    /**
     * Handle user account activation request.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function accountActivation($userID, $activationKey)
    {
        $processReaction = $this->userEngine
                                ->processAccountActivation(
                                    $userID,
                                    $activationKey
                                );

        // Check if account activation process succeed
        if ($processReaction['reaction_code'] === 1) {
            return redirect()->route('user.login')
                        ->with([
                            'success' => 'true',
                            'message' => __('Your account has been activated successfully. Login with your email ID and password.'),
                        ]);
        }

        // if activation process failed then
        return redirect()->route('user.login')
                        ->with([
                            'error' => 'true',
                            'message' => __('Account Activation link invalid.'),
                        ]);
    }

    /**
     * Handle user profile view request.
     *---------------------------------------------------------------- */
    public function profile()
    {
        $breadCrumb = $this->userEngine->breadcrumbGenerate('profile');

        return $this->loadPublicView('user.profile', $breadCrumb['data']);
    }

    /**
     * Handle profile details request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function profileDetails()
    {
        $processReaction = $this->userEngine->prepareProfileDetails();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle user profile update view request.
     *---------------------------------------------------------------- */
    public function updateProfile()
    {
        $breadCrumb = $this->userEngine->breadcrumbGenerate('profileEdit');

        return $this->loadPublicView('user.profile-edit', $breadCrumb['data']);
    }

    /**
     * Handle update profile request.
     *
     * @param object UserProfileUpdateRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function updateProfileProcess(UserProfileUpdateRequest $request)
    {
        $processReaction = $this->userEngine
                                ->processUpdateProfile(
                                    $request->only(
                                        'first_name',
                                        'last_name'
                                    )
                                );

        return __processResponse($processReaction, [
                1 => __('Profile updated successfully.'),
                14 => __('Nothing updated.'),
            ], [], true);
    }

    /**
     * Handle user delete request.
     *
     * @param number $userID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($userID, Request $request)
    {
        $processReaction = $this->userEngine->processUserDelete($userID);

        return __processResponse($processReaction, [
                1 => $processReaction['data']['message'],
                2 => __('User not deleted.'),
            ]);
    }

    /**
     * Handle user restore request.
     *
     * @param number $userID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function restore($userID, Request $request)
    {
        $processReaction = $this->userEngine->processUserRestore($userID);

        return __processResponse($processReaction, [
                1 => $processReaction['data']['message'],
                2 => __('User not restore.'),
            ]);
    }

    /**
     * Handle user contact view request.
     *---------------------------------------------------------------- */
    public function contact()
    {
        $breadCrumb = $this->userEngine->breadcrumbGenerate('contact');

        return $this->loadPublicView('user.contact', $breadCrumb['data']);
    }

    /**
     * Handle process contact request.
     *
     * @param object UserContactRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function contactProcess(UserContactRequest $request)
    {
        $processReaction = $this->userEngine->processContact($request->all());

        return __processResponse($processReaction, [
                1 => __('Mail has been sent successfully, we contact soon'),
                2 => __('Failed to send mail.'),
            ]);
    }

    /**
     * Show resend activation email view.
     *------------------------------------------------------------------------ */
    public function resendActivationEmail()
    {
        $breadCrumb = $this->userEngine
                           ->breadcrumbGenerate('resend-activation-email');

        return $this->loadPublicView('user.resend-activation-email', $breadCrumb['data']);
    }

    /**
     * Process resend activation email request.
     *
     * @param array UserResendActivationEmailRequest $request
     *------------------------------------------------------------------------ */
    public function resendActivationEmailProccess(UserResendActivationEmailRequest $request)
    {
        $processReaction = $this->userEngine
                                ->resendActivationEmail($request->all());

        return __processResponse($processReaction, [
                1 => __('Activation mail has been sent successfully, Please check your email..!!'),
                2 => __('Request failed.'),
                3 => __('Your account already activated.'),
                18 => __('User with entered email not register.'),
            ]);
    }

    /**
     * Handle resend activation email success view request.
     *---------------------------------------------------------------- */
    public function resendActivationEmailSuccess()
    {
        return $this->loadPublicView('user.resend-activation-email-success');
    }

    /**
     * view privacy policy.
     *---------------------------------------------------------------- */
    public function privacyPolicy()
    {
        $breadCrumb = $this->userEngine
                        ->breadcrumbGenerate('privacyPolicy');

        return $this->loadPublicView('privacy-policy', $breadCrumb['data']);
    }

    /**
     * view terms & conditions.
     *---------------------------------------------------------------- */
    public function termsAndConditions()
    {
        $breadCrumb = $this->userEngine
                        ->breadcrumbGenerate('termsAndCondition');

        return $this->loadPublicView('terms-and-conditions', $breadCrumb['data']);
    }

    /**
     * change user password by admin.
     *
     * @param number                          $userID
     * @param array UserChangePasswordRequest $request
     *---------------------------------------------------------------- */
    public function changePasswordByAdmin($userID, UserChangePasswordRequest $request)
    {
        $processReaction = $this->userEngine
                                ->processChangePassword($userID, $request->all());

        return __processResponse($processReaction, [
                1 => __('Password updated successfully.'),
                14 => __('Password not updated.'),
                18 => __('User not exist.'),
            ]);
    }

    /**
     * Get User details.
     *
     * @param number $userID
     *---------------------------------------------------------------- */
    public function getUserDetails($userID)
    {
        $processReaction = $this->userEngine
                                ->prepareUserDetails($userID);

        return __processResponse($processReaction, [
                18 => __('User not exist'),
            ], $processReaction['data']);
    }

    /**
     * Handle process contact request.
     *
     * @param object UserContactRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function userContactProcess(UserContactRequest $request)
    {// __dd($request->all());
        $processReaction = $this->userEngine->userContactProcess($request->all());

        return __processResponse($processReaction, [
                1 => __('Mail has been sent successfully'),
                2 => __('Failed to send mail.'),
            ]);
    }

    /**
     * Handle process contact request.
     *
     * @param object UserContactRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getInfo($userId)
    {
        $processReaction = $this->userEngine->prepareInfo($userId);

        return __processResponse($processReaction, [], true);
    }
}
