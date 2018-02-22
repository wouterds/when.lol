<?php

namespace WouterDeSchuyter\WhenLol\Application\Gallery;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItem;
use WouterDeSchuyter\WhenLol\Domain\Gallery\GalleryItemId;
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
        $query = $this->connection->createQueryBuilder();
        $query->insert(self::TABLE);
        $query->setValue('id', $query->createNamedParameter($galleryItem->getId()));
        $query->setValue('text', $query->createNamedParameter($galleryItem->getText()));
        $query->setValue('author_ip', $query->createNamedParameter($galleryItem->getAuthorIp()));
        $query->setValue('author_user_agent', $query->createNamedParameter($galleryItem->getAuthorUserAgent()));
        $query->setValue('created_at', 'NOW()');
        $query->execute();
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

    /**
     * @param GalleryItemId $galleryItemId
     * @return GalleryItem|null
     */
    public function findById(GalleryItemId $galleryItemId): ?GalleryItem
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('*');
        $query->from(self::TABLE);
        $query->where('id = ' . $query->createNamedParameter($galleryItemId));
        $data = $query->execute()->fetch();

        if (empty($data)) {
            return null;
        }

        return GalleryItem::fromArray($data);
    }

    /**
     * @param string $text
     * @return GalleryItem|null
     */
    public function findByText(string $text): ?GalleryItem
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('*');
        $query->from(self::TABLE);
        $query->where('text = ' . $query->createNamedParameter(strtolower($text)));
        $data = $query->execute()->fetch();

        if (empty($data)) {
            return null;
        }

        return GalleryItem::fromArray($data);
    }
}
