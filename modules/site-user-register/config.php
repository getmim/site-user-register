<?php

return [
    '__name' => 'site-user-register',
    '__version' => '0.1.0',
    '__git' => 'git@github.com:getmim/site-user-register.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'app/site-user-register' => ['install','remove'],
        'modules/site-user-register' => ['install','update','remove'],
        'theme/site/me' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-user-main' => NULL
            ],
            [
                'lib-form' => NULL
            ],
            [
                'site' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'SiteUserRegister\\Controller' => [
                'type' => 'file',
                'base' => 'app/site-user-register/controller'
            ],
            'SiteUserRegister\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-user-register/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteMeRegister' => [
                'path' => [
                    'value' => '/me/register'
                ],
                'handler' => 'SiteUserRegister\\Controller\\Register::create',
                'method' => 'GET|POST'
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'site.me.register' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'text' => 'slug',
                        'unique' => [
                            'model' => 'LibUserMain\\Model\\User',
                            'field' => 'name'
                        ]
                    ]
                ],
                'fullname' => [
                    'label' => 'Fullname',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'type' => 'password',
                    'meter' => FALSE,
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'length' => [
                            'min' => 6
                        ]
                    ]
                ]
            ]
        ]
    ]
];
