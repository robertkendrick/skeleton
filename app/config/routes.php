<?php

use app\controllers\ApiExampleController;
use flight\Engine;
use flight\net\Router;

use Ghostff\Session\Session;	//bobk
// $app->register('session', Session::class);	//bobk

class MyMiddleware {
    public function before($params) {
        // if (isset($_SESSION['user']) === false) {
        //     Flight::redirect('/login');
        //     exit;
        // }
		// var_dump($params);
		// $session = $app->session();
		$session = Flight::session();
		// if ($session->exist('is_logged-in') === false) { return; }
		$df = $session->exist('is_logged_in');
		if ($df === false) Flight::redirect('/login');
		// if ($session->exist('is_logged_in')) return;
		if($session->get('is_logged_in') === false) {
			Flight::redirect('/login');
			// echo '<br />' . $session->get('user') . ' is logged in';
		}
    }
}

$MyMiddleware = new MyMiddleware();

//Custom Error Example

//Let's say yo

/** 
 * @var Router $router 
 * @var Engine $app
 */
$router->get('/', function() use ($app) {
	// $app->render(' Bk welcome from routes.php', [ 'message' => 'You are gonna do great things!' ]);
	echo session_cache_limiter();
	$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
});

// just a test
$router->get('/test', function() use($app) {
	// echo $app;
	// echo Session::class;
//    $session = Flight::session();
	// header('Cache-Control: no-store, no-cache, must-revalidate, max-age=1');
	// header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	session_cache_limiter('nocache');
	session_cache_expire(0);
	echo session_cache_limiter().'<br />';
	$session = $app->session();
	$df = $session->exist('is_logged_in');
	if ($df === false) Flight::redirect('/login');

	echo '<a href="/logout">logout</a><br />';
	if($session->get('is_logged_in')) {
		echo '<br />' . $session->get('user') . ' is logged in';
	}
	print phpinfo();

// })->addMiddleware($MyMiddleware);
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

// Note: If you use session_start() afterwards, it will overwrite
// your header with Cache-Control: private, max-age=10800, pre-check=10800 
// because 180 minutes is the default value of session.cache_expire
$router->get('/logout', function() use($app) {
	// header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	// header('Cache-Control: no-store');
	// header('Cache-Control: no-cache, no-store, private');
	
	// use session_cache_??? functions before session_start()
	session_cache_limiter('nocache');
	session_cache_expire(0);
	echo session_cache_limiter().'<br />';
	$session = $app->session();
	var_dump($session);
	if (!$session) echo 'no Session variable';

    // do your login logic here
    // validate password, etc.
	
    $session->set('is_logged_in', false);
    $session->del('user');
    $session->del('password');

    // any time you write to the session, you must commit it deliberately.
   $session->commit();
	$session->destroy();
	echo '<br /><a href="/test">test</a><br />';
   echo 'YOU ARE LOGGED OUT';
//    Flight::redirect('/login');
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
	Flight::redirect('/test');
});
