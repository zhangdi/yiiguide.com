<?php

return [
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => ['log'],
    'basePath' => ROOT_PATH . '/src',
    'vendorPath' => ROOT_PATH . '/vendor',
    'aliases' => [
        '@App' => ROOT_PATH . '/src',
        '@resources'=>ROOT_PATH.'/resources'
    ],

    'enableCoreCommands'=>false,
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
