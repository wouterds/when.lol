<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Slim\Http\Request;
use Slim\Http\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use WouterDeSchuyter\WhenLol\Infrastructure\View\Twig;

class GalleryHandler
{
    /**
     * @var Twig
     */
    private $renderer;

    /**
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->renderer = $twig;
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
        return $this->renderer->renderWithResponse($response, 'gallery.html.twig', [
            'title' => 'Gallery - ' . getenv('APP_NAME'),
            'items' => [],
        ]);
    }
}