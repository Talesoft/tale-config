<?php

namespace Tale\Test;

use Tale\ConfigurableInterface;
use Tale\ConfigurableTrait;

class DbConnection implements ConfigurableInterface
{
    use ConfigurableTrait;

    public function __construct(array $options = null)
    {

        $this->defineOptions([
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'encoding' => 'utf-8'
        ], $options);
    }
}

class ConfigurableTraitTest extends \PHPUnit_Framework_TestCase
{

    public function testDefineOptions()
    {

        $db = new DbConnection(['password' => 'some password', 'host' => 'some host']);

        $this->assertEquals('some password', $db->getOption('password'));
        $this->assertEquals('some host', $db->getOption('host'));
    }
}