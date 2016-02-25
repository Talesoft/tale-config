<?php

use Tale\Config\FormatInterface;

class Json implements FormatInterface
{

    public static function load($path)
    {

        return json_decode(file_get_contents($path), true);
    }
}