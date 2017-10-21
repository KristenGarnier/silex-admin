<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 11:08
 */

namespace App\auth\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    protected $repo;

    public function __construct($repository)
    {
        $this->repo = $repository;
    }

    public function indexAction() {
        return new JsonResponse(['hello world!']);
    }

    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}