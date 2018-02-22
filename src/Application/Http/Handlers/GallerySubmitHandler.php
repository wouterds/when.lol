<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        $text = strtolower(trim($request->getParam('text')));

        if (!empty($text)) {
            $galleryItem = new GalleryItem(
                $text,
                $request->getHeaderLine('CF-Connecting-IP') ?
                    $request->getHeaderLine('CF-Connecting-IP') :
                    $request->getServerParam('REMOTE_ADDR'),
                $request->getHeaderLine('USER_AGENT')
            );

            try {
                $this->galleryItemRepository->add($galleryItem);
            } catch (UniqueConstraintViolationException $e) {
                return $response->withRedirect('/gallery');
            }
        }

        return $response->withRedirect('/gallery');
    }
}
