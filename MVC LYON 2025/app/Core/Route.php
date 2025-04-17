<?php

namespace App\Core;

#[\Attribute(\Attribute::TARGET_METHOD)] 
//on defini un attribut qui correspond à la classe Route 
// et qui n'est seulement accessible que dans des methode => Attribute::TARGET_METHOD
// Pour y accedder #[Route($name, $url, $methods)]
//Une classe attribut ne s'instancie pas mais s'applique à une methode 
//elle indique juste qu'une methode est lié à une route de l'application

class Route
{
    private ?string $controller = null;
    private ?string $action = null;

    public function __construct(
        private ?string $name = null,
        private ?string $url = null,
        private array $methods = ['GET'],
    ) {

    }

    /**
     * Get the value of controller
     *
     * @return ?string
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    /**
     * Set the value of controller
     *
     * @param ?string $controller
     *
     * @return self
     */
    public function setController(?string $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get the value of action
     *
     * @return ?string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @param ?string $action
     *
     * @return self
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of url
     *
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @param ?string $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of method
     *
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Set the value of method
     *
     * @param array $method
     *
     * @return self
     */
    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }
}