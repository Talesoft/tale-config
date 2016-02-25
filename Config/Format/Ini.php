<?php

use Tale\Config\FormatInterface;

class Ini implements FormatInterface
{

    public static function load($path)
    {

        return parse_ini_file($path, true);
    }
}