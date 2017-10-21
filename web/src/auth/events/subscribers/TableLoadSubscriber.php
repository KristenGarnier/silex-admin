<?php
/**
 * Created by PhpStorm.
 * User: kristen-linux
 * Date: 21/10/17
 * Time: 18:58
 */
namespace App\auth\events\subscribers;

use App\auth\entities\UserEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Config\events\TableLoadEvent;
use Doctrine\DBAL\Schema\Table;

class TableLoadSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TableLoadEvent::NAME => 'onMountTables',
        );
    }

    public function onMountTables(TableLoadEvent $event)
    {
        $schemaManager = $event->getApp()['db']->getSchemaManager();
        if (!$schemaManager->tablesExist('users')) {
            $users = new Table('users');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addUniqueIndex(array('username'));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('salt', 'string', array('length' => 255));
            $users->addColumn('roles', 'string', array('length' => 255));

            $schemaManager->createTable($users);

            $admin = new UserEntity();
            $admin->setUsername('admin');
            $admin->setPassword('salade');
            $admin->setRoles('ROLE_ADMIN');

            $event->getApp()['auth.repository.user']->save($admin);
        }
    }
}