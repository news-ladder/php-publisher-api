# News Ladder library for the Publisher API

This repository contains the News Ladder library, which provides functionality for handling transactions and HTTP requests within the News Ladder system and the system of a publisher.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [`Transaction`](#transaction)
  - [`Request`](#request)
  - [`Origins`](#origins)
- [License](#license)

## Installation

To install the News Ladder library, you can use Composer:

```shell
composer require news-ladder/publisher-api
```

Alternatively, you can add the following directly to your `composer.json` file:

```json
{
    "require": {
        "news-ladder/publisher-api": "dev-master"
    }
}
```

Then run:

```bash
composer install
```

## Usage

### `Transaction`

The Transaction class represents a transaction in the News Ladder system.

**Example**

```php
<?php
use NewsLadder\PublisherAPI\Transaction;

$transaction = new Transaction(
    'example.com',
    'https://example.com/article',
    'Example Article',
    '12345',
    'magazine_key',
    'checkout_token'
);

$response = $transaction->verify();
print_r($response);
```

### `Request`

The Request class handles HTTP requests for the News Ladder system.

**Example**

```php
<?php
use NewsLadder\PublisherAPI\Request;

$request = new Request('https://example.com/api', ['param' => 'value']);
$response = $request->send($request->url, $request->payload);
print_r($response);
```

### `Origins`

The Origins class contains constants for allowed origins and service URLs in the News Ladder system.

**Example**

```php
<?php
use NewsLadder\PublisherAPI\Origins;

$allowedOrigins = Origins::ALLOWED;
$tankUrl = Origins::TANK;
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for details.
