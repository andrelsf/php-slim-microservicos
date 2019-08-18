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
 * / 
 * 
 * somente para retornar versão do app
 * 
 * @request curl -X GET localhost:8080/
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
 * 
 * Busca e retorna todos usuarios cadastrados no sistema
 * 
 * @request curl -X GET localhost:8080/api/users
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
 * 
 * Registra um novo usuário no sistema valida CPF e EMAIL
 * 
 * @request curl -X POST -H "Content-Type: application/json" \
 * -d '{"nome":"Andre Xavier","cpf":"1234567890","telefone":"62999999999",\
 * "email":"andre@examplecoorp.com","data_nascimento":"1986-05-05","senha":"@admin",\
 * "rua":"Sem Fim","cidade":"Jurema","estado":"GO","numero":999,"bairro":\
 * "JD Cunha","complemento":""}' localhost:8080/api/registry
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
        ['status' => 'success', 'message' => 'User registred!'], 201
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/user/{user_id}
 * 
 * GET - Busca um unico usuário pelo ID
 * 
 * @request curl -X GET localhost:8080/api/user/1
 */
$app->get('/api/user/{user_id}', function (Request $request, Response $response) use ($app) {
    $route = $request->getAttribute('route');
    $user_id = $route->getArgument('user_id');
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $user = $usersRepository->find($user_id);
    if (!$user) {
        throw new \Exception("Usuario não encontrado.", 404);
    }
    $return = $response->withJson(
        ['status' => 'success', 'user' => $user], 200
    )->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * /api/user/{user_id}
 * 
 * PUT - Atualiza os dados do usuário
 * 
 * Valida se usuário existe e atualiza os dados caso exista
 * 
 * @request curl -X PUT -H "Content-Type: application/json" \
 * -d '{"nome":"Andre Xavier","cpf":"1234567890","telefone":"62999999999",\
 * "email":"andre@examplecoorp.com","data_nascimento":"1986-05-05","senha":"@admin",\
 * "rua":"Sem Fim","cidade":"Jurema","estado":"GO","numero":999,"bairro":\
 * "JD Cunha","complemento":""}' localhost:8080/api/user/1
 */
$app->put('/api/user/{user_id}', function (Request $request, Response $response) use ($app) {
    $params = (object) $request->getParams();
    $route = $request->getAttribute('route');
    $user_id = $route->getArgument('user_id');
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $user = $usersRepository->find($user_id);
    if (!$user) {
        throw new \Exception("Usuário não encontrado, impossivel atualizar!.", 404);
    }
    /**
     * Atualiza o usuário
     */
    $user->setNome($params->nome)
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
        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (Exception $e) {
            throw new \Exception([
                'status' => 'fail',
                'user_id' => $user_id, 
                'message' => "Falha ao atualizar o usuario!"
            ], 400);
        }
        $return = $response->withJson(
            [
              'status' => 'success', 
              'user_id' => $user_id, 
              'message' => 'User atualizado!'
            ], 200
        )->withHeader('Content-type', 'application/json');
        return $return;
});

/**
 * /api/login
 * 
 * Realiza o login do usuário verificando EMAIL e SENHA
 * 
 * @request curl -X POST -H "Content-Type: application/json" \
 * -d '{"email":"andre.ferreira@soluti.com.br","senha":"admin"}' localhost:8080/api/login
 */
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $params = (object) $request->getParams();
    $email_req = $params->email;
    $senha_req = generate_password($params->senha);
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $user = $usersRepository->findOneBy(['email' => $email_req]);
    if (!$user) {
        throw new \Exception("User not found.", 404);
    } elseif (($user->email === $email_req) and ($user->senha === $senha_req)) {
        $return = $response->withJson(
            ['status' => 'success', 'message' => 'Usuario autenticado'], 200
        )->withHeader('Content-type', 'application/json');
        return $return;
    }
    $return = $response->withJson(
        ['status' => 'fail', 'mensage' => 'Email ou senha incorretos'], 400
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