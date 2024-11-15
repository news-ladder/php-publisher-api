<?php

namespace NewsLadder\PublisherAPI;

/**
 * Library configuration class
 */
class Config {

    /**
     * @var array
     */
    private $defaults = 
        [
            "environment" => [
                "scope" => "production" 
            ],
            "origins" => [
                "api_url" => "https://api.news-ladder.com/v1/" 
            ]
        ];

    /**
     * @var array
     */
    private $settings = [];

    // Constructor loads the .ini file and stores the configuration data
    public function __construct($filePath = __DIR__ . '/config.ini')
    {
        $this->settings = $this->defaults;

        if (file_exists($filePath)) {
            // Load the .ini file and store it as a multidimensional array
            $this->settings = array_merge($this->settings, parse_ini_file($filePath, true));
        }

        // Save the environment
        $environment_value = $this->get("environment", "scope");
        
        if (file_exists($filePath . "." . $environment_value)) {
            // Load the .ini file and store it as a multidimensional array
            $environment = parse_ini_file($filePath . "." . $environment_value, true);
            
            $this->settings = array_merge($this->settings, $environment);
        }
        
    }
 
    // Method to access configuration values, with an optional section parameter
    public function get($section, $key = null)
    {
        // If only a section is specified, return it as an array
        if ($key === null) {
            return $this->settings[$section] ?? null;
        }

        // Return the specific value within the section
        $value = $this->settings[$section][$key] ?? null;

        // Check if the value is a list (e.g., comma-separated) and convert it to an array
        if (is_string($value) && strpos($value, ',') !== false) {
            $value = array_map('trim', explode(',', $value)); // Convert list to array and remove whitespace
        }

        return $value;
    }
}
