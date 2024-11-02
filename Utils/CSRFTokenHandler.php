<?php

declare(strict_types=1);

namespace App\Utils;

use App\Http\Session;

class CSRFTokenHandler
{

    const TOKEN_KEY = 'CSRF_TOKEN';
    const TOKEN_LIFESPAN = 60 * 15; // 15 minutes

    const HTML_KEY_NAME = '_token';

    public static function generate(): string
    {
        $token = bin2hex(random_bytes(32));
        $expireTime = time() + self::TOKEN_LIFESPAN;

        $tokenObj = [
            'token' => $token,
            'expire_time' => $expireTime,
        ];

        $tokens = Session::get(self::TOKEN_KEY) ?? [];
        Session::set(self::TOKEN_KEY, array_merge($tokens, [$tokenObj]));

        return $token;
    }

    public static function HTML(): string
    {
        $token = self::generate();
        $input = '<input type="hidden" name="' . self::HTML_KEY_NAME . '" value="' . $token . '">';

        return $input;
    }

    private static function isExpire(array $tokenObj): bool
    {
        return time() > $tokenObj['expire_time'];
    }

    public static function remove(string $token, bool $removeExpire = true)
    {
        $tokens = Session::get(self::TOKEN_KEY) ?? [];
        foreach($tokens as $key => $tokenObj){
            if($tokenObj['token'] === $token){
                unset($_SESSION[self::TOKEN_KEY][$key]);
            }
            if($removeExpire && self::isExpire($tokenObj)){
                unset($_SESSION[self::TOKEN_KEY][$key]);
            }
        }
    }

    public static function removeExpire()
    {
        $tokens = Session::get(self::TOKEN_KEY) ?? [];
        foreach($tokens as $key => $tokenObj){
            if(self::isExpire($tokenObj)){
                unset($_SESSION[self::TOKEN_KEY][$key]);
            }
        }
    }

    public static function isValid(string $token, bool $delete = true): bool
    {
        if(!$token) return false;

        $tokens = Session::get(self::TOKEN_KEY) ?? [];

        foreach($tokens as $key => $tokenObj){
            if(self::isExpire($tokenObj)){

                // delete if expire // don't care $delete = false 

                unset($_SESSION[self::TOKEN_KEY][$key]);

                return false;
            }

            if($tokenObj['token'] === $token && !self::isExpire($tokenObj)){

                if($delete) unset($_SESSION[self::TOKEN_KEY][$key]);

                return true;
            }
        }

        return false;
    }
}