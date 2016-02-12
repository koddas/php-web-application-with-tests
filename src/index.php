<?php
// Just a shorthand for the Slim object
use \Slim\Slim as Slim;

// Loads all dependencies
require '../vendor/autoload.php';

Slim::registerAutoloader();

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
$app->get('/insult', function () use ($app) {
	$insult = get_insult(get_name(date('Y'), date('m'), date('d')));
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
		function ($year, $month, $day) use ($app) {
	$title = $year . '-' . $month . '-' . $day . "'s insult";
	$insult = get_insult(get_name($year, $month, $day));
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
$app->get('/insult/:name', function () use ($app) {
	$insult = get_insult($name);
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

/**
 * Draws an insult at random from the pool of available FOAAS endpoints
 * 
 * @return A string containing an endpoint name.
 */
function pick_insult() {
	$endpoints = array("thanks", "fascinating", "because", "bye", "diabetes");
	return $endpoints[rand(0, 4)];
};

/**
 * Fetches an insult from the FOAAS web service.
 * 
 * @param $name A string containing a name.
 * @return A string with the fetched insult.
 */
function get_insult($name) {
	$client = new Guzzle\Http\Client();
	
	$url = "http://foaas.herokuapp.com/" . pick_insult() . "/" . $name;
	$headers = array('Accept' => 'application/json');
	$request = $client->get($url, $headers);
	$response = $request->send();
	$data = $response->json();
	$insult = array('signed' => $name, 'message' => $data['message']);
	
	return $insult;
}

/**
 * Fetches today's name from the Svenska Dagar web service. The year variable
 * needs to be four digits long, while the month and day variables need to be
 * two digits long each. All three are expected to be strings.
 * 
 * @param $year The year as a four-digit string.
 * @param $month The month as a two-digit string.
 * @param $day The day as a two-digit string.
 * @return A string with the name.
 */
function get_name($year, $month, $day) {
	$client = new Guzzle\Http\Client();
	
	$url = "http://api.dryg.net/dagar/v2/" . $year . "/" . $month . "/" . $day;
	$request = $client->get($url);
	$response = $request->send();
	$data = $response->json();
	
	return array_pop($data['dagar'])['namnsdag'][0];
}