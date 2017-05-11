<?php
class Utils
{
	/**
	 * Draws an insult at random from the pool of available FOAAS endpoints
	 * 
	 * @return A string containing an endpoint name.
	 */
	public function pick_insult() {
		$endpoints = array("thanks", "fascinating", "because", "bye", "diabetes");
		return $endpoints[rand(0, 4)];
	}

	/**
	 * Fetches an insult from the FOAAS web service.
	 * 
	 * @param $name A string containing a name.
	 * @return A string with the fetched insult.
	 */
	public function get_insult($name) {
		$client = new Guzzle\Http\Client();
	
		$url = "http://foaas.herokuapp.com/" . $this->pick_insult() . "/" . $name;
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
	public function get_name($year, $month, $day) {
		$client = new Guzzle\Http\Client();
	
		$url = "http://api.dryg.net/dagar/v2/" . $year . "/" . $month . "/" . $day;
		$request = $client->get($url);
		$response = $request->send();
		$data = $response->json();
	
		return array_pop($data['dagar'])['namnsdag'][0];
	}
}
