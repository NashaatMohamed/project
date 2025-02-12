<?php

namespace App\Http\Controllers\Installer;

use App\Helpers\Installer\DatabaseManager;
use App\Helpers\Installer\FinalInstallManager;
use App\Helpers\Installer\InstalledFileManager;
use App\Helpers\Installer\PermissionsChecker;
use App\Helpers\Installer\RequirementsChecker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    /**
     * @var PermissionsChecker
     */
    protected $permissions;
    protected $requirements;
    private $databaseManager;

    /**
     * @param PermissionsChecker $checker
     */
    public function __construct(PermissionsChecker $per_checker, RequirementsChecker $req_checker, DatabaseManager $databaseManager)
    {
        $this->permissions = $per_checker;
        $this->requirements = $req_checker;
        $this->databaseManager = $databaseManager;

        if (is_installed()) {
            return redirect()->route('home')->send();
        }
    }

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('installer.welcome');
    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function permissions()
    {
        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        return view('installer.permissions', compact('permissions'));
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        return view('installer.requirements', compact('requirements', 'phpSupportInfo'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environment()
    {
        return view('installer.environment');
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_environment(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $validator = Validator::make($request->all(), $rules);

        // Redirect if there are any errors
        if ($validator->fails()) {
            return $redirect->route('installer.environment')->withInput()->withErrors($validator->errors());
        }

        // Check database credentials
        if (!$this->checkDatabaseConnection($request)) {
            return $redirect->route('installer.environment')->withInput()->withErrors([
                'database_connection' => __('Database connection was failed. Please make sure you have type the correct credentials.'),
            ]);
        }

        // Configure env
        $this->setEnvironmentValue([
            'APP_NAME' => $request->app_name,
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_LOG_LEVEL' => 'debug',
            'APP_URL' => $request->app_url,
            'DB_CONNECTION' => $request->database_connection,
            'DB_HOST' => $request->database_hostname,
            'DB_PORT' => $request->database_port,
            'DB_DATABASE' => $request->database_name,
            'DB_USERNAME' => $request->database_username,
            'DB_PASSWORD' => $request->database_password,
            'BROADCAST_DRIVER' => 'log',
            'CACHE_DRIVER' => 'file',
            'SESSION_DRIVER' => 'file',
            'QUEUE_CONNECTION' => 'database',
            'REDIS_HOST' => '127.0.0.1',
            'REDIS_PASSWORD' => '',
            'REDIS_PORT' => 6379,
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'smtp.mailtrap.io',
            'MAIL_PORT' => 2525,
            'MAIL_USERNAME' => '',
            'MAIL_PASSWORD' => '',
            'MAIL_ENCRYPTION' => '',
            'MAIL_FROM_ADDRESS' => 'contact@example.com',
            'MAIL_FROM_NAME' => 'Acme Company',
            'FACEBOOK_CLIENT_ID' => '',
            'FACEBOOK_CLIENT_SECRET' => '',
            'GOOGLE_CLIENT_ID' => '',
            'GOOGLE_CLIENT_SECRET' => '',
            'TWITTER_CLIENT_ID' => '',
            'TWITTER_CLIENT_SECRET' => '',
            'LINKEDIN_CLIENT_ID' => '',
            'LINKEDIN_CLIENT_SECRET' => '',
        ]);

        return redirect()->route('installer.database');
    }

    /**
     * Migrate and seed the database.
     */
    public function database()
    {
        // Migrate and seed
        $response = $this->databaseManager->migrateDatabase();

        return redirect()->route('installer.final')->with(['message' => $response]);
    }

    /**
     * Update installed file and display finished view.
     *
     * @param \App\Helpers\Installer\InstalledFileManager $fileManager
     * @param \App\Helpers\Installer\FinalInstallManager $finalInstall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();

        return view('installer.finished', compact('finalMessages', 'finalStatusMessage'));
    }

    /**
     * Validate database connection
     *
     * @param Request $request
     * @return bool
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();

        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Save Settings on .env file
    private function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        $str .= "\n";

        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}
