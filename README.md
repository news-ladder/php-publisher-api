# NewsLadder Library for the Publisher API

This repository contains the NewsLadder library, which provides functionality for handling transactions and HTTP requests within the NewsLadder system and the system of a publisher.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [NewsLadderTransaction](#newsladdertransaction)
  - [NewsLadderRequest](#newsladderrequest)
  - [NewsLadderOrigins](#newsladderorigins)
- [License](#license)

## Installation

To install the NewsLadder library, you can use Composer. Add the following to your `composer.json` file:

```json
{
    "require": {
        "newsladder": "dev-master"
    }
}
```

Then run:

```bash
composer install
```


## Usage

### NewsLadderTransaction

The NewsLadderTransaction class represents a transaction in the NewsLadder system.

**Example**
```php
<?php
use Newsladder\lib\NewsLadderTransaction;

$transaction = new NewsLadderTransaction(
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

### NewsLadderRequest

The NewsLadderRequest class handles HTTP requests for the NewsLadder system.

**Example**

```php
<?php
use Newsladder\lib\NewsLadderRequest;

$request = new NewsLadderRequest('https://example.com/api', ['param' => 'value']);
$response = $request->send($request->url, $request->payload);
print_r($response);
```

### NewsLadderOrigins

The NewsLadderOrigins class contains constants for allowed origins and service URLs in the NewsLadder system.

**Example**

```php
<?php
use Newsladder\lib\NewsLadderOrigins;

$allowedOrigins = NewsLadderOrigins::ALLOWED;
$tankUrl = NewsLadderOrigins::TANK;
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.