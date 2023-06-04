<?php

namespace ContainerGKpBONF;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_P2lktr1Service extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.p2lktr1' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.p2lktr1'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'agent' => ['privates', 'App\\Entity\\Agent', 'getAgentService', true],
        ], [
            'agent' => 'App\\Entity\\Agent',
        ]);
    }
}