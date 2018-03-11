<?php


namespace App\DataTransferObject;

use JMS\Serializer\Annotation as Serializer;


/**
 * Class Category
 * @package App\DataTransferObject
 *
 * @Serializer\ExclusionPolicy("none")
 * @Serializer\AccessType("public_method")
 */
class Category
{
    public const DIRECTION_TYPE_CREDIT = 'credit';
    public const DERECTION_TYPE_DEBIT = 'debit';

    /**
     * @Serializer\Expose()
     *
     * @var int
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $icon;

    /**
     * @Serializer\Expose()
     * @Serializer\Type("string")
     *
     * @var string
     */
    private $direction;

    /**
     * @Serializer\Expose()
     * @Serializer\ReadOnly()
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @Serializer\Expose()
     * @Serializer\ReadOnly()
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Category constructor.
     * @param int $id
     * @param string $name
     * @param string $icon
     * @param string $direction
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     */
    public function __construct(
        int $id,
        string $name,
        string $icon,
        string $direction,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->direction = $direction;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Category
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): Category
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $direction
     * @return $this
     */
    public function setDirection(string $direction): Category
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @return null|\DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
