<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 12:01
 */

namespace Config;


use Silex\Application;
use Symfony\Component\Debug\Debug;
use App\auth\providers\UserProvider;

class Config
{
    static public function load(Application $app)
    {
        $app['db.credentials'] = array(
            'driver' => 'pdo_mysql',
            'host' => 'db',
            'dbname' => 'silex',
            'user' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        );

        $app['security.configuration'] = array(
            'security.firewalls' => array(
                'login' => array(
                    'pattern' => '^/auth/login$',
                ),
                'secured' => array(
                    'pattern' => '^.*$',
                    'form' => array('login_path' => '/auth/login', 'check_path' => '/auth/login_check'),
                    'logout' => array('logout_path' => '/auth/logout', 'invalidate_session' => true),
                    'users' => function () use ($app) {
                        return new UserProvider($app['db']);
                    },
                ),
            )
        );

        $app['twig.configuration'] = array(
            'twig.path' => __DIR__.'/../templates',
            'twig.options' => array(
                'cache' => __DIR__.'/../var/cache/twig'
            )
        );
    }

    static public function loadDev(Application $app)
    {
        self::load($app);

        // enable the debug mode
        Debug::enable();
        $app['debug'] = true;
    }
}