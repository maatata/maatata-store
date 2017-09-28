<?php

    /**
     * Custom validation rules for check unique email address -
     * for user.
     *
     * @return bool
     *---------------------------------------------------------------- */
    Validator::extend('unique_email', function ($attribute, $value, $parameters) {
        $email = strtolower($value);
        $userCount = App\Yantrana\Components\User\Models\User::where('email', $email)
                        ->get()
                        ->count();

        // Check for user exist with given email
        if ($userCount > 0) {
            return false;
        }

        $newEmailRequestCount = App\Yantrana\Components\User\Models\TempEmail::where('new_email', $email)
                                    ->count();
        // Check for new email request exist with given email
        if ($newEmailRequestCount > 0) {
            return false;
        }

        return true;

    });
