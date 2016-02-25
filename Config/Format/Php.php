<?php

namespace Tale\Config\Format;

use Tale\Config\FormatInterface;

class Php implements FormatInterface
{

    public static function getExtensions()
    {

        return ['.php', '.inc'];
    }

    public static function load($path)
    {

        return include($path);
    }
}