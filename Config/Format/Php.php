<?php

use Tale\Config\FormatInterface;

class Php implements FormatInterface
{

    public static function load($path)
    {

        return include($path);
    }
}