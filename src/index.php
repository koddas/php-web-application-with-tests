<?php
// Just a shorthand for the Slim object
use \Slim\Slim as Slim;

// Loads all dependencies
require '../vendor/autoload.php';
require 'Utils.php';

Slim::registerAutoloader();

// Create a Utils instance
$utils = new Utils();

// Sets up the application
$app = new Slim(array(
		'templates.path' => './views',
		'view' => new \Slim\Views\Twig()
));

/**
 * The / endpoint. This is where the homepage lives.
 */
$app->get('/', function () use ($app) {
	$message = array('title' => "Today's insult");
	$app->render('index.tpl', $message);
});

/**
 * The /insult endpoint.
 */
$app->get('/insult', function () use ($app, $utils) {
	$insult = $utils->get_insult($utils->get_name(date('Y'), date('m'), date('d')));
	$accept = $app->request->headers->get('ACCEPT');
	
	if ($accept == 'application/json') {
		$app->response->headers->set('Content-Type', 'application/json');
		$app->response->setBody(json_encode($insult));
	} else {
		$message = array('title' => "Today's insult", 'insult' => $insult);
		$app->render('insult.tpl', $message);
	}
});

/**
 * Fetches today's name from the Svenska Dagar web service. The year variable
 * needs to be four digits long, while the month and day variables need to be
 * two digits long each. All three are expected to be strings.
 */
$app->get('/insult/:year/:month/:day',
		function ($year, $month, $day) use ($app, $utils) {
	$title = $year . '-' . $month . '-' . $day . "'s insult";
	$insult = $utils->get_insult($utils->get_name($year, $month, $day));
	$accept = $app->request->headers->get('ACCEPT');
	
	if ($accept == 'application/json') {
		$app->response->headers->set('Content-Type', 'application/json');
		$app->response->setBody(json_encode($insult));
	} else {
		$message = array('title' => $title, 'insult' => $insult);
		$app->render('insult.tpl', $message);
	}
});

/**
 * The /insult/name endpoint.
 */
$app->get('/insult/:name', function () use ($app, $utils) {
	$insult = $utils->get_insult($name);
	$accept = $app->request->headers->get('ACCEPT');
	
	if ($accept == 'application/json') {
		$app->response->headers->set('Content-Type', 'application/json');
		$app->response->setBody(json_encode($insult));
	} else {
		$message = array('title' => "Personalized insult", 'insult' => $insult);
		$app->render('insult.tpl', $message);
	}
});

/**
 * Produces an error message.
 */
$app->error(function(Exception $e) use ($app) {
	$error = array(
			'message' => 'Internal Server Error',
			'status' => 500,
			'stack' => $e->getMessage()
	);
	
	$app->render('error.tpl', $error, 500);
});

// This starts the application
$app->run();

