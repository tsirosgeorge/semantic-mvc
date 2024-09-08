<?php

namespace App\lib;

class PasswordUtils
{
    /**
     * Verify the provided password against the hashed password.
     *
     * @param string $password The plain-text password.
     * @param string $hashedPassword The hashed password from the database.
     * @return bool Returns true if the password is correct, false otherwise.
     */
    public static function verifyPassword($password, $hashedPassword)
    {
        return $password === $hashedPassword;
    }

    /**
     * Hash a password for storage.
     *
     * @param string $password The plain-text password to hash.
     * @return string The hashed password.
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
