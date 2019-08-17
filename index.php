<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

# Carrega a API
require './bootstrap.php';

/**
 * /api/users       GET     Busca todos os usuários.
 * /api/registry    POST    Registra um novo usuário.
 * /api/user/<:id>  PUT     UPDATE usuario.
 * /api/login       POST    Login do usuário.
 * /api/logout      GET     Logout do usuário.
 */

 /**
 * / somente para retornar versão do app
 */
$app->get('/', function (Request $request, Response $response) use ($app) {
    $return = $response->withJson([
        'status' => 'success',
        'message' => 'GE API Users',
        'version' => 'v1.0'
    ], 200)->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/users
 */
$app->get('/api/users', function (Request $request, Response $response, $args) {
    $return = $response->withJson(
        ['status' => 'success', 'message' => 'pong'], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/registry
 */
$app->post('/api/registry', function (Request $request, Response $response, $args) {
    $return = $response->withJson(
        ['status' => 'success', 'message' => 'pong'], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * 
 */
$app->put('/api/user/{user_id}', function (Request $request, Response $response) use ($app) {
    $route = $request->getAttribute('route');
    $user_id = $route->getArgument('user_id');
    $return = $response->withJson(
        ['status' => 'success', 'message' => "User ID: $user_id"], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * /api/login
 */
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $return = $response->withJson(
        ['status' => 'success', 'message' => 'pong'], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/logout TODO
 */
$app->get('/api/logout', function (Request $request, Response $response) use ($app) {
    $return = $response->withJson(
        ['status' => 'success', 'message' => 'pong'], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

function generate_password($password) {
    $hash_passwd = hash("sha256", $password);
    return $hash_passwd;
};

$app->run();