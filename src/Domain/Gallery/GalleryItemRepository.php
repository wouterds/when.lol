<?php

namespace WouterDeSchuyter\WhenLol\Domain\Gallery;

interface GalleryItemRepository
{
    /**
     * @param GalleryItem $galleryItem
     */
    public function add(GalleryItem $galleryItem);

    /**
     * @param string $order
     * @return GalleryItem[]
     */
    public function findAll(string $order = 'DESC'): array;
}
