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

    /**
     * @param GalleryItemId $id
     * @return GalleryItem|null
     */
    public function findById(GalleryItemId $id): ?GalleryItem;

    /**
     * @param string $text
     * @return GalleryItem|null
     */
    public function findByText(string $text): ?GalleryItem;
}
