<?php

use NewsLadder\PublisherAPI\Request;

it('can send a GET request and retrieve data', function () {
    $method = 'GET';
    $url = 'https://jsonplaceholder.typicode.com/posts/1';
    $payload = []; // No payload for GET requests

    $request = new Request();
    $response = $request->send($method, $url, $payload);
    
    // Decode the response as JSON
    $data = json_decode($response, true);
    

    // Assert the response contains expected keys and values
    expect($data)->toHaveKeys(['userId', "id", "title", "body"]);
    expect($data['title'])->not()->toBeEmpty();
});

it('can send a POST request and create a resource', function () {
    $method = 'POST';
    $url = 'https://jsonplaceholder.typicode.com/posts';
    
    // Create a payload object
    $payload = new stdClass();
    $payload->title = 'foo';
    $payload->body = 'bar';
    $payload->userId = 1;
    
    // Encode the payload as JSON
    $request = new Request();
    $response = $request->send($method, $url, $payload);

    // Decode the response as JSON
    $data = json_decode($response, true);
    
    // Assert the response contains the sent payload
    expect($data)->toHaveKeys(['id', 'title', 'body', 'userId']);
    expect($data['title'])->toBe('foo');
    expect($data['body'])->toBe('bar');
    expect($data['userId'])->toBe(1);
});
