<?php

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use HBN\Plugin\System\HbnFail2ban\Extension\HbnFail2ban;

return new class() implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $config = (array) PluginHelper::getPlugin('content', 'hbnfail2ban');
                $dispatcher = $container->get(DispatcherInterface::class);
                $app = Factory::getApplication();

                $plugin = new HbnFail2ban($dispatcher, $config);
                $plugin->setApplication($app);

                return $plugin;
            }
        );
    }
};

