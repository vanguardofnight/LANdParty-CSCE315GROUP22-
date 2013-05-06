<?php
require_once ("facebook.php");
require('GoogleGeocode.php');
  
  session_start();
  $_SESSION = array();
  $config = array(
  'appId' => '136730719847805',
  'secret' => 'eb120b2c585d641cfbbe1b29d3650b08',
  'fileUpload' => true, // optional
  //'cookie' => true,
  );

  $facebook = new Facebook($config);
  $login_params = array(
		'scope' => 'user_location, user_about_me',
		'redirect_uri' => 'http://projects.cse.tamu.edu/chaveser',
		'display' =>  'page'
  );
  
  $login_status_params = array(
	'ok_session' => 'http://projects.cse.tamu.edu/crevia1',
	'no_user' => $facebook->getLoginUrl($login_params),
	'no_session' => $facebook->getLoginUrl($login_params)
  );
  $user_id = $facebook->getUser();
  $access_token = $facebook->getAccessToken;
  
  $logout_params = array(
		'next' =>  'http://projects.cse.tamu.edu/chaveser/'
  );
 if ($user_id) {
    $logoutUrl = $facebook->getLogoutUrl($logout_params);
} else {
    $loginUrl = $facebook->getLoginUrl($login_params);
}
  ?>

  <html>
	<head></head>
	<body>
	<div id="fb-root"></div>
		<script>
		  // Additional JS functions here
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '136730719847805', // App ID
			  channelUrl : '//projects.cse.tamu.edu/chaveser/channel.html', // Channel File
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  xfbml      : true  // parse XFBML
			});
			
			FB.Event.subscribe('auth.login', function(){
				window.location.reload();
			});
			
					// Additional init code here
		 // Here we subscribe to the auth.authResponseChange JavaScript Event. This event is fired
		  // for any auth related change, such as login, logout or session refresh. This means that
		  // whenever someone who was previously logged out then logs in, the correct case below 
		  // will be handled. 
		  //window.location.reload();
		  FB.Event.subscribe('auth.authResponseChange', function(response) {
			
			// Here we specify what we do with the response anytime this event occurs. 
			if (response.status === 'connected') {
			  // The response object is returned with a status field that lets us know what the current
			  // login status of the person is. In this case, we're handling the situation where they 
			  // have logged in to the app.
			  //testAPI();
			 
			} else if (response.status === 'not_authorized') {
			  // In this case, the person is logged into Facebook, but not into the app, so we call
			  // FB.login() to prompt them to do so. 
			  // In real-life usage, you wouldn't want to immediately prompt someone to login 
			  // like this, for two reasons:
			  // (1) JavaScript created popup windows are blocked by most browsers unless they 
			  // result from direct user interaction (such as a mouse click)
			  // (2) it is a bad experience to be continually prompted to login upon page load.
			  FB.login();
			  
			} else {
			  // In this case, the person is not logged into Facebook, so we call the login() 
			  // function to prompt them to do so. Note that at this stage there is no indication
			  // of whether they are logged into the app. If they aren't then they'll see the Login
			  // Dialog right after they login to Facebook. 
			  // The same caveats as above apply to the FB.login() call here.
			  FB.login();
			  
			}
		  });
		  };
		  

		  // Load the SDK Asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
		// Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Good to see you, ' + response.name + '.');
    });
  }
