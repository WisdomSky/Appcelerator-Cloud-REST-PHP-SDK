<?php


	include "Cloud.class.php";
	
	/* Instantiating ACS */
	$ACS = new Cloud(array(
			"key" 	=> "cGeA90mqOYIXg7anvC6p59t9rMLDAVIP"
	));
	// Login using an admin user
	$ACS->login("trs.markt@gmail.com","trscebu123");



	/*
		The cloud class allows you to access directly the api() method which will give you more freedom.
		the CustomObject, PushNotification, CustomObjects, QueryCustomObjects, User class uses this method internally.
		
	*/

	
	// sending push notification using the api() method		

	$ACS->api("/push_notification/notify", "POST", array(
  		"channel" => "free_channel", 
  		"payload" => stripcslashes(json_encode((object)array("badge"=>"+1","alert"=>"WisdomSky3"))),
  		"to_ids"  => "everyone"
	));
	
	echo $ACS->response();