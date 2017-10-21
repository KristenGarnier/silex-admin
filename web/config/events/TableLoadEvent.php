<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 18:25
 */

namespace Config\events;

use Silex\Application;
use Symfony\Component\EventDispatcher\Event;

class TableLoadEvent extends Event
{

    const NAME = 'table.load';
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

}