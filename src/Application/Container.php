<?php

namespace WouterDeSchuyter\WhenLol\Application;

use League\Container\Container as LeagueContainer;
use League\Container\ReflectionContainer;
use WouterDeSchuyter\WhenLol\Application\Gallery\ServiceProvider as GalleryServiceProvider;
use WouterDeSchuyter\WhenLol\Application\Http\ServiceProvider as HttpServiceProvider;
use WouterDeSchuyter\WhenLol\Infrastructure\Database\ServiceProvider as DatabaseServiceProvider;
use WouterDeSchuyter\WhenLol\Infrastructure\View\ServiceProvider as ViewServiceProvider;

class Container extends LeagueContainer
{
    /**
     * @return Container
     */
    public static function load()
    {
        $container = new static();
        $container->delegate(new ReflectionContainer());

        $container->addServiceProvider(GalleryServiceProvider::class);
        $container->addServiceProvider(HttpServiceProvider::class);
        $container->addServiceProvider(DatabaseServiceProvider::class);
        $container->addServiceProvider(ViewServiceProvider::class);

        return $container;
    }
}
