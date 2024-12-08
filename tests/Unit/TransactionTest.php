<?php

use NewsLadder\PublisherAPI\Transaction;

it('constructs a transaction with a valid payload', function () {
    $payload = [
        'domain' => 'example.com',
        'url' => 'https://example.com/article',
        'name' => 'Example Magazine',
        'articleEID' => '12345',
        'magazineKey' => 'MAG123',
        'checkoutToken' => 'TOKEN123',
    ];

    $transaction = new Transaction($payload);

    expect($transaction->domain)->toBe('example.com');
    expect($transaction->url)->toBe('https://example.com/article');
    expect($transaction->name)->toBe('Example Magazine');
    expect($transaction->articleEID)->toBe('12345');
    expect($transaction->magazineKey)->toBe('MAG123');
    expect($transaction->checkoutToken)->toBe('TOKEN123');
});

it('throws an exception if the payload is empty', function () {
    $payload = [];

    new Transaction($payload);
})->throws(InvalidArgumentException::class, 'Payload is empty');

it('throws an exception if required keys are missing', function () {
    $payload = [
        'url' => 'https://example.com/article',
        'name' => 'Example Magazine',
        'articleEID' => '12345',
        'magazineKey' => 'MAG123',
        'checkoutToken' => 'TOKEN123',
    ];

    new Transaction($payload);
})->throws(InvalidArgumentException::class, 'Missing required key: domain');

it('throws an exception if there are too many keys in the payload', function () {
    $payload = [
        'domain' => 'example.com',
        'url' => 'https://example.com/article',
        'name' => 'Example Magazine',
        'articleEID' => '12345',
        'magazineKey' => 'MAG123',
        'checkoutToken' => 'TOKEN123',
        'extraKey1' => 'unexpected_value1',
        'extraKey2' => 'unexpected_value2',
    ];

    new Transaction($payload);
})->throws(InvalidArgumentException::class, 'Too many keys in payload');


it('throws an exception if an undefined key is included in the payload', function () {
    $payload = [
        'domain' => 'example.com',
        'url' => 'https://example.com/article',
        'name' => 'Example Magazine',
        'articleEID' => '12345',
        'magazineKey' => 'MAG123',
        'undefinedKey' => 'unexpected_value',
    ];

    new Transaction($payload);
})->throws(InvalidArgumentException::class, 'Missing required key: checkoutToken');

it('returns null for a nonexistent property', function () {
    $payload = [
        'domain' => 'example.com',
        'url' => 'https://example.com/article',
        'name' => 'Example Magazine',
        'articleEID' => '12345',
        'magazineKey' => 'MAG123',
        'checkoutToken' => 'TOKEN123',
    ];

    $transaction = new Transaction($payload);

    expect($transaction->nonexistentProperty)->toBeNull();
});