</script>
</body>
</html>
		
	<?php
  
	if ($user_id) {
		
	  try {
		$fql = 'SELECT name FROM user WHERE uid = '. $user_id;
		$ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
		$user_name = $ret_obj[0]['name'];
		echo '<pre>Name: ' . $user_name . '</pre>';	
		$fql2 = 'SELECT current_location FROM user WHERE uid = '. $user_id;	
		$ret_obj2 = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql2,
                                 ));		
		$user_location = $ret_obj2[0]['current_location']['name'];
		echo '<pre>Location: ' . $user_location . '</pre>';
		} catch (FacebookApiException $e) {
		echo "<br />";
		echo "Message: " . $e->getMessage();
		echo "<br />";
		echo "<br />";
		echo $e->getTraceAsString();
		echo "<br />";
		echo "<br />";
        echo 'Please <a href="' . $login_url . '">login 1.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
		}
	} else {
	// No user, so print a link for the user to login
      $login_url = $facebook->getLoginUrl($login_params);
	}
	//Save to XML
	$locations_array = array();
	$locations_array [] = array(
		'name' => $user_name,
		'location' => $user_location,
	);	
	
	$doc = new DOMDocument();
	$doc ->formatOutput = true;
	$preserveWhiteSpace = true;
	$r = $doc->createElement( "locations" );
	$doc-> appendChild($r);
	
	foreach($locations_array as $locs)
	{
	$b = $doc->createElement("User");
	$name = $doc->createElement("name");
	$name -> appendChild($doc->createTextNode($locs['name']));
	$b->appendChild($name);
	$r->appendChild($b);
	
	$b = $doc->createElement("location1");
	$location = $doc->createElement("location2");
	$location -> appendChild($doc->createTextNode($locs['location']));
	$b->appendChild($location);
	$r->appendChild($b);
	
	}
	$doc->save("locations.xml");
	
	//Load from XML
	$doc = new DOMDocument();
	$doc->load('locations.xml');
	
	$locations_array = $doc->getElementsByTagName("locations");
	foreach($locations_array as $locs){
		$locations = $locs->getElementsByTagName("location1");
		$location = $locations->item(0)->nodeValue;
	}
	function replace_whitespaces($string){
		$new_location = preg_replace('/\s+/', '+',$string);
		return $new_location;
	}
	$new_location = replace_whitespaces($location);
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/xml";
	$search_variable = "video+game+stores+near";
	$api_key = "&sensor=false&key=AIzaSyDhr8wxu4MLOEqivMJc-E16vGxM_qhtgZk";
	$new_url = $url . "?query=$search_variable$new_location&radius=50" . $api_key;
	//$_SESSION['new_url']=$new_url;
	
	$current_file = file_get_contents($new_url);
	file_put_contents('gamestores.xml', $current_file);
	
	$doc = new DOMDocument();
	$doc->load('gamestores.xml');
	
	$names_array = array();
	$addr_array = array();
	$lat_array = array();
	$lng_array = array();
	$results = $doc->getElementsByTagName("result");
	foreach($results as $res){
		$names = $res->getElementsByTagName("name");
		$name = $names->item(0)->nodeValue;
		
		$addresses = $res->getElementsByTagName("formatted_address");
		$address = $addresses->item(0)->nodeValue;
		
		$lats = $res->getElementsByTagName("lat");
		$lat = $lats->item(0)->nodeValue;
		
		$lngs = $res->getElementsByTagName("lng");
		$lng = $lngs->item(0)->nodeValue;
		
		$names_array [] = $name;
		$addr_array [] = $address;
		$lat_array [] = $lat;
		$lng_array [] = $lng;	
	}
	$center_lat = $lat_array[0];
	$center_lng = $lng_array[0];
	echo $center_lat;
	echo $center_lng;
	session_start();
	$_SESSION['cent_lat']=$center_lat;
	$_SESSION['cent_lng']=$center_lng;
	session_write_close();
	$markerDoc = new DOMDocument();
	$node = $markerDoc->createElement("markers");
	$parnode = $markerDoc->appendChild($node);
	$size = sizeof($names_array);
	
	for($i=0; $i<$size; $i++){
		$node = $markerDoc->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("name", $names_array[$i]);
		$newnode->setAttribute("address", $addr_array[$i]);
		$newnode->setAttribute("lat", $lat_array[$i]);
		$newnode->setAttribute("lng", $lng_array[$i]);
	}
	$markerDoc->save("markerDoc.xml");
  ?>
	</body>
</html>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php if ($user_id): ?>
    <?php echo "Welcome, ".$user_name. ".<br />"; ?>
    <a href="<?php echo $logoutUrl;?>">
      <img src="http://www.picturehealing.com/dashboard/app/Assets/fblogout.png">
    </a>
    <?php else: ?>
      <a href="<?php session_destroy();
	  echo $loginUrl; ?>">
        <img src="http://www.challengeyoursoul.com/images/facebooklogin.png">
      </a>
    <?php endif ?>
	
  <!-- <p> Run xmlTest <a href = "http://projects.cse.tamu.edu/chaveser/xmlTest.php">here</a><p> -->
 <p>You can get to php from <a href="http://projects.cse.tamu.edu/crevia1/facebook_1_pg.php">here</a><p>
 <p>You can get to the map from <a href="http://projects.cse.tamu.edu/chaveser/searchResults2.php">here</a><p>
  <iframe = src="http://www.facebook.com/plugins/like.php?href=http://students.cse.tamu.edu/crevia1/"
   scrolling = "no" frameborder="0"
   style="border::none; width:450px; height:80px"><iframe>
	
 </body>
</html>
