<?php

namespace WouterDeSchuyter\WhenLol\Infrastructure\ValueObjects;

use JsonSerializable;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid as BaseUuid;

abstract class AbstractUuid implements JsonSerializable
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string|null $value
     * @throws InvalidUuidStringException
     */
    public function __construct(string $value = null)
    {
        if (!empty($value)) {
            $this->isValid($value);
        }

        if (empty($value)) {
            $value = BaseUuid::uuid4()->toString();
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return bool
     * @throws InvalidUuidStringException
     */
    public function isValid(string $value): bool
    {
        if (!BaseUuid::isValid($value)) {
            throw new InvalidUuidStringException();
        }

        return true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
