<?php
/*
* UserRepository.php - Repository file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Repositories;

use Auth;
use Request;
use DB;
use Carbon\Carbon;
use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\User\Models\User as UserModel;
use App\Yantrana\Components\User\Models\LoginAttempt;
use App\Yantrana\Components\User\Models\PasswordReset;
use App\Yantrana\Components\User\Models\TempEmail;
use App\Yantrana\Components\User\Blueprints\UserRepositoryBlueprint;

class UserRepository extends BaseRepository implements UserRepositoryBlueprint
{
    /**
     * @var UserModel - User Model
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param UserModel $user - User Model
     *-----------------------------------------------------------------------*/
    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    /**
     * Fetch users for manage section.
     *
     * @param number status
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUsers($status)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                'name' => 'fname',
                'creation_date' => 'created_at',
            ],
            'searchable' => [
                'fname',
                'lname',
                'email',
            ],
        ];

        return $this->user
                    ->where('status', $status)
                    ->select(
                        'id',
                        'role',
                        'status',
                        DB::raw('CONCAT(users.fname, " ", users.lname) AS name'),
                        'email',
                        'created_at',
                        'last_login'
                    )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Update login attempts.
     *---------------------------------------------------------------- */
    public function updateLoginAttempts()
    {
        $ipAddress = Request::getClientIp();
        $loginAttempt = LoginAttempt::where('ip_address', $ipAddress)
                                        ->first();

        // Check if login attempt record exist for this ip address
        if (!empty($loginAttempt)) {
            $loginAttempt->attempts = $loginAttempt->attempts + 1;
            $loginAttempt->save();
        } else {
            $newLoginAttempt = new LoginAttempt();

            $newLoginAttempt->ip_address = $ipAddress;
            $newLoginAttempt->attempts = 1;
            $newLoginAttempt->created_at = getCurrentDateTime();
            $newLoginAttempt->save();
        }
    }

    /**
     * Clear login attempts.
     *---------------------------------------------------------------- */
    private function clearLoginAttempts()
    {
        LoginAttempt::where('ip_address', Request::getClientIp())->delete();
    }

    /**
     * Fetch login attempts based on ip address.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchLoginAttemptsCount()
    {
        $loginAttempt = LoginAttempt::where('ip_address',
                                        Request::getClientIp()
                                    )
                                    ->select('attempts')
                                    ->first();

        if (!empty($loginAttempt)) {
            return $loginAttempt->attempts;
        }

        return 0;
    }

    /**
     * Handle user login attempts based on passed user credentials,
     * if credentials match permit the authentication creating login -
     * log & return boolean response.
     *
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function login($input)
    {
        // set credentials for login attempt.
        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
            'status' => 1,            // active
        ];

        // Get logged in if credentials valid
        if (Auth::attempt($credentials,
            isset($input['remember_me']) ? $input['remember_me'] : false)) {
            $this->clearLoginAttempts(); // make login log entry

            $user = Auth::user();
            $user->last_ip = Request::getClientIp();
            $user->last_login = getCurrentDateTime();
            $user->save();

            return true;
        }

        // If authentication failed 
        $this->updateLoginAttempts();   // update login attempts

        return false;
    }

    /**
     * Fetch active user using email address & return response.
     *
     * @param string $email
     * @param bool   $selectRecord
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchActiveUserByEmail($email, $selectRecord = false)
    {
        $activeUser = $this->user->where([
                                    'status' => 1,      // active status
                                    'banned' => 0,      // not banned
                                    'email' => $email,
                                  ]);

        if ($selectRecord) {
            $activeUser->select(
                            'id',
                            'fname',
                            'lname'
                        );
        }

        return $activeUser->first();
    }

    /**
     * Store password reminder & return response.
     *
     * @param string $email
     * @param string $token
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storePasswordReminder($email, $token)
    {
        $passwordReminder = new PasswordReset();

        $passwordReminder->email = $email;
        $passwordReminder->token = $token;
        $passwordReminder->created_at = getCurrentDateTime();

        return $passwordReminder->save();
    }

    /**
     * Delete old password reminder.
     *
     * @param string $email
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteOldPasswordReminder($email)
    {
        $expiryTime = time() - config('__tech.account.password_reminder_expiry')
                                * 60 * 60;

        return PasswordReset::where('email', $email)
                            ->orWhere(DB::raw('UNIX_TIMESTAMP(created_at)'),
                             '<', $expiryTime
                            )
                            ->delete();
    }

    /**
     * Fetch password reminder count.
     *
     * @param string $reminderToken
     * @param string $email
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchPasswordReminderCount($reminderToken, $email = null)
    {
        return PasswordReset::where(function ($query) use ($reminderToken, $email) {

                                $query->where('token', $reminderToken);

                                if (!__isEmpty($email)) {
                                    $query->where('email', $email);
                                }

                            })
                            ->get()
                            ->count();
    }

    /**
     * Reset password.
     *
     * @param object $user
     * @param string $newPassword
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function resetPassword($user, $newPassword)
    {
        $user->password = bcrypt($newPassword);

        if ($user->save()) {  // Check for if user password reset

            $this->deleteOldPasswordReminder($user->email);

            return true;
        }

        return false;
    }

    /**
     * Update password.
     *
     * @param object $user
     * @param string $newPassword
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function updatePassword($user, $newPassword)
    {
        $user->password = bcrypt($newPassword);

        if ($user->save()) {
            activityLog('ID of '.$user->id.' user update password.');

            return true;
        }

        return false;
    }

    /**
     * Store user new email reminder.
     *
     * @param string $newEmail
     * @param string $activationKey
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function storeNewEmailReminder($newEmail, $activationKey)
    {
        $tempEmail = new TempEmail();

        $tempEmail->activation_key = $activationKey;
        $tempEmail->new_email = $newEmail;
        $tempEmail->users_id = Auth::id();

        if ($tempEmail->save()) {
            activityLog('ID of '.$tempEmail->id.' tempEmail added.');

            return $tempEmail;
        }

        return false;
    }

    /**
     * Delete old email change request.
     * 
     * @param string $newEmail
     * 
     * @return bool
     */
    public function deleteOldEmailChangeRequest($newEmail = null)
    {
        $userID = Auth::id();
        $expiryTime = time() - config('__tech.account.change_email_expiry')
                                * 60 * 60;

        return TempEmail::where([
                                'new_email' => $newEmail,
                                'users_id' => $userID,
                            ])
                            ->orWhere(DB::raw('UNIX_TIMESTAMP(created_at)'), '<', $expiryTime
                            )
                            ->orWhere(['users_id' => $userID])
                            ->delete();
    }

    /**
     * Fetch temparary email.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTempEmail($userID, $activationKey)
    {
        return TempEmail::where([
                            'activation_key' => $activationKey,
                            'users_id' => $userID,
                        ])
                        ->select('new_email')
                        ->first();
    }

    /**
     * Update user email.
     *
     * @param string $newEmail
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateEmail($newEmail)
    {
        $user = Auth::user();

        $user->email = $newEmail;

        // Check if user email updated
        if ($user->save()) {
            $this->deleteOldEmailChangeRequest($newEmail);

            activityLog('ID of '.$user->id.' user email update.');

            return true;
        }

        return false;
    }

    /**
     * Store new user.
     *
     * @param array $input
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeNewUser($input)
    {
        $newUser = new $this->user();

        $newUser->password = bcrypt($input['password']);
        $newUser->role = 2;                 // user role
        $newUser->status = 4;                 // never activated user
        $newUser->fname = $input['first_name'];
        $newUser->lname = $input['last_name'];
        $newUser->email = $input['email'];
        $newUser->banned = 0;                 // not banned
        $newUser->remember_token = __generateUID();

        // Check if user stored
        if ($newUser->save()) {
            return $newUser;
        }

        return [];
    }

    /**
     * Fetch never activated user.
     *
     * @param number $userID
     * @param string $activationKey
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchNeverActivatedUser($userID, $activationKey)
    {
        return $this->user->where([
                            'remember_token' => $activationKey,
                            'id' => $userID,
                            'status' => 4,  // never activated 
                        ])
                        ->first();
    }

    /**
     * Activate user by updating its status information.
     *
     * @param object $user
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function activateUser($user)
    {
        $user->status = 1;  // activate status 

        // Check if information updated
        if ($user->save()) {
            return true;
        }

        return false;
    }

    /**
     * Fetch user profile.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProfile()
    {
        return $this->user
                    ->where('id', Auth::id())
                    ->select(
                        'fname as first_name',
                        'lname as last_name'
                    )
                    ->first();
    }

    /**
     * Update profile.
     *
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateProfile($input)
    {
        $user = Auth::user();

        // Check if profile updated
        if ($user->modelUpdate([
                'fname' => $input['first_name'],
                'lname' => $input['last_name'],
            ])) {
            activityLog('ID of '.$user->id.' user profile updated.');

            return true;
        }

        return false;
    }

    /**
     * Fetch user by id.
     *
     * @param number $userID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($userID)
    {
        return $this->user->find($userID);
    }

    /**
     * Delete user by updating user status.
     *
     * @param object $user
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($user)
    {
        // Check if user status is never activated, then delete user permanently
        if ($user->status == 4) {
            if ($user->delete()) {
                activityLog('ID of '.$user->id.' user permanently deleted.');

                return true;
            }
        } elseif ($user->modelUpdate(['status' => 5])) { // if user is active then soft delete it

            activityLog('ID of '.$user->id.' user soft deleted.');

            return true;
        }

        return false;
    }

    /**
     * Restore user by updating user deleted status to active status.
     *
     * @param object $user
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function restore($user)
    {
        // Check if user restored
        if ($user->modelUpdate(['status' => 1])) {
            activityLog('ID of '.$user->id.' user restore.');

            return true;
        }

        return false;
    }

    /**
     * Get admin user.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function getAdmin()
    {
        return $this->user->where('status', 1)
                            ->where('role', 1)
                            ->first();
    }

    /**
     * Fetch application active users count not including with the role of admin.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchUsersCount()
    {
        return $this->user->where('status', 1)
                          ->where('role', 2)
                          ->count();
    }

    /**
     * Fetch application active users count not including with the role of admin.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchTodayRegisteredUsersCount()
    {
        return $this->user->where('role', 2)
                          ->where('status', 1)
                          ->where(DB::raw('DATE(created_at)'), Carbon::now()->startOfDay())
                          ->count();
    }

    /**
     * Delete non activated user if activation limit expired.
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function deleteNonActicatedUser()
    {
        return $this->user
                    ->where(
                        DB::raw('UNIX_TIMESTAMP(created_at)'),
                        '<',
                        time() - config('__tech.account_activation')
                    )
                    ->whereRole(4) // never activated
                    ->whereStatus(2) // customer
                    ->delete();
    }

    /**
     * Fetch non activated user record by email.
     *
     * @param string $email.
     *
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function getNonActicatedUserByEmail($email)
    {
        return $this->user
                   ->whereEmailAndRoleAndStatus($email, 2, 4)
                   ->first();
    }

    /**
     * Fetch all users email.
     *
     *
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function fetchEmailOfUsers()
    {
        return $this->user
                    ->where('status', 5)
                    ->get(['email', 'status']);
    }

    /**
     * Fetch User First and Last name.
     *
     * @param number $userID
     *
     * @return Eloquent Collection Object.
     *---------------------------------------------------------------- */
    public function fetchUserFullName($userID)
    {
        return $this->user
                    ->where('id', $userID)
                    ->first(['fname', 'lname']);
    }
}
