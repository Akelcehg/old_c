<?php

return [
    //'revisionConsolePath' =>'"C:\Program Files\Mercurial\hg.exe"',
    'client' => 'Aser',
    'revisionConsolePath' =>'/usr/bin/hg',
    'admin_url' => '/admin/admins/index',
    'adminEmail' => 'noreply@aser.com.ua',
    'client_name' => 'aser',
    'client_name_titles' => 'Aser',
    'defaultPageSize' => 25,
    'MaxWaitingPeriod' => 3,
    'LowBatteryLevel' => 2,
    'MinimumBalanceLevel' => 5,
    'HeaderTelephone' => '048 770-24-87',
    'beginDate' => date('Y-m') . '-01 00:00:00',
    'version' => '2.1.5',
    'EmailAlertNotificationEnabled' => false,
    'TelegramAlertNotificationEnabled' => true,
    'TelegramNotificationBotName'=>'AserNotificationBot',
    'sslkey'=>'',
    'cronJobs' => [
        'alert-input-command/index' => [
            'cron' => '*/20 * * * * *', // Every hour
        ],
    ],
    'countersPhotoPath' => 'img/counters_foto/',
];

