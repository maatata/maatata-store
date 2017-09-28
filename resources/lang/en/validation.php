<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => __('The __attribute__ must be accepted.',[
            '__attribute__' => ':attribute'
        ]),
    'active_url'           => __('The __attribute__ is not a valid URL.',[
            '__attribute__' => ':attribute'
        ]),
    'after'                => __('The __attribute__ must be a date after __date__.',[
            '__attribute__' => ':attribute',
            '__date__' => ':date'
        ]),
    'alpha'                => __('The __attribute__ may only contain letters.',[
            '__attribute__' => ':attribute'
        ]),
    'alpha_dash'           => __('The __attribute__ may only contain letters, numbers, and dashes.',[
            '__attribute__' => ':attribute'
        ]),
    'alpha_num'            => __('The __attribute__ may only contain letters and numbers.',[
            '__attribute__' => ':attribute'
        ]),
    'array'                => __('The __attribute__ must be an array.',[
            '__attribute__' => ':attribute'
        ]),
    'before'               => __('The __attribute__ must be a date before __date__.',[
            '__attribute__' => ':attribute',
            '__date__' => ':date'
        ]),
    'between'              => [
        'numeric' => __('The __attribute__ must be between __min__ and __max__.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min',
            '__max__' => ':max'
        ]),
        'file'    => __('The __attribute__ must be between __min__ and __max__ kilobytes.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min',
            '__max__' => ':max'
        ]),
        'string'  => __('The __attribute__ must be between __min__ and __max__ characters.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min',
            '__max__' => ':max'
        ]),
        'array'   => __('The __attribute__ must have between __min__ and __max__ items.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min',
            '__max__' => ':max'
        ]),
    ],
    'boolean'              => __('The __attribute__ field must be true or false.',[
            '__attribute__' => ':attribute'
        ]),
    'confirmed'            => __('The __attribute__ confirmation does not match.',[
            '__attribute__' => ':attribute'
        ]),
    'date'                 => __('The __attribute__ is not a valid date.',[
            '__attribute__' => ':attribute'
        ]),
    'date_format'          => __('The __attribute__ does not match the format __format__.',[
            '__attribute__' => ':attribute',
            '__format__' => ':format'
        ]),
    'different'            => __('The __attribute__ and __other__ must be different.',[
            '__attribute__' => ':attribute',
            '__other__' => ':other'
        ]),
    'digits'               => __('The __attribute__ must be __digits__ digits.',[
            '__attribute__' => ':attribute',
            '__digits__' => ':digits'
        ]),
    'digits_between'       => __('The __attribute__ must be between __min__ and __max__ digits.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min',
            '__max__' => ':max'
        ]),
    'distinct'             => __('The __attribute__ field has a duplicate value.',[
            '__attribute__' => ':attribute'
        ]),
    'email'                => __('The __attribute__ must be a valid email address.',[
            '__attribute__' => ':attribute'
        ]),
    'exists'               => __('The selected __attribute__ is invalid.',[
            '__attribute__' => ':attribute'
        ]),
    'filled'               => __('The __attribute__ field is required.',[
            '__attribute__' => ':attribute'
        ]),
    'image'                => __('The __attribute__ must be an image.',[
            '__attribute__' => ':attribute'
        ]),
    'in'                   => __('The selected __attribute__ is invalid.',[
            '__attribute__' => ':attribute'
        ]),
    'in_array'             => __('The __attribute__ field does not exist in __other__.',[
            '__attribute__' => ':attribute',
            '__other__'        => ':other'
        ]),
    'integer'              => __('The __attribute__ must be an integer.',[
            '__attribute__' => ':attribute'
        ]),
    'ip'                   => __('The __attribute__ must be a valid IP address.',[
            '__attribute__' => ':attribute'
        ]),
    'json'                 => __('The __attribute__ must be a valid JSON string.',[
            '__attribute__' => ':attribute'
        ]),
    'max'                  => [
        'numeric' => __('The __attribute__ may not be greater than __max__.',[
            '__attribute__' => ':attribute',
            '__max__'       => ':max'
        ]),
        'file'    => __('The __attribute__ may not be greater than __max__ kilobytes.',[
            '__attribute__' => ':attribute',
            '__max__'       => ':max'
        ]),
        'string'  => __('The __attribute__ may not be greater than __max__ characters.',[
            '__attribute__' => ':attribute',
            '__max__'       => ':max'

        ]),
        'array'   => __('The __attribute__ may not have more than __max__ items.',[
            '__attribute__' => ':attribute',
            '__max__'       => ':max'
        ]),
    ],
    'mimes'                => __('The __attribute__ must be a file of type: __values__.',[
            '__attribute__' => ':attribute',
            '__values__' => ':values'
        ]),
    'min'                  => [
        'numeric' => __('The __attribute__ must be at least __min__.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min'
        ]),
        'file'    => __('The __attribute__ must be at least __min__ kilobytes.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min'
        ]),
        'string'  => __('The __attribute__ must be at least __min__ characters.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min'
        ]),
        'array'   => __('The __attribute__ must have at least __min__ items.',[
            '__attribute__' => ':attribute',
            '__min__' => ':min'
        ]),
    ],
    'not_in'               => __('The selected __attribute__ is invalid.',[
            '__attribute__' => ':attribute'
        ]),
    'numeric'              => __('The __attribute__ must be a number.',[
            '__attribute__' => ':attribute'
        ]),
    'present'              => __('The __attribute__ field must be present.',[
            '__attribute__' => ':attribute'
        ]),
    'regex'                => __('The __attribute__ format is invalid.',[
            '__attribute__' => ':attribute'
        ]),
    'required'             => __('The __attribute__ field is required.',[
            '__attribute__' => ':attribute'
        ]),
    'required_if'          => __('The __attribute__ field is required when :other is __value__.',[
            '__attribute__' => ':attribute',
            '__value__' => ':value'
        ]),
    'required_unless'      => __('The __attribute__ field is required unless __other__ is in __values__.',[
            '__attribute__' => ':attribute',
            '__other__' => ':other',
            '__values__' => ':values'
        ]),
    'required_with'        => __('The __attribute__ field is required when __values__ is present.',[
            '__attribute__' => ':attribute',
            '__values__' => ':values'
        ]),
    'required_with_all'    => __('The __attribute__ field is required when __values__ is present.',[
            '__attribute__' => ':attribute',
            '__values__' => ':values'
        ]),
    'required_without'     => __('The __attribute__ field is required when __values__ is not present.',[
            '__attribute__' => ':attribute',
            '__values__' => ':values'
        ]),
    'required_without_all' => __('The __attribute__ field is required when none of __values__ are present.',[
            '__attribute__' => ':attribute',
            '__values__'    => ':values'
        ]),
    'same'                 => __('The __attribute__ and __other__ must match.',[
            '__attribute__' => ':attribute',
            '__other__'     => ':other',
        ]),
    'size'                 => [
        'numeric' => __('The __attribute__ must be __size__.',[
            '__attribute__' => ':attribute',
            '__size__'      => ':size'
        ]),
        'file'    => __('The __attribute__ must be __size__ kilobytes.',[
            '__attribute__' => ':attribute',
            '__size__'      => ':size'
        ]),
        'string'  => __('The __attribute__ must be __size__ characters.',[
            '__attribute__' => ':attribute',
            '__size__'      => ':size'
        ]),
        'array'   => __('The __attribute__ must contain __size__ items.',[
            '__attribute__' => ':attribute',
            '__size__'      => ':size'
        ]),
    ],
    'string'               => __('The __attribute__ must be a string.',[
            '__attribute__' => ':attribute'
        ]),
    'timezone'             => __('The __attribute__ must be a valid zone.',[
            '__attribute__' => ':attribute'
        ]),
    'unique'               => __('The __attribute__ has already been taken.',[
            '__attribute__' => ':attribute'
        ]),
    'url'                  => __('The __attribute__ format is invalid.',[
            '__attribute__' => ':attribute'
        ]),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'confirmation_code' => [
            'captcha' => __("The confirmation code is invalid."),
        ],
        'email' => [
            'unique_email' => __("The __attribute__ has already been taken.",[
            '__attribute__' => ':attribute'
        ]),
            'unique_client_member_email' => __("The __attribute__ has already been taken.",[
            '__attribute__' => ':attribute'
        ])
        ],
        'new_email' => [
            'unique_email' => __("The __attribute__ has already been taken.",[
            '__attribute__' => ':attribute'
        ]),
        ],
        'custom_domain' => [
            'domain' => __("The __attribute__ format is invalid.",[
            '__attribute__' => ':attribute'
        ]),
        ]
    ],

    'reactions' => [
        14 => [
            __("Ooops... No changes made!!"),
            __("Ooops... Nothing to process!!"),
            __("It seems you didn't modified anything!!")
        ], // for reaction code 14
        15 => [
            __("Please wait files uploading in progress ..."),
            __("Please wait a while its in progress ...")
        ], // for reaction code 15
        16 => __("Files uploaded successfully"), // for reaction code 16
        4  => [
            __("Oh..no...Something left invalid!!"),
            __("Ooops... Validation Errrrors...!!"),
            __("Oh... it looks invalid...!!"),
            __("Something went wrong with validation!!")
        ], // for validation error
        1  => __("Request processed successfully"), // for request success
        6  => __("Invalid request access"), // for invalid request
        19 => __("Oooops ... something went wrong on server. Try after some time!!"), // for request success
        20 => [
            __("Invalid request token. Please reload page and try again!!"),
            __("Request token Expired. Please reload page and try again!!"),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
