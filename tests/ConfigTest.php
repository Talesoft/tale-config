<?php

namespace Tale\Test;



use Tale\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testXmlConfig()
    {

        $this->assertEquals([
            'db'     => [
                'host'     => 'localhost',
                'password' => '12345',
            ],
            'models' => 'c',
            'a'      => [
                'b' => [
                    'c' => [
                        'd' => 'ABCD',
                    ],
                ],
            ],
        ], Config::load(__DIR__.'/config/common.xml'));
    }
}