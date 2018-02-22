<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Slim\Http\Request;
use Slim\Http\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemId;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;
use WouterDeSchuyter\WhenLol\Infrastructure\View\Twig;

class GalleryItemHandler
{
    /**
     * @var Twig
     */
    private $renderer;

    /**
     * @var GalleryItemRepository
     */
    private $galleryItemRepository;

    /**
     * @param Twig $twig
     * @param GalleryItemRepository $galleryItemRepository
     */
    public function __construct(Twig $twig, GalleryItemRepository $galleryItemRepository)
    {
        $this->renderer = $twig;
        $this->galleryItemRepository = $galleryItemRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $id
     * @return Response
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function __invoke(Request $request, Response $response, string $id): Response
    {
        $ua = $request->getHeaderLine('User-Agent');

        return $this->renderer->renderWithResponse($response, 'gallery-item.html.twig', [
            'title' => getenv('APP_NAME'),
            'url' => getenv('APP_URL'),
            'item' => $this->galleryItemRepository->findById(new GalleryItemId($id)),
            'bot' => stripos($ua, 'TelegramBot') !== false || stripos($ua, 'Twitterbot') !== false || stripos($ua, 'Slackbot') !== false,
        ]);
    }
}
