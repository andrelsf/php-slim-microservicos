<?php

use App\Models\Entity\UserEntity;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\EntityManager;

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
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $users = $usersRepository->findAll();
    $return = $response->withJson(
        ['status' => 'success', 'users' => $users], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/registry
 */
$app->post('/api/registry', function (Request $request, Response $response, $args) {
    $params = (object) $request->getParams();
    $entityManager = $this->get(EntityManager::class);
    $user = (new UserEntity())->setNome($params->nome)
                    ->setEmail($params->email)
                    ->setCPF((int)$params->cpf)
                    ->setTelefone($params->telefone)
                    ->setDataNascimento(
                        DateTime::createFromFormat(
                            "Y-m-d", $params->data_nascimento
                        )
                    )
                    ->setSenha(generate_password($params->senha))
                    ->setRua($params->rua)
                    ->setNumero($params->numero)
                    ->setBairro($params->bairro)
                    ->setCidade($params->cidade)
                    ->setEstado($params->estado)
                    ->setComplemento($params->complemento);
    /**
     * Persiste no banco
     */
    try {
        $entityManager->persist($user);
        $entityManager->flush();
    } catch (Exception $e) {
        throw new \Exception("Falha ao registrar o novo usuario!", 400);
    }
    $return = $response->withJson(
        ['status' => 'success', 'message' => 'User registred!'], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * 
 */
$app->get('/api/user/{user_id}', function (Request $request, Response $response) use ($app) {
    $route = $request->getAttribute('route');
    $user_id = $route->getArgument('user_id');
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $user = $usersRepository->find($user_id);
    if (!$user) {
        throw new \Exception("User not found.", 404);
    }
    $return = $response->withJson(
        ['status' => 'success', 'user' => $user], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * /api/login
 */
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $return = $response->withJson(
        ['status' => 'success', 'user' => 'pong'], 200
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