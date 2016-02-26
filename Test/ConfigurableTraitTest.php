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

    public function testLoadOptions()
    {

        $db = new DbConnection();
        $db->loadOptions(__DIR__.'/config/db.yml');

        $this->assertEquals('12345', $db->getOption('password'));
        $this->assertEquals('some-host:3306', $db->getOption('host'));
        $this->assertEquals('iso-8859-1', $db->getOption('encoding'));
    }
}