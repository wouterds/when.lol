<?php

namespace WouterDeSchuyter\WhenLol\Domain\Gallery;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

interface GalleryItemRepository
{
    /**
     * @param GalleryItem $galleryItem
     * @throws UniqueConstraintViolationException
     */
    public function add(GalleryItem $galleryItem);

    /**
     * @param string $order
     * @return GalleryItem[]
     */
    public function findAll(string $order = 'DESC'): array;
}
