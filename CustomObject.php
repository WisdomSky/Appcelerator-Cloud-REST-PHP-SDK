<?php


	include "Cloud.class.php";
	
	/* Instantiating ACS */
	$ACS = new Cloud(array(
			"key" 	=> "cGeA90mqOYIXg7anvC6p59t9rMLDAVIP"
	));
	// Login using an admin user
	$ACS->login("trs.markt@gmail.com","trscebu123");




	/*
		Custom Object object utilizing ORM approach
	*/

	// Creating a new custom object of class news.
	$news = $ACS->CustomObjects->create("news");
	$news->type = "free";
	$news->title = "AS Hello World XD";
	$news->content = "hahahah.";
	$news->html = "Hehehehe";
	$news->save(); // invoking this method will commit the changes

	// ---------------------------------------


	// Updating a custom object by ID
	$news = $ACS->CustomObjects->create("news");
	$news->get("5562dc95ac4547b5fc6fe8ba"); // Get the existing custom object with the ID 5562dc95ac4547b5fc6fe8ba
	$news->title = "XD Hello World XD";
	$news->save();

	// ---------------------------------------

	// Permanently deleting a custom object
	$news = $ACS->CustomObjects->create("news");
	$news->get("5562dc95ac4547b5fc6fe8ba"); // Get the existing custom object with the ID 5562dc95ac4547b5fc6fe8ba
	$news->destroy(); // the magic word


	// ---------------------------------------

	/*
		Querying Custom Objects
	*/

	$news = $ACS->CustomObjects->query("news");

	// set the conditions
	$news->where = array();
	$news->limit = 100;
	
	// fetch results
	$the_results = $news->results(); // this will return an array of custom object objects
	
	// handle the results 
	foreach ($the_results as $obj) {
		$obj->destroy();
		echo $obj->id.":".$ACS->response()."<br><br>";
	} 