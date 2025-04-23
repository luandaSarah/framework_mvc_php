<?php

namespace App\Core;


/** 
 * le but de cette classe est de renvoyer une reponse au navigateur
 *
 */
class Response
{
    public function __construct(
        private string $content = "",
        private int $status = 200,
        private array $headers = [],
    ) {}



    public function send(): void
    {

        http_response_code($this->status); //renvoie un code http en reponse 

        /**
         * On à ca : 
         * [
         * 'Content-Type => 'text/html; charset=UTF-8',
         * 'Location' => '/login',
         * ]
         * 
         * 
         */

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
            // on le transforme en ca 'Locaction : /login'
        }

        echo $this->content;
    }
}
