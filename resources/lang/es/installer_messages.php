<?php
    /*
    |--------------------------------------------------------------------------
    |   Líneas de idioma de aplicación
	|	Traducido al español por @EnigmaHernandez In Telegram
	|   https://github.com/enigmaelectronica/-Foxtrot-SaaS
    |--------------------------------------------------------------------------
    */
return [

    /*
     *
     *Traducciones compartidas.
     *
     */
    'title' => 'Instalador',
    'next' => 'Siguiente',
    'back' => 'Anterior',
    'finish' => 'Instalar',
    'forms' => [
        'errorTitle' => 'Ocurrieron los siguientes errores:',
    ],

    /*
     *
     * Traducciones de la página de inicio.
     *
     */
    'welcome' => [
        'templateTitle' => 'Bienvenido',
        'title'   => 'Instalador',
        'message' => 'Asistente de instalación.',
        'next'    => 'Consulta de requisitos',
    ],

    /*
     *
     * Traducción de la página de requisitos.
     *
     */
    'requirements' => [
        'templateTitle' => 'Paso 1 | Requisitos del Servidor (Xampp o Web Server)',
        'title' => 'Requisitos del Servidor',
        'next'    => 'Revisión de Permisos',
    ],

    /*
     *
     * Traducciones de la página de permisos.
     *
     */
    'permissions' => [
        'templateTitle' => 'Paso 2 | Permisos',
        'title' => 'Permisos',
        'next' => 'Configuración del entorno',
    ],

    /*
     *
     * Traducciones de páginas de entorno.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Paso 3 | Configuración del entorno',
            'title' => 'Configuraciones del entorno',
            'desc' => 'Seleccione cómo desea configurar las aplicaciones en el archivo <code>.env</code>.',
            'wizard-button' => 'Configuración del asistente de formulario',
            'classic-button' => 'Editor de texto clásico',
        ],
        'wizard' => [
            'templateTitle' => 'Paso 3 |Configuración del entorno | Asistente guiado',
            'title' => 'Configurando archivo <code>.env</code> por asistente',
            'tabs' => [
                'environment' => 'Entorno',
                'database' => 'Base de datos',
                'application' => 'Aplicación',
            ],
            'form' => [
                'name_required' => 'Se requiere un nombre de entorno.',
                'app_name_label' => 'Nombre de la Aplicación',
                'app_name_placeholder' => 'Nombre de la Aplicación',
                'app_environment_label' => 'Entorno de Aplicación',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Desarrollo',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Producción',
                'app_environment_label_other' => 'Otro',
                'app_environment_placeholder_other' => 'Ingrese su Entorno...',
                'app_debug_label' => 'Depuración de aplicaciones',
                'app_debug_label_true' => 'Verdadero',
                'app_debug_label_false' => 'Falso',
                'app_log_level_label' => 'Nivel de registro de la aplicación',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'aviso',
                'app_log_level_label_warning' => 'advertencia',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'critico',
                'app_log_level_label_alert' => 'alerta',
                'app_log_level_label_emergency' => 'emergencia',
                'app_url_label' => 'URL de la aplicación',
                'app_url_placeholder' => 'URL de la aplicación',
                'db_connection_failed' => 'no se pudo conectar a la base de datos.',
                'db_connection_label' => 'Conexión a la Base de Datos',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Host de Base de Datos',
                'db_host_placeholder' => 'Host de Base de Datos',
                'db_port_label' => 'Puerto de Base de Datos',
                'db_port_placeholder' => 'Puerto de Base de Datos',
                'db_name_label' => 'Nombre de Base de Datos',
                'db_name_placeholder' => 'Nombre de Base de Datos',
                'db_username_label' => 'Usuario de Base de Datos',
                'db_username_placeholder' => 'Usuario de Base de Datos',
                'db_password_label' => 'Contraseña de Base de Datos',
                'db_password_placeholder' => 'Contraseña de Base de Datos',

                'app_tabs' => [
                    'more_info' => 'Mas Info',
                    'broadcasting_title' => 'Difusión, almacenamiento en caché, sesión, &amp; cola',
                    'broadcasting_label' => 'Controlador de transmisión',
                    'broadcasting_placeholder' => 'Controlador de transmisión',
                    'cache_label' => 'Controlador de Cache',
                    'cache_placeholder' => 'Controlador de Cache',
                    'session_label' => 'Controlador de Sesión',
                    'session_placeholder' => 'Controlador de Sesión',
                    'queue_label' => 'Controlador de Cola',
                    'queue_placeholder' => 'Controlador de Cola',
                    'redis_label' => 'Controlador de Redis',
                    'redis_host' => 'Controlador de Redis',
                    'redis_password' => 'Contraseña de Redis',
                    'redis_port' => 'Puerto de Redis',

                    'mail_label' => 'Correo',
                    'mail_driver_label' => 'Controlador de Correo',
                    'mail_driver_placeholder' => 'Controlador de Correo',
                    'mail_host_label' => 'Servidor de Correo',
                    'mail_host_placeholder' => 'Servidor de Correo',
                    'mail_port_label' => 'Puerto de Correo',
                    'mail_port_placeholder' => 'Puerto de Correo',
                    'mail_username_label' => 'Usuario de Correo',
                    'mail_username_placeholder' => 'Usuario de Correo',
                    'mail_password_label' => 'Contraseña de Correo',
                    'mail_password_placeholder' => 'Contraseña de Correo',
                    'mail_encryption_label' => 'Encriptación de Correo',
                    'mail_encryption_placeholder' => 'Encriptación de correo',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'ID de aplicación Pusher',
                    'pusher_app_id_palceholder' => 'ID de aplicación Pusher',
                    'pusher_app_key_label' => 'Clave de aplicación Pusher',
                    'pusher_app_key_palceholder' => 'Clave de aplicación Pusher',
                    'pusher_app_secret_label' => 'Secreto de aplicación Pusher',
                    'pusher_app_secret_palceholder' => 'Secreto de aplicación Pusher',
                ],
                'buttons' => [
                    'setup_database' => 'Configurar Base de Datos',
                    'setup_application' => 'Configurar Aplicación',
                    'install' => 'Instalar',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Paso 3 | Configuración de Entorno | Editor Clásico',
            'title' => 'Editor de Entorno Clásico',
            'save' => 'Guardar .env',
            'back' => 'Usar asistente de formulario',
            'install' => 'Guardar e Instslar',
        ],
        'success' => 'Se ha guardado la configuración de su archivo .env.',
        'errors' => 'No se puede guardar el archivo .env, créelo manualmente.',
    ],

    'install' => 'Instalar',

    /*
     *
     * Traducciones de registro instaladas.
     *
     */
    'installed' => [
        'success_log_message' => 'Instalador de Script instalado exitosamente en: ',
    ],

    /*
     *
     * Traducciones de la página final.
     *
	 *
     */
    'final' => [
        'title' => 'Instalación finalizada',
        'templateTitle' => 'Instalación finalizada',
        'finished' => 'La aplicación se ha instalado correctamente.',
        'migration' => 'Migración &amp; Salida de la consola de semillas (seed):',
        'console' => 'Salida de la consola de aplicaciones:',
        'log' => 'Entrada de registro de instalación:',
        'env' => 'Archivo .env final:',        
		'exit' => 'Haga clic aquí para salir',
	],

     /*
     * Actualizar traducciones específicas
     *
     */
	 
    'updater' => [
        /*
         *
         * Shared translations.
         *
         */
        'title' => 'Actualizador de Laravel',

        /*
         *
         * Traducción de la página de bienvenida para la función de actualización.
         *
         */
        'welcome' => [
            'title'   => 'Bienvenido al actualizador.',
            'message' => 'Bienvenido al asistente de actualización.',
        ],

        /*
         *
         * Traducción de la página de bienvenida para la función de actualización.
         *
         */
        'overview' => [
            'title'   => 'Resumen',
            'message' => 'Hay 1 actualización.|Hay :number actualizaciones.',
            'install_updates' => 'Instalar Actualizaciones',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Finalizado',
            'finished' => 'La base de datos de la aplicación se ha actualizado correctamente.',
            'exit' => 'Haga clic aquí para salir',
        ],

        'log' => [
            'success_message' => 'Instaldor ACTUALIZADO con éxito en',
        ],
    ],
];
