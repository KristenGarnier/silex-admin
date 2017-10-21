Silex Admin Skeleton
==============

Welcome to the Silex admin Skeleton - a fully-functional Silex admin application that you
can use as the skeleton for your new admin dashboard.

This document contains information on how to start using the Silex admin Skeleton.

Creating a Silex admin Application
----------------------------

Simply clone or download this repository.

This repository includes docker containers to work with.
Insure you have `docker` and `docker-compose` installed and available on your computer.

Go to the directory `docker/` from the root of the project and run `docker-compose up`.
The project will run on the docker's configured IP.
Now go to your host, remap the docker's configured IP to `local.dev`.
Et voilà ! If you access `local.dev/index_dev.php` on your browser you should see the login page of the silex admin application.

**Warning** Db credentials are made to work with the docker database, please change them if you do not user docker environments.

Getting started with Silex admin
--------------------------------

This Silex admin application have been built with modularity in mind.

In the base application, you will find under the `src/` directory all the base modules that come with Silex admin application.
You are free to modify any of theses modules, and create yours.

**The architecture of a module goes as following:**

* Controllers : Where all the module's controller lives ( Controllers are used after a route )
* Entity : Where all the module's entities lives ( Class maping of your database tables )
* Events : Splitted between events and subscribers, put inside all classes that have to do with events 
* Providers : Where the module's providers lives
* Repositories : All the query available for the entities
* Services : Hosts all the module's services
* index.php : Module provider, it will be responsible for mounting, controllers, routes, services, events and repositories from the module.

Everything in theses modules should stay as much as you can self contained.
The preferred way to communicate between modules is with Events, to decouple the two modules.

**How do i activate my module ?**

You simple go to the `config/Routes.php` file, in the load method you can mount your module : 
``` php
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
        // mount your module here in the same way as above (Don't forget to specify the use statement)
    }

    static public function loadDev(Application $app)
    {
        self::load($app);
        
        // If your module should only work in dev mode, mount your module here ! 
    }
}
```

**Few things to know**

The core send out an event at the startup of the app for loading all the table. The event is 'table.load' and sends out $app.
This event is intended for the module to initate its tables in the database and seed with minimum information or no information at all.
You can find an exemple into the auth module in the file `src/auth/events/subscribers/TableLoaderSubscriber.php`. This is how the module create its table and fill the minimum required information for it to work.

If you have created a new class and silex throw namespace errors, or class not foud errors, please try `composer dumpautolad -o` fist.

Silex admin configuration
-------------------------

Has you can see, there is a bunch of files in the config directory.

Most of theses files are here to initiate the core of the application, what will be used through all the app.

Here is some explanation for each of the files :

* Boot.php : Is responsible for bootstraping all the application together. It loads the configuration, the routes, and services, and then send back an app ready to run.
* Config.php : Where the configuration of all top level services lives
* Routes.php : Where all top level routes lives (Usually only to declare modules)
* Services.php : Where all the top level services are registered
* Services : Where custom top level services are.
* Events : Where custom top level events are.

**What do i mean by top level** : Top level means this is used by the core of the admin, and can be used in your modules.

Enjoy!