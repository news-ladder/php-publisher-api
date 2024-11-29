<?php

namespace NewsLadder\PublisherAPI;

class Transaction {

    /**
     * @var string The domain of the transaction.
     */
    private $domain;

    /**
     * @var string The URL of the transaction.
     */
    private $url;

    /**
     * @var string The name associated with the transaction.
     */
    private $name;

    /**
     * @var string The external article ID.
     */
    private $external_article_id;

    /**
     * @var string The magazine key.
     */
    private $magazine_key;

    /**
     * @var string The checkout token.
     */
    private $checkout_token;

    /**
     * NewsLadderTransaction constructor.
     * 
     * @param string $domain The domain of the magazine.
     * @param string $url The URL of the article.
     * @param string $name The magazine name.
     * @param string $external_article_id The external article ID. The ID is defined by the auuid in the publishers database.
     * @param string $magazine_key The magazine key from the News Ladder plattform
     * @param string $checkout_token The checkout token from the params from the user.
     */
    public function __construct(
            $domain, 
            $url, 
            $name, 
            $external_article_id, 
            $magazine_key, 
            $checkout_token) {
        $this->domain = $domain;
        $this->url = $url;
        $this->name = $name;
        $this->external_article_id = $external_article_id;
        $this->magazine_key = $magazine_key;
        $this->checkout_token = $checkout_token;
    }

    /**
     * Magic getter method.
     * 
     * @param string $property The property name.
     * @return mixed The property value or null if it does not exist.
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * Verifies the transaction.
     * 
     * @return NewsLadderRequest The response from the verification request.
     */
    public function verify() {
        $payload = [
            'domain' => $this->domain,
            'url' => $this->url,
            'name' => $this->name,
            'articleEID' => $this->external_article_id,
            'magazineKey' => $this->magazine_key,
            'checkoutToken' => $this->checkout_token,
        ];
        $config = new Config();
        $url = sprintf("%s/transaction/verify", $config->get("origins", "api_url"));
        $response = new NewsLadderRequest($url, $payload);
        $response->send($response->url, $response->payload);
        
        return $response;
    }
}
