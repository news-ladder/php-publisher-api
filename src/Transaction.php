<?php

namespace NewsLadder\PublisherSDK;

class Transaction {

    /**
     * @var string The required keys for the transaction.
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

        $payload = array_intersect_key($payload, array_flip($this->requiredKeys));

        foreach ($this->requiredKeys as $key) {
            if (!array_key_exists($key, $payload) || !isset($payload[$key]) || empty($payload[$key])) {
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
     * @return Request The response from the verification request.
     */
    public function verify() {
        $config = new Config();
        $url = sprintf("%s/transaction/verify", $config->get("origins", "api_url"));
        $response = new Request($url, $this->payload);
        $response->send($response->url, $response->payload);

        // Handle HTTP response codes
        $httpStatusCode = $response->statusCode();
        $responseBody = $response->message();

        switch ($httpStatusCode) {
            case 200:
                return $response; // Success case
            case 400:
                throw new \Exception("Bad Request: Invalid payload. Details: " . $responseBody);
            case 401:
                throw new \Exception("Unauthorized: Invalid token or credentials. Details: " . $responseBody);
            case 404:
                throw new \Exception("Not Found: The requested resource does not exist. Details: " . $responseBody);
            case 500:
                throw new \Exception("Internal Server Error: An unexpected error occurred. Please try again later.");
            default:
                throw new \Exception("Unexpected Error (HTTP $httpStatusCode): " . $responseBody);
        }
    }
}
