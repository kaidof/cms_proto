<?php

declare(strict_types=1);

namespace core\models;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password Password hash
 * @property int $is_active
 * @property string $created_at
 */
class UserModel extends BaseModel
{
    protected $table = 'users';

    protected $hidden = ['password'];

    /**
     * Set the password
     *
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return UserModel|null
     */
    public static function getByEmail($email)
    {
        $array = db()->queryOne('SELECT id FROM ' . (new self())->table . ' WHERE email = :email', ['email' => $email]);

        if (isset($array['id']) && $array['id'] > 0) {
            return new self($array['id']);
        }

        return null;
    }

}
