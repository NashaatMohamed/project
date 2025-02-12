<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use App\Helpers\Installer\DatabaseManager;
use App\Helpers\Installer\InstalledFileManager;
use App\Helpers\Installer\MigrationsHelper;

class UpdateController extends Controller
{
    use MigrationsHelper;

    /**
     */
    public function __construct()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();
        
        // If the count of migrations and dbMigrations is equal,
        // then the update as already been updated.
        if (count($migrations) == count($dbMigrations) && request()->route()->getName() != 'updater.final') {
            return redirect()->route('home')->send();
        }
    }

    /**
     * Display the updater welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('installer.update.welcome');
    }

    /**
     * Display the updater overview page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        return view('installer.update.overview', ['numberOfUpdatesPending' => count($migrations) - count($dbMigrations)]);
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $databaseManager = new DatabaseManager;
        $response = $databaseManager->migrateDatabase(false);

        return redirect()->route('updater.final')->with(['message' => $response]);
    }

    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager)
    {
        $fileManager->update();

        return view('installer.update.finished');
    }
}