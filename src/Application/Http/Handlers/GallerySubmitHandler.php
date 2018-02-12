<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Slim\Http\Request;
use Slim\Http\Response;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItem;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;

class GallerySubmitHandler
{
    /**
     * @var GalleryItemRepository
     */
    private $galleryItemRepository;

    /**
     * @param GalleryItemRepository $galleryItemRepository
     */
    public function __construct(GalleryItemRepository $galleryItemRepository)
    {
        $this->galleryItemRepository = $galleryItemRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $text = $request->getParam('text');

        if (!empty($text)) {
            $galleryItem = new GalleryItem(
                $text,
                $request->getServerParam('REMOTE_ADDR'),
                $request->getHeaderLine('USER_AGENT')
            );

            $this->galleryItemRepository->add($galleryItem);
        }

        return $response->withRedirect('/gallery');
    }
}
