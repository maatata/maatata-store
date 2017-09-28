<?php

// default time zone
$timeZone = 'UTC';

// set time timezone for store
if (getStoreSettings('timezone')) {
    $timeZone = getStoreSettings('timezone');
}
// set default time zone    
date_default_timezone_set($timeZone);

// set configuration items
config([
    'app.timezone' => $timeZone,
    'lfm.images_url' => url('/media-storage/upload-manager-assets'),
    'lfm.files_url' => url('/media-storage/upload-manager-assets'),
    'mail.from.address' => env('MAIL_FROM_ADD') ?: getStoreSettings('business_email'),
    'mail.from.name' => getStoreSettings('store_name'),
    '__tech.mail_from' => [
        env('MAIL_FROM_ADD', getStoreSettings('business_email')),
        env('MAIL_FROM_NAME', getStoreSettings('store_name')),
    ],
]);
