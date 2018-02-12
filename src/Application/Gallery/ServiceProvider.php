<?php

namespace WouterDeSchuyter\WhenLol\Application\Gallery;

use League\Container\ServiceProvider\AbstractServiceProvider;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;

class ServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        GalleryItemRepository::class,
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->container->share(GalleryItemRepository::class, function () {
            return $this->container->get(DbalGalleryItemRepository::class);
        });
    }
}
