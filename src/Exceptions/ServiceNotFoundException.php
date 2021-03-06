<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Container\Exceptions;

/**
 * Class ServiceNotFoundException
 *
 * @package FastD\Container\Exceptions
 */
class ServiceNotFoundException extends ContainerException
{
    /**
     * ServiceNotFoundException constructor.
     *
     * @param string $service
     */
    public function __construct($service)
    {
        parent::__construct(sprintf('Service "%s" not found', $service));
    }
}