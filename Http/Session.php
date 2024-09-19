<?php

declare(strict_types = 1);

namespace App\Http;

class Session
{
    // 30 minutes
    const LIFETIME = 60 * 30;
    // 5 minutes
    const REGENERATE_TIME = 60 * 5;

    public function __construct()
    {
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);

        session_set_cookie_params([
            'lifetime' => self::LIFETIME,
            'domain' => \DOMAIN,
            'path' => '/',
            'secure' => false, // https - true, http - false
            'httponly' => true
        ]);

        $this->regenerateId();
    }

    private function regenerateId()
    {
        if(!self::start()) return false;

        $time = (int) self::get('lastTimeRegenerate');

        if($time + self::REGENERATE_TIME < time()){
            session_regenerate_id(true);
            self::set('lastTimeRegenerate', time());
        }
    }

    public static function start(): bool
    {
        if(headers_sent()) return false;

        if(session_status() !== PHP_SESSION_ACTIVE)
            if(!session_start())
                return false; 

        return true;
    }

    public static function set(string $key, $value): bool
    {
        if(!self::start()) return false;

        $_SESSION[$key] = $value;

        return true;
    }

    public static function get(string $key)
    {
        if(!self::start()) return '';

        $value = $_SESSION[$key] ?? '';

        return $value;
    }

    public static function getAndUnset(string $key)
    {
        $value = self::get($key);        

        self::unset($key);

        return $value;
    }

    public static function unset(string $key): void
    {
        if(self::start()) unset($_SESSION[$key]);   
    }

    public static function isset(string $key): bool
    {
        if(!self::start()) return false;

        return isset($_SESSION[$key]);
    }

    public static function destroy()
    {
        if(!self::start()) return;

        $_SESSION = [];
        session_destroy();
    }
}