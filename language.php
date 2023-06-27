<?php declare (strict_types = 1);

abstract class Language
{
    public static function lang():  ? string
    {
        $lang = null;
        if (isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        }

        if (isset($_SESSION['lang'])) {
            $lang = $_COOKIE['lang'];
        }

        if (in_array($lang, ['en', 'ro'])) {
            return $lang;
        } else {
            return null;
        }
    }
}
