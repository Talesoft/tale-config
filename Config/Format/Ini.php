<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;

class Ini implements FormatInterface
{

    public static function getExtensions()
    {

        return ['.ini'];
    }

    public static function load($path)
    {

        return parse_ini_file($path, true);
    }
}