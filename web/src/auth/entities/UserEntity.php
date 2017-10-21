<?php

namespace App\auth\entities;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEntity implements UserInterface
{
    private $id;
    private $username;
    private $password;
    private $roles;
    private $salt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public static function getForm($app, $data = null)
    {
        return $app['form.factory']->createBuilder(FormType::class, $data)
            ->add('username', TextType::class, array(
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array('max' => 255))
                    ),
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Nom d\'utilisateur'
                )
            )
            ->add('password', PasswordType::class, array(
                    'attr' => ['class' => 'form-control'],
                    'label' => 'Mot de passe',
                    'required' => false
                )
            )
            ->getForm();
    }

    public function toArray()
    {
        $result = [];
        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}