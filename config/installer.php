<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'core' => [
        'minPhpVersion' => '7.3',
        'minMysqlVersion' => '5.7.3',
    ],
    'final' => [
        'key' => true,
        'publish' => false,
    ],
    'requirements' => [
        'php' => [
            'bcmath',
            'ctype',
            'cURL',
            'fileinfo',
            'JSON',
            'mbstring',
            'openssl',
            'pdo',
            'tokenizer',
            'xml',
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/framework/'     => '775',
        'storage/logs/'          => '775',
        'bootstrap/cache/'       => '775',
        'public/uploads'         => '775',
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Form Wizard Validation Rules & Messages
    |--------------------------------------------------------------------------
    |
    | This are the default form field validation rules. Available Rules:
    | https://laravel.com/docs/5.4/validation#available-validation-rules
    |
    */
    'environment' => [
        'form' => [
            'rules' => [
                'app_name'              => 'required|string|max:180',
                'app_url'               => 'required|url',
                'database_connection'   => 'required|string',
                'database_hostname'     => 'required|string',
                'database_port'         => 'required|numeric',
                'database_name'         => 'required|string',
                'database_username'     => 'required|string',
                'database_password'     => 'nullable|string',
            ],
        ],
    ],
];