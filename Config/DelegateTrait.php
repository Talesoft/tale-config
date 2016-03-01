<?php

namespace Tale\Config;

use Tale\Config;
use Tale\ConfigurableInterface;

/**
 * Class DelegateTrait
 *
 * @package Tale\Config
 */
trait DelegateTrait
{

    protected $optionNameSpace = '';

    /**
     * @return array
     */
    public function getOptions()
    {

        if (empty($this->optionNameSpace))
            return $this->getTargetConfigurableObject()
                        ->getOptions();

        return $this->getTargetConfigurableObject()
                    ->getOption($this->optionNameSpace);
    }

    /**
     * @param array $options
     * @param bool  $recursive
     * @param bool  $reverse
     *
     * @return $this
     */
    public function mergeOptions(array $options, $recursive = false, $reverse = false)
    {

        if (!empty($this->optionNameSpace)) {

            $o = [];
            Config::set($this->optionNameSpace, $options, $o);
            $options = $o;
        }

        $this->getTargetConfigurableObject()
            ->mergeOptions($options, $recursive, $reverse);

        return $this;
    }

    /**
     * @param array $options
     * @param bool  $recursive
     *
     * @return $this
     */
    public function setOptions(array $options, $recursive = false)
    {

        return $this->mergeOptions($options, $recursive);
    }

    /**
     * @param array $options
     * @param bool  $recursive
     *
     * @return $this
     */
    public function setDefaults(array $options, $recursive = false)
    {

        return $this->mergeOptions($options, $recursive, true);
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {

        return $this->getTargetConfigurableObject()->getOption(
            $this->getOptionName($name),
            $default
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {

        $this->getTargetConfigurableObject()
            ->setOption($this->getOptionName($key), $value);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getOptionName($name)
    {
        
        if (empty($this->optionNameSpace))
            return $name;

        return $this->optionNameSpace.'.'.$name;
    }

    /**
     * @return ConfigurableInterface
     */
    abstract protected function getTargetConfigurableObject();
}