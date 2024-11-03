<?php

namespace app\models;

use app\lib\cArray;

class UserRole
{
    private static ?cArray $roles = null;
    private static ?string $role = null;
    private const SESSION_PLACE = 'user_role';

    public static function get(): ?int
    {
        if (!isset(self::$role)) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();

            if (isset($_SESSION[self::SESSION_PLACE])) {
                self::$role = $_SESSION[self::SESSION_PLACE];
            } else {
                return null;
            }
        }
        if (self::$roles === null)
            self::$roles = new cArray(self::SESSION_PLACE, ['admin' => 0]);
        return self::$roles[self::$role];
    }

    public static function set(string $role): int
    {
        if (self::$roles === null)
            self::$roles = new cArray(self::SESSION_PLACE, ['admin' => 0]);
        if (!isset(self::$roles[$role])) return -1;
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        self::$role = $role;
        $_SESSION[self::SESSION_PLACE] = $role;
        // echo self::$roles->render();
        return self::$roles[self::$role];
    }

    public static function reset(): void
    {
        self::$role = null;
        unset($_SESSION[self::SESSION_PLACE]);
    }

    public static function addRoleUnder(string $name, string $pos): int
    {
        if (self::$roles === null)
            self::$roles = new cArray(self::SESSION_PLACE, ['admin' => 0]);
        $value = self::$roles[$pos];
        if ($value === null) return -1;
        else {
            self::$roles[$name] = $value + 1;
            return $value + 1;
        }
    }
    public static function accessCheck(?string $role): bool
    {
        if (self::$roles === null)
            self::$roles = new cArray(self::SESSION_PLACE, ['admin' => 0]);
        if (self::$roles[$role] === null) return true;
        if (self::$role === null) return false;
        return self::$roles[$role] <= self::$roles[self::$role];
    }

    public static function getAllRoles(): cArray
    {
        if (self::$roles === null)
            self::$roles = new cArray(self::SESSION_PLACE, ['admin' => 0]);
        return self::$roles;
    }
}
