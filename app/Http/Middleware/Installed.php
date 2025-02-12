<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Installed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If there is no subscription at all
        $route = $request->route()->getName();
        if ($route && (str_contains($route, 'installer.') || str_contains($route, 'updater.'))) {
            return $next($request);
        }

        // Check if the application is installed
        if (!is_installed()) {
            return redirect('/install');
        } 

        // Check if the application needs to upgrade
        if (!$this->alreadyUpdated()) {
            return redirect('/update');
        }

        return $next($request);
    }

    /**
     * Get the migrations in /database/migrations.
     * 
     * @return array Array of migrations name, empty if no migrations are existing
     */
    public function getMigrations()
    {
        $migrations = glob(database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'*.php');

        return str_replace('.php', '', $migrations);
    }

    /**
     * Get the migrations that have already been ran.
     *
     * @return \Illuminate\Support\Collection List of migrations
     */
    public function getExecutedMigrations()
    {
        // migrations table should exist, if not, user will receive an error.
        return DB::table('migrations')->get()->pluck('migration');
    }

    /**
     * If application is already updated.
     *
     * @return bool
     */
    public function alreadyUpdated()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();
        
        // If the count of migrations and dbMigrations is equal,
        // then the update as already been updated.
        if (count($migrations) == count($dbMigrations)) {
            return true;
        }

        // Continue, the app needs an update
        return false;
    }
}