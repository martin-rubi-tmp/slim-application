<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Youbim\Collections\RedBeanCollection;

/// Initialize endpoints
if( ! RedBeanCollection::is_connected() ) {
    $mysql = $container->get('settings')['mysql'];
    RedBeanCollection::setup_connection( $mysql['connect'], $mysql['user'], $mysql['password'] );
}

// Routes

$app->get('/users', function (Request $request, Response $response, array $args) {
    $action = new \Youbim\API\ListUsersEndPoint();

    $json = $action->evaluate();

    return $response->withJson($json);
});

$app->post('/users/create', function (Request $request, Response $response, array $args) {
    $action = new \Youbim\API\CreateUserEndPoint();

    $json = $action->evaluate( $request->getParsedBody() );

    return $response->withJson($json);
});