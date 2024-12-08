<?php

namespace NewsLadder\PublisherAPI;

class Transaction {

    /**
     * @var string The requred key for the transaction.
     */
    private $requiredKeys = [
        'domain', 
        'url', 
        'name', 
        'articleEID', 
        'magazineKey', 
        'checkoutToken'
    ];

    /**
     * @var string The payload of the transaction.
     */
    private $payload;

    /**
     * NewsLadderTransaction constructor.
     * 
     * @param string $payload ['domain', 'url', 'name', 'articleEID', 'magazineKey', 'checkoutToken']
     */
    public function __construct(array $payload) {
        if (empty($payload)) {
            throw new \InvalidArgumentException("Payload is empty");
        }

        if (count($this->requiredKeys) < count($payload)) {
            throw new \InvalidArgumentException("Too many keys in payload");
        }

        foreach ($this->requiredKeys as $key) {
            if (!array_key_exists($key, $payload)) {
                throw new \InvalidArgumentException("Missing required key: $key");
            }
        }

        $this->payload = $payload;
    }

    /**
     * Magic getter method.
     * 
     * @param string $key of the payload.
     * @return mixed The value from the key or null if it does not exist.
     */
    public function __get($key) {
        if (array_key_exists($key, $this->payload)) {
            return $this->payload[$key];
        }
        return null;
    }

    /**
     * Verifies the transaction.
     * 
     * @return NewsLadderRequest The response from the verification request.
     */
    public function verify() {
        $config = new Config();
        $url = sprintf("%s/transaction/verify", $config->get("origins", "api_url"));
        $response = new NewsLadderRequest($url, $this->payload);
        $response->send($response->url, $response->payload);
        return $response;
    }
}
