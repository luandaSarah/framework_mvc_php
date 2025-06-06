<?php

namespace App\Models;

class User extends Model
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?string $email = null,
        protected ?string $password = null,
        protected ?\DateTime $createdAt = null,
        protected ?array $roles = null,
    ) {
        $this->table = "users";
    }

    public function connectUser(): self
    {
        // On stocke l'utilisateur dans la session
        $_SESSION['USER'] = [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'roles' => $this->roles,
        ];

        return $this;
    }

    public function findOneByEmail(string $email): bool|self
    {
        $pdoStatement = $this
            ->runQuery("SELECT * FROM $this->table WHERE email = :email", ['email' => $email])
            ->fetch();

        return $this->fetchHydrate($pdoStatement);
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
     * Get the value of firstName
     *
     * @return ?string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @param ?string $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     *
     * @return ?string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @param ?string $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return "$this->firstName $this->lastName";
    }
    /**
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return ?string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param ?string $password
     *
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

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
     * Get the value of roles
     *
     * @return ?array
     */
    public function getRoles(): ?array
    {
        // Garantit que le tableau de roles ait au moins le role USER
        $this->roles[] = "ROLE_USER";

        return array_unique($this->roles);
    }

    /**
     * Set the value of roles
     *
     * @param null|array|string $roles
     *
     * @return self
     */
    public function setRoles(null|array|string $roles): self
    {
        if (is_string($roles)) {
            $this->roles = json_decode($roles ?? '[]');
        } else {
            $this->roles = $roles;
        }

        return $this;
    }
}
