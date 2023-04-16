<?php

declare(strict_types=1);

namespace core;

use core\models\UserModel;

class AuthenticatedUser
{
    private static $instance;
    /**
     * @var UserModel
     */
    private $user;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $email
     * @param string $pass
     * @return bool
     */
    public function login($email, $pass)
    {
        // check if user exists and password is correct
        $user = UserModel::getByEmail($email);
        if ($user && password_verify($pass, $user->password)) {
            // set user id to session
            $_SESSION['user'] = (int)$user->id;

            return true;
        }

        return false;
    }

    public function getName()
    {
        return $this->user()->name ?? '';
    }

    /**
     * Is user session active
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        // is user logged in?
        return isset($_SESSION['user']) && $_SESSION['user'] > 0;
    }

    /**
     * Logout the user
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['user']);
    }

    /**
     * @return UserModel|null
     */
    public function user()
    {
        if (!$this->user && $this->isLoggedIn()) {
            $this->user = new UserModel((int)$_SESSION['user']);
        }

        return $this->user;
    }
}
