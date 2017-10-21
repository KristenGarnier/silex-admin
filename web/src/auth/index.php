<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 10:56
 */

namespace App\auth;

use App\auth\events\subscribers\TableLoadSubscriber;
use App\auth\repositories\UserRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use App\auth\controllers\AuthController;

class AuthProvider implements ControllerProviderInterface
{

    protected $app;

    public function connect(Application $app)
    {
        $this->app = $app;
        $controllers = $app['controllers_factory'];

        $this->mountControllers($app);
        $this->mountRoutes($controllers);
        $this->mountServices($app);
        $this->mountRepositories($app);
        $this->mountEvents($app);

        return $controllers;
    }

    private function mountControllers(Application $app)
    {
        $app['auth.controller.auth'] = function () use ($app) {
            return new AuthController('salad');
        };
    }

    private function mountRepositories(Application $app)
    {
        $app['auth.repository.user'] = function() use ($app) {
            return new UserRepository($app['db'], $app['security.default_encoder']);
        };
    }

    private function mountServices(Application $app)
    {

    }

    private function mountRoutes($controllers)
    {
        $controllers->get('/', 'auth.controller.auth:indexAction');
        $controllers->get('/login', 'auth.controller.auth:loginAction');
    }

    private function mountEvents(Application $app)
    {
        $app['dispatcher']->addSubscriber(new TableLoadSubscriber());
    }
}
