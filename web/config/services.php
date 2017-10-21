<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 11:49
 */

namespace Config;

use Config\services\TableLoader;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;

class TopLevelServices
{
    static public function load(Application $app)
    {
        $app->register(new ServiceControllerServiceProvider());
        $app->register(new AssetServiceProvider());
        $app->register(new TwigServiceProvider(), $app['twig.configuration']);
        $app->register(new HttpFragmentServiceProvider());
        $app->register(new SessionServiceProvider());

        $app->register(new SecurityServiceProvider(), $app['security.configuration']);

        $app->register(new DoctrineServiceProvider(), array(
            'db.options' => $app['db.credentials'],
        ));

        $app['table.loader'] = function() use ($app) {
            return new TableLoader($app);
        };

        $app['twig'] = $app->extend('twig', function ($twig, $app) {
            // add custom globals, filters, tags, ...

            return $twig;
        });
    }

    static public function loadDev(Application $app)
    {
        self::load($app);

        $app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => __DIR__ . '/../var/logs/silex_dev.log',
        ));

        $app->register(new WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
        ));
    }
}
