<?php


	include "Cloud.class.php";
	
	/* Instantiating ACS */
	$ACS = new Cloud(array(
			"key" 	=> "cGeA90mqOYIXg7anvC6p59t9rMLDAVIP"
	));
	// Login using an admin user
	$ACS->login("trs.markt@gmail.com","trscebu123");



	// Sending a push notification
	$ACS->PushNotification->notify(array(
		"channel"   => "free_channel", 
		"alert"		=> "Hello World 5",
		"title"		=> "Coupon",
		"badge"		=> "+1"
	)); 
		
	echo $ACS->response();



	// ---------------------------------------

	// Reset badges		
	$ACS->PushNotification->reset_badge(); 
	//echo $ACS->response();
		
		
	// Get the count of subscribed devices		
	$ACS->PushNotification->count(); 
	//echo $ACS->response();
			
		
	// Set badge	
	$ACS->PushNotification->set_badge(array(
		"badge_number" => 3
	)); 
	//echo $ACS->response();