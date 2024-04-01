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

// just a test
$router->get('/test', function() use($app) {
	// echo $app;
	echo Session::class;
	print phpinfo();
});

$router->get('/session', function() use($app) {
	echo ini_get('session.save_path');
});

$router->get('/login', function() use($app) {
	// echo '<form method="post">';
	// echo '<button type="submit">Login</button>';
	// echo '</form>';
	
$op = <<<WEBTXT
<div class="container">
<form method="post">
<label for=\"uname\"><b>Username</b></label>
<input type="text" placeholder="Enter Username" name="uname" required>

<label for="psw"><b>Password</b></label>
<input type="password" placeholder="Enter Password" name="psw" required>    
<button type="submit">Login</button>
<label>
<input type="checkbox" checked="checked" name="remember"> Remember me
</label>
</form>
</div>
WEBTXT;
echo $op;
});

$router->get('/logout', function() use($app) {
    $session = $app->session();
	// var_dump($session);
	if (!$session) echo 'no Session variable';

    // do your login logic here
    // validate password, etc.
	
    $session->set('is_logged_in', false);
    $session->del('user');
    $session->del('password');

    // any time you write to the session, you must commit it deliberately.
   $session->commit();
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
	$uname = $_POST['uname'];
	$psw = $_POST['psw'];

    // $session = Flight::session();
    $session = $app->session();
	// var_dump($session);
	if (!$session) echo 'no Session variable';

    // do your login logic here
    // validate password, etc.

    // if the login is successful
	$user = $uname;
	$pass = $psw;
    $session->set('is_logged_in', true);
    $session->set('user', $user);
    $session->set('password', $psw);

    // any time you write to the session, you must commit it deliberately.
   $session->commit();
	// if ($session->get('is_logged_in')) { 
	// 	echo $session->get('user');
	// 	echo 'You are logged in';
	// }
	// else {
	// 	echo 'You are NOT Logged in';
	// }
	// echo $session->id(), $session->get('user');

});
