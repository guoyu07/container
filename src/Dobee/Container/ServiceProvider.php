<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/9
 * Time: 上午11:25
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Container;

/**
 * Class ServiceProvider
 *
 * @package Dobee\Container
 */
class ServiceProvider implements ProviderInterface
{
    /**
     * @var array
     */
    private $services = array();

    /**
     * @var array
     */
    private $alias = array();

    /**
     * @param array $services
     */
    public function __construct(array $services = array())
    {
        foreach ($services as $name => $service) {
            $this->setService($name, $service);
        }
    }

    /**
     * @param $name
     * @param $service
     * @return $this
     */
    public function setService($name, $service)
    {
        $service = is_object($service) ? get_class($service) : $service;

        $serviceName = (false !== ($pos = strpos($service, '::'))) ? substr($service, 0, $pos) : $service;

        $this->services[$serviceName] = $service;

        $this->alias[$name] = $serviceName;

        unset($name, $service, $serviceName);

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasService($name)
    {
        return isset($this->alias[$name]) || isset($this->services[$name]);
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function getAlias($name)
    {
        if (isset($this->alias[$name])) {
            return $name;
        }

        if (!$this->getServiceName($name)) {
            return false;
        }

        return array_search($name, $this->alias);
    }

    /**
     * @param $name
     * @return bool
     */
    public function getServiceName($name)
    {
        if (isset($this->alias[$name])) {
            return $this->alias[$name];
        }

        if (isset($this->services[$name])) {
            return $name;
        }

        return false;
    }

    /**
     * @param       $name
     * @param array $arguments
     * @param bool  $flag
     * @return bool
     */
    public function getService($name, array $arguments = array(), $flag = false)
    {
        if (!($name = $this->getServiceName($name))) {
            return false;
        }

        if (!is_object($this->services[$name]) || $flag) {
            $this->services[$name] = $this->newInstance($this->services[$name], $arguments);
        }

        return $this->services[$name];
    }

    /**
     * @param       $service
     * @param array $arguments
     * @return mixed|object
     */
    public function newInstance($service, array $arguments = array())
    {
        return ServiceGenerator::createService($service, $arguments);
    }

    /**
     * @param       $service
     * @param       $method
     * @param array $arguments
     * @return mixed
     */
    public function callServiceMethod($service, $method, array $arguments = array())
    {
        return ServiceGenerator::callServiceCallback($service, $method, $arguments);
    }
}