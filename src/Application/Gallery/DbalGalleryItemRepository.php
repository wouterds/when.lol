<?php

namespace WouterDeSchuyter\WhenLol\Application\Gallery;

use Doctrine\DBAL\Connection;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItem;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemRepository;

class DbalGalleryItemRepository implements GalleryItemRepository
{
    public const TABLE = 'gallery_item';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param GalleryItem $galleryItem
     */
    public function add(GalleryItem $galleryItem)
    {
        $this->connection->insert(self::TABLE, [
            'id' => $galleryItem->getId(),
            'text' => $galleryItem->getText(),
            'author_ip' => $galleryItem->getAuthorIp(),
            'author_user_agent' => $galleryItem->getAuthorUserAgent(),
            'created_at' => 'NOW()',
        ]);
    }

    /**
     * @param string $order
     * @return GalleryItem[]
     */
    public function findAll(string $order = 'DESC'): array
    {
        $order = strtoupper($order);
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order =  'DESC';
        }

        $query = $this->connection->createQueryBuilder();
        $query->select('*');
        $query->from(self::TABLE);
        $query->orderBy('created_at', $order);
        $rows = $query->execute()->fetchAll();

        if (empty($rows)) {
            return [];
        }

        $data = [];
        foreach ($rows as $row) {
            $galleryItem = GalleryItem::fromArray($row);

            $data[$galleryItem->getId()->getValue()] = $galleryItem;
        }

        return $data;
    }
}
