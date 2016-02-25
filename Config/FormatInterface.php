<?php

namespace Tale\Config;

interface FormatInterface
{

    public static function getExtensions();
    public static function load($path);
    //TODO: public static function save($path, array $options)
}