<?php

namespace WouterDeSchuyter\WhenLol\Domain\Gallery;

use DateTime;
use JsonSerializable;

class GalleryItem implements JsonSerializable
{
    /**
     * @var GalleryItemId
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $authorIp;

    /**
     * @var string
     */
    private $authorUserAgent;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime|null
     */
    private $updatedAt;

    /**
     * @param string $text
     * @param string $authorIp
     * @param string $authorUserAgent
     */
    public function __construct(string $text, string $authorIp, string $authorUserAgent)
    {
        $this->id = new GalleryItemId();
        $this->text = $text;
        $this->authorIp = $authorIp;
        $this->authorUserAgent = $authorUserAgent;
        $this->createdAt = new DateTime('now');
        $this->updatedAt = null;
    }

    /**
     * @param array $data
     * @return GalleryItem
     */
    public static function fromArray(array $data): self
    {
        $galleryItem = new GalleryItem($data['text'], $data['author_ip'], $data['author_user_agent']);
        $galleryItem->id = new GalleryItemId(!empty($data['id']) ? $data['id'] : null);
        $galleryItem->createdAt = !empty($data['created_at']) ? new DateTime($data['created_at']) : null;
        $galleryItem->updatedAt = !empty($data['updated_at']) ? new DateTime($data['updated_at']) : null;

        return $galleryItem;
    }

    /**
     * @return GalleryItemId
     */
    public function getId(): GalleryItemId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return getenv('APP_URL') . '/' . urlencode($this->text) . '.jpg';
    }

    /**
     * @return string
     */
    public function getAuthorIp(): string
    {
        return $this->authorIp;
    }

    /**
     * @return string
     */
    public function getAuthorUserAgent(): string
    {
        return $this->authorUserAgent;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
