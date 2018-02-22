<?php

namespace WouterDeSchuyter\WhenLol\Application\Http\Handlers;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use GDText\Box;
use GDText\Color;
use Slim\Http\Request;
use Slim\Http\Response;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItem;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemId;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;

class ImageHandler
{
    private const IMAGE = APP_DIR . '/resources/assets/images/when-template.jpg';
    private const FONT = APP_DIR . '/resources/assets/fonts/ComicSansMSBold.ttf';
    private const WIDTH = 640;
    private const HEIGHT = 480;

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
     * @param string $text
     * @return Response
     */
    public function __invoke(Request $request, Response $response, string $text): Response
    {
        $galleryItem = $this->botCheck($request, $text);
        if ($galleryItem) {
            return $response->withRedirect('/gallery/' . $galleryItem->getId());
        }

        $img = $this->loadJpgFromFile(self::IMAGE);

        if (!empty($text)) {
            $box = new Box($img);
            $box->setFontFace(self::FONT);
            $box->setFontColor(new Color(50, 50, 50));
            $box->setFontSize(40);
            $box->setBox(10, 380, self::WIDTH - 20, 100);
            $box->setLineHeight(1.1);
            $box->setTextAlign('center', 'center');
            $box->draw(substr($text, 0, 56));
        }

        // Generate image & cache output to variable
        ob_start();
        imagejpeg($img);
        $image = ob_get_contents();
        ob_end_clean();

        // Destroy image resource
        imagedestroy($img);

        // Write output
        $response->getBody()->write($image);

        // Render with correct content type
        return $response->withHeader('Content-Type', 'image/jpeg');
    }

    /**
     * @param string $path
     * @return resource
     */
    private function loadJpgFromFile(string $path)
    {
        $img = @imagecreatefromjpeg($path);

        if (!$img) {
            $img = imagecreatetruecolor(150, 30);
            $bgc = imagecolorallocate($img, 255, 255, 255);
            $tc = imagecolorallocate($img, 0, 0, 0);

            imagefilledrectangle($img, 0, 0, 150, 30, $bgc);
            imagestring($img, 2, 10, 10, 'Error loading ' . $path, $tc);

            return $img;
        }

        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        $color = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, self::WIDTH, self::HEIGHT, $color);

        $imageWidth = 375;
        imagecopyresampled(
            $canvas,
            $img,
            (self::WIDTH - $imageWidth) * 0.5,
            10,
            0,
            0,
            $imageWidth,
            $imageWidth,
            700,
            700
        );

        return $canvas;
    }

    /**
     * @param string $text
     * @param Request $request
     * @return GalleryItem|null
     */
    private function botCheck(Request $request, string $text): ?GalleryItem
    {
        if (!empty($request->getParam('direct'))) {
            return null;
        }

        $ua = $request->getHeaderLine('User-Agent');

        if (stripos($ua, 'Twitterbot') !== false || stripos($ua, 'Slackbot') !== false) {
            $galleryItem = $this->galleryItemRepository->findByText($text);

            if ($galleryItem) {
                return $galleryItem;
            }

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
                return null;
            }

            return $galleryItem;
        }

        return null;
    }
}
