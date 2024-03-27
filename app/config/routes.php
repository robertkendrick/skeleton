<?php

use app\controllers\ApiExampleController;
use flight\Engine;
use flight\net\Router;

use Ghostff\Session\Session;	//bobk
// $app->register('session', Session::class);	//bobk

/** 
 * @var Router $router 
 * @var Engine $app
 */
$router->get('/', function() use ($app) {
	$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
});

$router->get('/test', function() use($app) {
	echo Session::class;
});

$router->get('/session', function() use($app) {
	echo ini_get('session.save_path');
});

$router->get('/login', function() use($app) {
    $session = Flight::session();
    // $session = $app->session();
	if (!$session) echo 'no Session variable';
	echo $session->get('user');
});

$router->get('/hello-world/@name', function($name) {
	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
});

$router->group('/api', function() use ($router, $app) {
	$Api_Example_Controller = new ApiExampleController($app);
	$router->get('/users', [ $Api_Example_Controller, 'getUsers' ]);
	$router->get('/users/@id:[0-9]', [ $Api_Example_Controller, 'getUser' ]);
	$router->post('/users/@id:[0-9]', [ $Api_Example_Controller, 'updateUser' ]);
});

//bobk
//Flight::route('POST /login', function() {
$router->post('/login', function() use ($app) {
    // $session = Flight::session();
    $session = $app->session();
	// var_dump($session);
	if (!$session) echo 'no Session variable';

    // do your login logic here
    // validate password, etc.

    // if the login is successful
	$user = "bobk";
    $session->set('is_logged_in', true);
    $session->set('user', $user);

    // any time you write to the session, you must commit it deliberately.
   $session->commit();
	if ($session->get('is_logged_in')) { 
		echo $session->get('user');
		echo 'You are logged in';
	}
	else {
		echo 'You are NOT Logged in';
	}
	echo $session->id(), $session->get('user');

});
