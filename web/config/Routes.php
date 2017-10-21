<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 12:06
 */

namespace Config;

use Silex\Application;
use App\auth\AuthProvider;

class TopLevelRoutes
{
    static public function load(Application $app)
    {
        $app->mount('/auth', new AuthProvider());
    }

    static public function loadDev(Application $app)
    {
        self::load($app);
    }
}