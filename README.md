
# News Ladder Library for the Publisher API

The **News Ladder library** is designed to streamline handling transactions and HTTP requests for 
integration between the News Ladder system and publisher systems. 
It provides a straightforward way to manage and verify transactions, send requests, and handle origin configurations for allowed services.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Transaction](#transaction)
  - [Request](#request)
  - [Origins](#origins)
- [License](#license)

---

## Overview

The News Ladder library simplifies integration between News Ladder REST APIs and publisher eco system. 
It includes classes for managing transactions, sending HTTP requests, and defining origin configurations. 
With clear methods and examples, it aims to speed up development and ensure robust operations.

---

## Features

- **Transaction Handling:** Verify and manage transactions with ease.
- **HTTP Request Management:** A utility class to send HTTP requests with payloads.
- **Origin Configuration:** Predefined constants for allowed origins and service URLs.
- **MIT Licensed:** Open-source and free to use.

---

## Installation

The News Ladder library is available via Composer. Install it by running:

```bash
composer require news-ladder/publisher-api
```

Alternatively, update your `composer.json`:

```json
{
    "require": {
        "news-ladder/publisher-api": "dev-main"
    }
}
```

Then, execute:

```bash
composer install
```

**Dependencies:**  
Ensure you are using **PHP 7.4+** with Composer installed.

---

## Usage

### Transaction

The `Transaction` class represents a transaction in the News Ladder system. Use it to verify and manage transactions.

**Example:**

This object ist a transaction object.
This key values are necessary for the `Transaction HTTP Request`.

```json
{
    "domain":           "The domain from the 'become publisher' process",
    "magazineKey":      "The magazine key from the 'become publisher' process",
    "name":             "The name of the article, e.g., article title",
    "url":              "The URL of the article",
    "articleEID":       "The external page ID; can be a string or any identifier related to the article",
    "checkoutToken":    "The token from the URL's GET parameter"
}
```


```php
<?php
require 'vendor/autoload.php';

use NewsLadder\PublisherAPI\Transaction;

$payload = [
    'domain' => "example.com",
    'url' => "https://example.com/article",
    'name' => "Example Magazine",
    'articleEID' => "12345",
    'magazineKey' => "MAG123",
    'checkoutToken' => "TOKEN123",
];

$transaction = new Transaction($payload);

$response = $transaction->verify();
print_r($response);
```

---

### Request

The `Request` class is used to send HTTP requests within the News Ladder system.

**Example:**

```php
<?php
require 'vendor/autoload.php';

use NewsLadder\PublisherAPI\Request;

$request = new Request('https://example.com/api', ['param' => 'value']);
$response = $request->send($request->url, $request->payload);
print_r($response);
```

---

**Coding Standards:**
- Follow PSR-12 coding standards.
- Write clear and concise code comments.
- Include tests for new features.

**Feedback:**
Feel free to report issues or suggest improvements in the [issues section](#).

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for more details.
