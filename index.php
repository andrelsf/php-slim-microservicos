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
$app->get('/', function ($request, $response, $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
})->setName('home');

/**
 * Pagina para registro
 */
$app->get('/registro', function ($request, $response, $args) {
    return $this->renderer->render($response, 'registro.phtml', $args);
})->setName('registro');

/**
 * Pagina dos usuários
 */
$app->get('/homeusers', function ($request, $response, $args) {
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $users = $usersRepository->findAll();
    return $this->renderer->render($response, 'homeUsers.phtml', ['users' => $users]);
})->setName('homeUsers')->add('Auth');

/**
 *  Pagina para atualização de cadastro
 */
$app->get('/api/user/update/{user_id}', function ($request, $response, $args) {
    $route = $request->getAttribute('route');
    $user_id = $route->getArgument('user_id');
    $entityManager = $this->get(EntityManager::class);
    $usersRepository = $entityManager->getRepository('App\Models\Entity\UserEntity');
    $user = $usersRepository->find($user_id);
    $data_nascimento = date_format($user->data_nascimento, "Y-m-d");
    return $this->renderer->render($response, 'editar.phtml', [
            'user' => $user,
            'data_nascimento' => $data_nascimento
        ]);
})->setName('userUpdate')->add('Auth');

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
    // $return = $response->withJson(
    //     ['status' => 'success', 'users' => $users], 200
    // )->withHeader('Content-type', 'application/json');
    return $users;
})->setName('getAllUsers')->add('Auth');

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
$app->post('/api/registry', function ($request, $response, $args) {
    $params = (object) $request->getParams();
    $entityManager = $this->get(EntityManager::class);
    $user = (new UserEntity())->setNome($params->nome)
                    ->setEmail($params->email)
                    ->setCPF($params->cpf)
                    ->setTelefone($params->telefone)
                    ->setDataNascimento(
                        DateTime::createFromFormat(
                            "Y-m-d", $params->data_nascimento
                        )
                    )
                    ->setSenha(generate_password($params->senha))
                    ->setRua($params->rua)
                    ->setNumero((int)$params->numero)
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
        return $this->renderer->render($response, 'mensage.phtml', [
            'error' => true,
            'url_for' => '/',
            'mensage' => "Falha ao registrar o novo usuario! :("
        ]);
        //throw new \Exception("Falha ao registrar o novo usuario!\n\n".var_dump($e), 400);
    }
    return $this->renderer->render($response, 'mensage.phtml', [
        'error' => false,
        'url_for' => '/',
        'mensage' => "Usuário cadastrado com sucesso!"
    ]);
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
})->setName('getOneUser')->add('Auth');

/**
 * /api/user/{user_id}
 * 
 * PUT - Atualiza os dados do usuário
 * 
 * Valida se usuário existe e atualiza os dados caso exista
 * 
 * @request curl -X POST -H "Content-Type: application/json" \
 * -d '{"nome":"Andre Xavier","cpf":"1234567890","telefone":"62999999999",\
 * "email":"andre@examplecoorp.com","data_nascimento":"1986-05-05","senha":"@admin",\
 * "rua":"Sem Fim","cidade":"Jurema","estado":"GO","numero":999,"bairro":\
 * "JD Cunha","complemento":""}' localhost:8080/api/user/1
 */
$app->post('/api/user/{user_id}', function (Request $request, Response $response, $args) {
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
        ->setCPF($params->cpf)
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
        return $this->renderer->render($response, 'mensage.phtml', [
            'error' => true,
            'url_for' => '/homeusers',
            'mensage' => "Falha ao Atualizar o usuario! :("
        ]);
            // throw new \Exception([
            //     'status' => 'fail',
            //     'user_id' => $user_id, 
            //     'message' => "Falha ao atualizar o usuario!"
            // ], 400);
    }
    return $this->renderer->render($response, 'mensage.phtml', [
        'error' => false,
        'url_for' => '/homeusers',
        'mensage' => "Registro realizado com sucesso! :)"
    ]);
    //return $response->withRedirect($this->router->pathFor('homeUsers'), 201);
})->setName('updateOneUser')->add('Auth');

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
        $_SESSION['isLoggedIn'] = 'yes';
        session_regenerate_id();
        /**
         * Login SUCCESS redirect to home page.
         */
        return $response->withRedirect($this->router->pathFor('homeUsers')); #TODO
    }
    return $response->withRedirect($this->router->pathFor('home'), 403);
})->setName('login');

/**
 * /api/logout TODO
 */
$app->get('/api/logout', function (Request $request, Response $response, $args) {
    unset($_SESSION['isLoggedIn']);
    session_regenerate_id();
    
    // You wouldn't typically redirect to the dashboard, just doing it to prove we are logged out!
    // After redirecting to the dashboard, the middleware will detect the user is not logged in
    // and then redirect to 'home'
    return $response->withRedirect($this->router->pathFor('homeUsers'));
});

function generate_password($password) {
    $hash_passwd = hash("sha256", $password);
    return $hash_passwd;
};

$app->run();