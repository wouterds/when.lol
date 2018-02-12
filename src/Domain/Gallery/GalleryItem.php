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