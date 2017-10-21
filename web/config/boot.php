<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 11:50
 */

namespace Config;

use Silex\Application;
use Config\TopLevelServices;
use Config\TopLevelRoutes;

class Boot
{
    static public function load()
    {
        $app = new Application();
        Config::load($app);
        TopLevelServices::load($app);
        TopLevelRoutes::load($app);

        $app['table.loader']->loadTables();

        return $app;
    }

    static public function loadDev()
    {
        $app = new Application();
        Config::loadDev($app);
        TopLevelServices::loadDev($app);
        TopLevelRoutes::loadDev($app);

        $app['table.loader']->loadTables();

        return $app;
    }
}


