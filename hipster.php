<?php
/*
	Plugin Name: Hipster Ipsum
	Plugin URI: http://mangowebdesign.com/
	Description: Use the plugin before anyone hears about it. Example of shortcode: [hipster pbr="10"]. Change number of pbr to however many paragraphs you want. Defaults to 4 if not included. Thank you <a href="http://hipsterjesus.com/">Hipster Jesus</a> for the API.
	Author: Mango Web Design
	Version: 1.1
	Author URI: http://mangowebdesign.com/
*/


function hipster_ipsum_shortcode($atts) {


	// the array to be passed in GET to hipster Jesus
	// 'paras' is number of paragraphs taken from the 'pbr' attribute of the hipster shortcode
	// 'type' is 'hipster-centric'. I think this is better than the mix with latin which is 'hipster-latin', feel free to change
	// 'html' is enabled to automatically do the paragraphs so that I won't have to parse anything
	
	if (!empty($atts['pbr'])) {
		$paras = $atts['pbr'];
	} else {
		$paras = '4';
	}

	$params = array(
		'paras' => $paras, 
		'type' => 'hipster-centric', 
		'html' => 'true'
	);

	// parses the parameters array into GET parameters
	$data_string = http_build_query($params);

	// initializes CURL instance
	$ch = curl_init();

	// sets up options for the CURL stuff to hipster Jesus
	$hipster_url = 'http://hipsterjesus.com/api/?'.$data_string;
	curl_setopt($ch, CURLOPT_URL, $hipster_url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	// executes the curl and saves response
	$response = curl_exec($ch);

	//close curl connection
	curl_close($ch);
	
	//decoding json into php object
	$response = json_decode($response);

	//print the text object. html is already included so no need to parse or do anything with it.
	echo stripslashes($response->text);

}

//adding the shortcode hipster. use pbr="#" where # is the number of paragraphs wanted. Defaults to 4.
add_shortcode('hipster', 'hipster_ipsum_shortcode');

?>