<?php

$EM_CONF['formhandler'] = [
    'title' => 'Formhandler HCaptcha',
    'description' => 'Add error check hcaptcha to Formhandler.',
    'category' => 'frontend',
    'version' => '13.0.2',
    'state' => 'stable',
    'author' => 'Reinhard Führicht',
    'author_email' => 'r.fuehricht@gmail.com',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-13.99.99',
            'hcaptcha' => '2.3.0-2.99.99'
        ],
        'conflicts' => [
        ],
    ],
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1
];
