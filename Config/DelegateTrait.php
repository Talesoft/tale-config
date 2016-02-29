<?php

namespace Tale\Config;

use Tale\ConfigurableInterface;

/**
 * Class DelegateTrait
 *
 * @package Tale\Config
 */
trait DelegateTrait
{

    /**
     * @return array
     */
    public function getOptions()
    {

        $nameSpace = $this->getOptionNameSpace();
        if (empty($nameSpace))
            return $this->getTargetConfigurableObject()
                        ->getOptions();

        return $this->getTargetConfigurableObject()
                    ->getOption($nameSpace);
    }

    /**
     * @param array $options
     * @param bool  $recursive
     * @param bool  $reverse
     *
     * @return $this
     */
    public function setOptions(array $options, $recursive = false, $reverse = false)
    {

        $nameSpace = $this->getOptionNameSpace();
        if (!empty($nameSpace))
            $options = [$nameSpace => $options];

        $this->getTargetConfigurableObject()
             ->setOptions($options, $recursive, $reverse);

        return $this;
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
     * @return string
     */
    protected function getOptionNameSpace()
    {

        return '';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getOptionName($name)
    {

        $nameSpace = $this->getOptionNameSpace();

        if (empty($nameSpace))
            return $name;

        return $this->getOptionNameSpace().'.'.$name;
    }

    /**
     * @return ConfigurableInterface
     */
    abstract protected function getTargetConfigurableObject();
}