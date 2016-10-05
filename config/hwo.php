<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Define here
    |--------------------------------------------------------------------------
    |
    */
    /**
     * config for get weather
     */
    'weather_zc' => '96704',//zip code at hawaii
    'weather_u' => 'c',//units (f or c)
    
    /*
    |--------------------------------------------------------------------------
    | Checkpoints
    |--------------------------------------------------------------------------
    |
    | When logging in, checking for existing sessions and failed logins occur,
    | you may configure an indefinite number of "checkpoints". These are
    | classes which may respond to each event and handle accordingly.
    | We ship with throttling checkpoint. Feel free to add, remove or re-order
    | these.
    |
    */
    'checkpoints' => [

        'throttle',
    ],

    
    /*
    |--------------------------------------------------------------------------
    | Temporary
    |--------------------------------------------------------------------------
    |
    | Here you may specify the time (in seconds) which temporary hashs expire.
    | By default, temporary expire after 24 hours. 
    | The lottery is used for garbage collection, expired codes will be cleared
    | automatically based on the provided odds.
    |
    */
    'temporary' => [
        'expires' => 86400,
        'lottery' => [2, 100],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Throttling
    |--------------------------------------------------------------------------
    |
    | Here, you may configure your site's throttling settings. There are three
    | types of throttling.
    |
    | The first type is "global". Global throttling will monitor the overall
    | failed login attempts across your site and can limit the effects of an
    | attempted DDoS attack.
    |
    | The second type is "ip". This allows you to throttle the failed login
    | attempts (across any account) of a given IP address.
    |
    | The third type is "user". This allows you to throttle the login attempts
    | on an individual user account.
    |
    | Each type of throttling has the same options. The first is the interval.
    | This is the time (in seconds) for which we check for failed logins. Any
    | logins outside this time are no longer assessed when throttling.
    |
    | The second option is thresholds. This may be approached one of two ways.
    | the first way, is by providing an key/value array. The key is the number
    | of failed login attempts, and the value is the delay, in seconds, before
    | the next attempt can occur.
    |
    | The second way is by providing an integer. If the number of failed login
    | attempts outweigh the thresholds integer, that throttle is locked until
    | there are no more failed login attempts within the specified interval.
    |
    | On this premise, we encourage you to use array thresholds for global
    | throttling (and perhaps IP throttling as well), so as to not lock your
    | whole site out for minutes on end because it's being DDoS'd. However,
    | for user throttling, locking a single account out because somebody is
    | attempting to breach it could be an appropriate response.
    |
    | You may use any type of throttling for any scenario, and the specific
    | configurations are designed to be customized as your site grows.
    |
    */

    'throttling' => [
        'global' => [
            'interval' => 900,
            'thresholds' => [
                10 => 1,
                20 => 2,
                30 => 4,
                40 => 8,
                50 => 16,
                60 => 12
            ],
        ],
        'ip' => [
            'interval' => 900,
            'thresholds' => 5,
        ],
        'user' => [
            'interval' => 900,
            'thresholds' => 5,
        ],

    ],

];
