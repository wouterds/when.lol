<?php

namespace WouterDeSchuyter\WhenLol\Application\Http;

use Slim\App;
use WouterDeSchuyter\WhenLol\Application\Container;
use WouterDeSchuyter\WhenLol\Application\Http\Handlers\GalleryHandler;
use WouterDeSchuyter\WhenLol\Application\Http\Handlers\GalleryItemHandler;
use WouterDeSchuyter\WhenLol\Application\Http\Handlers\GallerySubmitHandler;
use WouterDeSchuyter\WhenLol\Application\Http\Handlers\ImageHandler;
use WouterDeSchuyter\WhenLol\Application\Http\Handlers\IndexHandler;

class Application extends App
{
    public function __construct()
    {
        parent::__construct(Container::load());

        $this->loadRoutes();
    }

    private function loadRoutes()
    {
        $this->get('/', IndexHandler::class)->setName('index');
        $this->get('/gallery', GalleryHandler::class)->setName('gallery');
        $this->post('/gallery/submit', GallerySubmitHandler::class)->setName('gallery.submit');
        $this->get('/gallery/{id}', GalleryItemHandler::class)->setName('gallery.item');
        $this->get('/{text}.jpg', ImageHandler::class)->setName('image');
    }
}
