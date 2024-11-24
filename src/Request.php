<?php

namespace NewsLadder\PublisherAPI;

/** 
 * Class Request
 * 
 * Handles HTTP requests for the NewsLadder system.
 */

class Request {
    /**
     * @var string The URL for the request.
     */
    private $url;

    /**
     * @var array The method for the request.
     */
    private $method;

    /**
     * @var array The payload for the request.
     */
    private $payload;

    /**
     * @var array The content type for the request.
     */
    private $content_type;

    /**
     * @var string The response from the request.
     */
    private $response;

    /**
     * @var bool Indicates if there was an error with the request.
     */
    private $error;

    /**
     * @var array The HTTP response headers.
     */
    private $http_response_header;

    /**
     * @var string The HTTP version from the response.
     */
    private $version;

    /**
     * @var int The HTTP status code from the response.
     */
    private $status_code;

    /**
     * @var string The HTTP status message from the response.
     */
    private $message;
    
    /**
     * @var string The HTTP headers from the response.
     */
     private $headers;

    /**
     * @var string The HTTP header size from the response.
     */
    private $header_size;
    
     /**
     * NewsLadderRequest constructor.
     * 
     * @param string $url The URL for the request.
     * @param array $payload The payload for the request.
     */
    public function __construct() {
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
     * Sends the HTTP request.
     * 
     * @param string $method The Method for the request.
     * @param string $url The URL for the request.
     * @param array $payload The payload for the request.
     * @return mixed The response from the request.
     * @throws \Exception If neither cURL nor allow_url_fopen is enabled.
     */
    public function send(
            $method, 
            $url, 
            $payload,
            $content_type = "application/json"
            ) {
        $this->method = $method;
        $this->url = $url;
        $this->payload = $payload;
        $this->content_type = $content_type;

        if (function_exists('curl_init')) {
            return $this->sendCURL(
                $method, 
                $url, 
                $payload, 
                $content_type);
        }

        throw new \Exception(
            'CURL is deactivated on the system and allow_url_fopen is deactivated in php.ini. ' . 
            'To resolve this issue, either enable CURL in the PHP configuration or ensure ' .
            'that allow_url_fopen is set to "On" in the php.ini file.'
        );
    }

    /**
     * Parses HTTP response headers to extract version, status code, and message.
     * 
     * @param string $headers The HTTP response headers.
     */
    private function parseResponseHeader($headers) {
        // Split headers into lines
        $headerLines = explode("\r\n", $headers);

        // The first line contains the HTTP version, status code, and status message
        if (!empty($headerLines[0])) {
            list($this->version, $this->status_code, $this->message) = explode(' ', $headerLines[0], 3);
        } else {
            // Set default values if parsing fails
            $this->version = "Unknown";
            $this->status_code = "Unknown";
            $this->message = "No headers received";
        }
    }

    /**
     * Sends the HTTP request using cURL.
     * 
     * @param string $method The Method for the request.
     * @param string $url The URL for the request.
     * @param array $payload The payload for the request.
     * @return mixed The response from the request.
     */
    private function sendCURL(
            $method, 
            $url, 
            $payload, 
            $content_type = "application/json"
            ) {

        $this->method = $method;
        $this->url = $url;
        $this->payload = $payload;
        $this->content_type = $content_type;
        
        if ($content_type === "application/json") {
            $this->payload = json_encode($this->payload);
        } else {
            $this->payload = http_build_query($this->payload);
        }
        
        $content_type = 'Content-Type: ' . $content_type;


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->payload,
            CURLOPT_HTTPHEADER => array(
                $content_type
            ),  
        ));

        $this->response = curl_exec($curl);
        
        if ($this->response === false) {
            $this->error = true;
        } else {
            // Extract the headers and body from the response
            $this->header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $this->headers = substr($this->response, 0, $this->header_size); // Get headers

            // Extract status code, version, and message from the headers
            $this->parseResponseHeader($this->headers);

            // Get the status code using cURL's built-in function
            $this->status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the HTTP status code
        
        }
        curl_close($curl);
        
        return $this->response;
    }
    
}