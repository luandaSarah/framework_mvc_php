<?php

namespace App\Models;

class Categorie extends Model
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $name = null,
        protected ?string $description = null,
        protected ?bool $enabled = null,
        protected ?\DateTime $createdAt = null,
    ) {
        $this->table = "categories";
    }
    
 /**
     * Get the value of id
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param ?int $id
     *
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

        /**
     * Get the value of description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return ?\DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param ?\DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of enabled
     *
     * @return ?bool
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Set the value of enabled
     *
     * @param ?bool $enabled
     *
     * @return self
     */
    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }



}
