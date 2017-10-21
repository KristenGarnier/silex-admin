<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 12:33
 */

namespace Config\services;

use Doctrine\DBAL\Connection;
use Silex\Application;
use Doctrine\DBAL\Schema\Table;
use Config\events\TableLoadEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TableLoader
{
    protected $schemaManager;
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

    }

    public function loadTables()
    {
        $event = new TableLoadEvent($this->app);
        $this->app['dispatcher']->dispatch($event::NAME, $event);
    }
}