<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Slim\Http\Request;
use Slim\Http\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;
use WouterDeSchuyter\WhenLol\Infrastructure\View\Twig;

class GalleryHandler
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
     * @return Response
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $ua = $request->getHeaderLine('User-Agent');

        return $this->renderer->renderWithResponse($response, 'gallery.html.twig', [
            'title' => 'Gallery - ' . getenv('APP_NAME'),
            'items' => $this->galleryItemRepository->findAll(),
            'bot' => stripos($ua, 'TelegramBot') !== false || stripos($ua, 'Twitterbot') !== false || stripos($ua, 'Slackbot') !== false,
        ]);
    }
}
