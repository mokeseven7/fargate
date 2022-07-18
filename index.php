<?php


use React\Socket\SocketServer;
use React\Http\Message\Response;


require "vendor/autoload.php";

$error_strings = [
    'invalid' => [
        'reason' => 'Invalid JSON data given',
        'status' => Response::STATUS_UNSUPPORTED_MEDIA_TYPE
    ],
    'missing' => [
        'reason' => 'JSON data does not contain a string "name" property',
        'status' => Response::STATUS_BAD_REQUEST
    ],
    'content_type' => [
        'reason' => 'Sorry, this endpiont only supports application/json media type',
        'status' => Response::STATUS_UNPROCESSABLE_ENTITY
    ],
];

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) use ($error_strings) {


    //TODO: Return plaintext fallback in content negotiation middleware
    if (!in_array('application/json', array_values($request->getHeader('Content-Type')))) {
        $error = $error_strings['content_type'];
    }

    //Check if there request body
    $input = json_decode($request->getBody()->getContents());

    //Check to see if we got an error when trying to parse the json 
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = $error_strings['invalid'];
    }

    //Our pretend endpoint requires a "name" endpiont in the request
    if (!isset($input->name) || !is_string($input->name)) {
        $error = $error_strings['missing'];
    }

    $user = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'user.json'), true);

    if (!empty($error)) {
        return Response::json(['error' => $error['reason']])->withStatus($error['status'])->withHeader('Content-Type', 'application/json');
    }

    return Response::json($user)->withStatus(200);
});

$socket = new SocketServer('0.0.0.0:80');

$http->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;
