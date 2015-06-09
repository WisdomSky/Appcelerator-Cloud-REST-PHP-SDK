<?php


	include "Cloud.class.php";
	
	/* Instantiating ACS */
	$ACS = new Cloud(array(
			"key" 	=> "cGeA90mqOYIXg7anvC6p59t9rMLDAVIP"
	));
	$ACS->login("trs.markt@gmail.com","trscebu123");
	
	
	echo "ACS :<br>".$ACS->response()."<br><br>";
	
// 	
	// $me = $ACS->me();
// 	
	// echo $me->first_name."<br><br>";
// 	
// 	
	// $me->first_name = "lol";
	// $me->save();
	// echo $me->first_name."<br><br>";
// 	
// 	
// 	
// 	
// 	
// 	
// 	
// 	
// 	
	// // Sending a push notification
	// $ACS->PushNotification->notify(array(
		// "channel"   => "free_channel", 
		// "alert"		=> "Hello World 5",
		// "title"		=> "Coupon",
		// "badge"		=> "+1"
	// )); 	
	// echo "PushNotification#notify():<br>".$ACS->response()."<br><br>";
// 			
// 		
// 		
	// // reset badges		
	// $ACS->PushNotification->reset_badge(); 
	// echo "PushNotification#reset_badge():<br>".$ACS->response()."<br><br>";
// 		
// 		
	// // get the count of subscribed devices		
	// $ACS->PushNotification->count(); 
	// echo "PushNotification#count():<br>".$ACS->response()."<br><br>";
// 			
// 		
	// // set badge	
	// $ACS->PushNotification->set_badge(array(
		// "badge_number" => 3
	// )); 
	// echo "PushNotification#set_badge():<br>".$ACS->response()."<br><br>";
// 	
// 	
// 	
	// // Creating a new custom object
	// // Let's say the classname is "news"
	// $news = $ACS->CustomObjects->create("news");
	// $news->type = "free";
	// $news->title = "AS Hello World XD";
	// $news->content = "hahahah.";
	// $news->html = "Hehehehe";
	// $news->save();
// 	
// 	
// 	
	// echo "CustomObjects#save():<br>".$ACS->response()."<br><br>";
	
	
	// Updating properties of a custom object
	// $news = $ACS->CustomObjects->create("news");
	// $news->get("5562dc95ac4547b5fc6fe8ba");
// 	
	// echo $news->title."<br><br>";
	// $news->title = "XD Hello World XD";
	// $news->save();
	// echo "CustomObject#update():<br>".$ACS->response()."<br><br>";
// 	

	// // Permanently deleting a custom object
	// $news = $ACS->CustomObjects->create("news");
	// $news->get("custom_object_id_here");
	// $news->destroy();
	// echo "CustomObject#delete():<br>".$ACS->response()."<br><br>";
	
	
	
	// $news = $ACS->CustomObjects->query("news");
	// $news->where = array();
	// $news->limit = 100;
	// $the_results = $news->results();
// 	
	// echo "CustomObject#query():<br>".$ACS->response()."<br><br>";
	// foreach ($the_results as $obj) {
		// $obj->destroy();
		// echo $obj->id.":".$ACS->response()."<br><br>";
	// } 


	
	
	$ACS->api("/push_notification/notify", "POST", array(
				  		"channel" => "free_channel", 
				  		"payload" => stripcslashes(json_encode((object)array("badge"=>"+1","alert"=>"WisdomSky3"))),
				  		"to_ids"  => "everyone"
				  ));
	
	echo $ACS->response();
