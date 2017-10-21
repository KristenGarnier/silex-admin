<?php

namespace App\auth\repositories;

use Doctrine\DBAL\Connection;
use App\auth\entities\UserEntity;

class UserRepository
{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    protected $encoder;

    public function __construct(Connection $db, $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }
    /**
     * Saves the user to the database.
     *
     * @param UserEntity $user
     *
     * @return UserEntity|False $user
     */
    public function save(UserEntity $user)
    {
        $userData = array(
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles()
        );
        if ($user->getId()) {
            $this->db->update('users', $userData, array('id' => $user->getId()));
        }
        else {

            $userData['salt'] = uniqid(mt_rand(), true);
            $userData['password'] = $this->encoder->encodePassword($userData['password'], $userData['salt']);

            $this->db->insert('users', $userData);
            $last = $this->db->lastInsertId();
            return $this->find($last);
        }
    }


    /**
     * Deletes the user.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        // If the user had an image, delete it.
        return $this->db->delete('users', array('id' => $id));
    }
    /**
     * Returns the total number of users.
     *
     * @return integer The total number of users.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(id) FROM users');
    }
    /**
     * Returns an user matching the supplied id.
     *
     * @param integer $id
     *
     * @return UserEntity|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $userData = $this->db->fetchAssoc('SELECT * FROM users WHERE id = ?', array($id));
        return $userData ? $this->buildUser($userData) : FALSE;
    }

    /**
     * Returns an user matching the supplied username.
     *
     * @param string $username
     *
     * @return UserEntity|false An entity object if found, false otherwise.
     */
    public function findByName($username)
    {
        $userData = $this->db->fetchAssoc('SELECT * FROM users WHERE username = ?', array($username));
        return $userData ? $this->buildUser($userData) : FALSE;
    }

    /**
     * Returns a collection of users, sorted by name.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of users, keyed by user id.
     */
    public function findAll($limit = 10000, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('username' => 'ASC');
        }
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('u.*')
            ->from('users', 'u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('u.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $usersData = $statement->fetchAll();
        $users = array();
        foreach ($usersData as $userData) {
            $userId = $userData['id'];
            $users[$userId] = $this->buildUser($userData);
        }
        return $users;
    }
    /**
     * Instantiates an user entity and sets its properties using db data.
     *
     * @param array $userData
     *   The array of db data.
     *
     * @return UserEntity
     */
    protected function buildUser($userData)
    {
        $user = new UserEntity();
        $user->setId($userData['id']);
        $user->setUsername($userData['username']);
        $user->setPassword($userData['password']);
        $user->setSalt($userData['salt']);
        $user->setRoles($userData['roles']);
        return $user;
    }

}