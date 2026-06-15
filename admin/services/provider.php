<?php

defined('_JEXEC') or die;

use BarlowsWoodyard\Component\CheckoutLog\Administrator\Extension\CheckoutLogComponent;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\BarlowsWoodyard\\Component\\CheckoutLog'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\BarlowsWoodyard\\Component\\CheckoutLog'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new CheckoutLogComponent(
                    $container->get(ComponentDispatcherFactoryInterface::class)
                );
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                return $component;
            }
        );
    }
};
