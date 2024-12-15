<?php

use NewsLadder\PublisherSDK\Config;

beforeEach(function () {

    $this->current_default_config_file_path = __DIR__ . '/../../src/config.ini';
    $this->current_default_config_file_path_tmp = __DIR__ . '/../../src/config.ini.temp';

    if (file_exists($this->current_default_config_file_path)) {
        if (!rename($this->current_default_config_file_path, $this->current_default_config_file_path_tmp)) {
            throw new Exception("Failed to rename $this->current_default_config_file_path to $this->current_default_config_file_path_tmp");
        }
    }

    // Create temporary directories and files for each test
    $this->tempDir = sys_get_temp_dir() . '/config_tests';
    if (!file_exists($this->tempDir)) {
        mkdir($this->tempDir, 0777, true);
    }

    // Default configuration file
    $this->defaultConfigPath = $this->tempDir . '/cconfig.ini';
    file_put_contents($this->defaultConfigPath, "[environment]\nscope = \"production\"");

    // Environment-specific configuration file
    $this->envConfigPath = $this->tempDir . '/myconfig.ini.production';
    file_put_contents($this->envConfigPath, "[origins]\napi_url = \"https://api.news-ladder.com/v1/\"\n");
});

afterEach(function () {
    $this->current_default_config_file_path = __DIR__ . '/../../src/config.ini';
    $this->current_default_config_file_path_tmp = __DIR__ . '/../../src/config.ini.temp';

    if (file_exists($this->current_default_config_file_path_tmp)) {
        if (!rename($this->current_default_config_file_path_tmp, $this->current_default_config_file_path)) {
            throw new Exception("Failed to rename $this->current_default_config_file_path to $this->current_default_config_file_path_tmp");
        }
    }

    // Clean up temporary files and directories after each test
    if (file_exists($this->defaultConfigPath)) {
        unlink($this->defaultConfigPath);
    }
    if (file_exists($this->envConfigPath)) {
        unlink($this->envConfigPath);
    }
    if (file_exists($this->tempDir)) {
        rmdir($this->tempDir);
    }
});

test('loads default configuration values', function () {
    // Delete the environment-specific config file to test default loading
    unlink($this->envConfigPath);

    $config = new Config();

    expect($config->get('environment', 'scope'))->toBe('production');
    expect($config->get('origins', 'api_url'))->toBe('https://api.news-ladder.com/v1/');
});

test('overrides default configuration with environment-specific config', function () {
    $config = new Config($this->defaultConfigPath);

    expect($config->get('origins', 'api_url'))->toBe('https://api.news-ladder.com/v1/');
});

test('handles missing configuration file gracefully', function () {
    unlink($this->defaultConfigPath);

    $config = new Config($this->defaultConfigPath);

    expect($config->get('environment', 'scope'))->toBe('production');
    expect($config->get('origins', 'api_url'))->toBe('https://api.news-ladder.com/v1/');
});
