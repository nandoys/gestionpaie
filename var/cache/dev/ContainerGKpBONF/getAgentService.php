<?php

namespace ContainerGKpBONF;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getAgentService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\Entity\Agent' shared autowired service.
     *
     * @return \App\Entity\Agent
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Entity'.\DIRECTORY_SEPARATOR.'Agent.php';

        return $container->privates['App\\Entity\\Agent'] = new \App\Entity\Agent();
    }
}