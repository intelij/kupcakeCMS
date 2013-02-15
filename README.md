##Custom re-usable functions
===========

These are my custom functions, nothing to do with cake php but everything to do with my custom KupcakeCMS (MVC based CMS).


USAGE: http://www.intelligentexecutive.com/forum


###Simple CMS using Bootstrap frontend


I used to have a CodeIgniter bootstrap framework that dealt with basic CRUD stuff, news articles, image galleries, users, permissions etc. All based on the amazing kupcakeCMS framework.

###Feature Overview

News Articles (with image uploads)
User Management
Role Management
Help and Support (sends an email from a form in the backend)
Gallery and Images
Setup Of Database and User Automatic
Setting Up The Database


####Status -> Pending

Things in here are unfinished and a lot of it is undocumented. I've done most of what I can and there will be lots of bugs. This is just a starting point for what I'm hoping will turn into a nice bootstrap for plonking into a new client's website or even your own.
More files will be uploaded when i get the time.

logout.php (logout but remain within the facebook iframe environment)

```php
<?php
  require 'php-sdk/facebook.php';
	$facebook = new Facebook(array(
		'appId'  => 'YOUR_APP_ID',
		'secret' => 'YOUR_APP_SECRET'
	));

	setcookie('fbs_'.$facebook->getAppId(),'', time()-100, '/', 'intelij.co.uk');
	$facebook->destroySession();
	header('Location: index.php');

```


###PDO implementation :


```php
<?php
function insert_album_sql( $album )
{
    global $CRUD;
    $dbh = $CRUD['dbh'];

    $query = '
        INSERT INTO album
            ( title, artist, label, released )
            VALUES ( ?, ?, ?, ? )
    ';

    $sth = $dbh->prepare($query);
    if($sth) $sth->execute( array( @$album['title'], @$album['artist'], @$album['label'], @$album['released'] ) );
    else error("insert_album_sql: insert prepare returned no statement handle");

    // check for errors
    $err = $sth->errorInfo();
    if($err[0] != 0) error( $err[2] );

    $id = $dbh->lastInsertId();
    return($id);
}

```

Not the best and secure implementation, i would suggest you use prepared statements with PDO.  I personally prefere PDO over MySQLi implementation.

```php
private function insert($table, $arr)
    {
        $query = "INSERT INTO " . $table . " (";
        $pref = "";
        foreach($arr as $key => $value)
        {
            $query .= $pref . $key;
            $pref = ", ";
        }
        $query .= ") VALUES (";
        $pref = "";
        foreach($arr as $key => $value)
        {
            $query .= $pref . "'" . $value . "'";
            $pref = ", ";
        }
        $query .= ");";
        return $this->db->query($query);
    }

```


###Facebook with javascript sample code

```javascript
window.fbAsyncInit = function() {
	FB.init({
		appId      : '################', // App ID
		channelUrl : '//intelij.co.uk/channel.php', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		frictionlessRequests : true, // enable frictionless requests		
		xfbml      : true  // parse XFBML
	});

	// Additional initialization code here
	populateStories();

	//Next, find out if the user is logged in
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			var uid = response.authResponse.userID;
			accessToken = response.authResponse.accessToken;

			FB.api('/me', function(info) {
				console.log(info);
				$('#welcome').html("Hello " + info.first_name );
			});

			FB.api( '/################/achievements', 'get', {
				'access_token': appToken
			  }, function(appResponse) {
				//Declare variable to hold App Achievement Data
				appAchievements=appResponse.data;
			
				//Output App Achievements
				var output =  '<h2 class="label">Achievements</h2>';
				output += '<p>Achievements is our way to thank you for being active in our app...To get more try interacting with our app.</p>';
			
				output += '<h3>Available Achievements</h3>';
				$.each(appAchievements, function(appIndex, appObject) {
				  output += '<div class="article group">';
				  output += '<img src="'+appObject.image[0].url+'" alt="' + appObject.title + '">';
				  output += '<div class="text group">';
				  output += '<h4>' + appObject.title + '</h4>';
				  output += '<p>' + appObject.description + '</p>';
				  output += '</div><!-- text -->';
				  output += '</div><!-- articles -->';
				});
			
				document.getElementById('appachievements').innerHTML = output;
			
			}); //get app achievements

			  //Get Achievements from this user
			  FB.api( '/' + uid + '/achievements', 'get', {
				  'access_token': accessToken
			  }, function(userResponse) {
				  userAchievements=userResponse.data;
		
				  //Output User Achievements
							if (userAchievements.length) {

          //Output User Achievements
            					output = '<h3>Your Achievements</h3>';

				    		        for (i in userAchievements) {

									  $.each(appAchievements, function(appIndex, appObject) {
										  //If the user achievements match app achievements, display them.
										  if (userAchievements[i].achievement.title==appObject.title) {
											output += '<div class="article group">';
											output += '<img src="'+appObject.image[0].url+'" alt="' + appObject.title + '">';
											output += '<div class="text">';
											output += '<h4>' + userAchievements[i].achievement.title + '</h4>';
											output += '<p>' + appObject.description + '</p>';
											output += '</div><!-- Text -->';
											output += '</div><!-- article -->';
										  }; // Check if titles match
									  }); //Go through each app Achievement



						            } //go through each User Achievement



								//Check to see if user has a newbie achievement
								var hasNewbie;
								$.each(userAchievements, function(userIndex, userObject) {
								  if (userObject.achievement.title='Newbie Achievement') {
									hasNewbie=true;
									return false;
								  }
								});
					
								if (!(hasNewbie)) {
								  //try to post a newbie achievement to a user
								  FB.api( '/'+uid+'/achievements?access_token='+appToken, 'post', {
									  'achievement': '/achievement-newbie.php'
									});
								} //user doesn't have newbie achievement


							} else { //user has achievements
								var output = '<p>Sorry, you have not earned any achievements yet.</p>';
								FB.api( '/'+uid+'/achievements?access_token='+appToken, 'post', {
									'achievement': 'achievement-newbie.php'
								});


							} //user has no achievements
				  document.getElementById('userachievements').innerHTML = output;
		
			  }); // get user achievements



		} else if (response.status === 'not_authorized') {
			//User is logged into Facebook, but not your App


			  var oauth_url = 'https://www.facebook.com/dialog/oauth/';
			  oauth_url += '?client_id=401236519908500'; //Your Client ID
			  oauth_url += '&redirect_uri=' + 'https://'; //Send them here if they're not logged in
			  oauth_url += '&scope=user_about_me,email,user_location,user_photos,publish_actions,user_birthday,user_likes';//permissions
		
			  window.top.location = oauth_url;


		} else {
			// User is not logged into Facebook at all
			window.top.location ='https://www.facebook.com/index.php';
		} //response.status
	}); //getLoginStatus
}; //fbAsyncInit


function populateStories() {
$.getJSON('/?json=recentstories', function(data) {
    var output = '';
    var excerpt='';
    output = '<h2 class="label">Latest Blog Posts</h2>';


    $.each(data.posts, function(key, val) {
      var title = data.posts[key].title;
      var link = data.posts[key].url;

      //Get excerpt, but remove click to read link
      var tempDiv = document.createElement("tempDiv");
      tempDiv.innerHTML = data.posts[key].excerpt;
      $("a", tempDiv).remove();
      var excerpt = tempDiv.innerHTML;


      //Set up the output
      output += '<div id="storyid' +key+ '" class="articles">';
      output += '<h3><a href="' + link + '" target="_blank">' + title + '</a></h3>';
      output +='<p>' + excerpt;
	  output += '<a onclick="postToFeed(\'' + title + '\',\'' + link + '\',\'' + excerpt + '\');">Post to Feed</a>';
      output += '<a onclick="messageToFriend(\'' + title + '\',\'' + link + '\',\'' + excerpt + '\'); return false;" >Message a Friend</a>';
      output += '</p>';
      output += '</div>';
    }); //Go through each piece of JSON data
    document.getElementById('blog').innerHTML=output;
  }); //Get JSON Data for Stories
} //Populate Stories



function postToFeed(myTitle, myLink, myExcerpt) {
	FB.ui({
		method: 'feed',
		'link': myLink,
		'picture': 'images-sm.png',
		'name': myTitle,
		'caption': 'View Source Blog',
		'description': myExcerpt
	}, function(response) {
		if (response && response.post_id) {
			document.getElementById('mymessage').innerHTML = "Thanks. This has been posted onto your timeline.";
		} else {
			document.getElementById('mymessage').innerHTML = "The post was not published.";
		} //Response from post attempt
	}); // Call to FB.ui
} // postToFeed

function messageToFriend(myTitle, myLink, myExcerpt) {
  FB.ui({
    'method': 'send',
    'link': myLink,
    'picture': 'images-sm.png',
    'name': myTitle,
    'caption': 'View Source Blog',
    'description': myExcerpt
  }, function(response) {
    if (response && response.post_id) {
      document.getElementById('mymessage').innerHTML = "Thanks. The message has been sent.";
    } else {
      document.getElementById('mymessage').innerHTML = "The message was cancelled.";
    } //Response from send attempt
  }); // Call to FB.ui
} // messageToFriend

function requestToFriends() {
  FB.ui({
      method: 'apprequests',
      title: 'View Source Request',
      message: 'Join me and be a part of the View Source revolution!'
  }); // Call to FB.ui
} // messageToFriend


function populateVideos(data) {
  var entries = data.feed.entry;
  var output = '<h2 class="label">Latest Videos</h2>';

  for (var i=0; i<data.feed.entry.length; i++) {
    var entriesID=entries[i].id.$t.substring(38);
    var entriesTitle=entries[i].title.$t;
    var entriesDescription=entries[i].media$group.media$description.$t;
    var entriesThumbnail='https://i.ytimg.com/vi/' + entriesID + '/1.jpg';

    if (i==0) {
      output += '<div class="first">';
      output +=   '<h3>' + entriesTitle + '</h3>';
      output +=   '<iframe src="https://www.youtube.com/embed/'+entriesID+'?wmode=transparent&amp;HD=0&amp;rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay="0" frameborder="0" allowfullscreen></iframe>';
      output +=   '<p>' + entriesDescription + '</p>';
      output += '</div>';
      output += '<ul>';
    } else {
      output += '<li><div class="entriestitle">' + entriesTitle + '</div>';
      output += '<a href="https://www.youtube.com/watch?v=' + entriesID + '&feature=youtube_gdata" target="_blank"><img src="' + entriesThumbnail + '" alt=' + entriesTitle + ' /></a>';
    }
  }
  output +='</ul>';
  document.getElementById('videogroup').innerHTML = output;
}

// Load the JavaScript SDK Asynchronously
(function(d){
  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
  d.getElementsByTagName('head')[0].appendChild(js);
}(document));

```


```javascript

window.fbAsyncInit = function() {
	FB.init({
		appId      : '', // App ID
		channelUrl : '', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		frictionlessRequests : true, // enable frictionless requests		
		xfbml      : true  // parse XFBML
	});

	// Additional initialization code here

	//Next, find out if the user is logged in
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			var uid = response.authResponse.userID;
			accessToken = response.authResponse.accessToken;

			FB.api('/me', function(info) {
				console.log(info);
  			  	$('#welcome').html("Hello there " + info.birthday );
			});


		} else if (response.status === 'not_authorized') {
			//User is logged into Facebook, but not your App


			  var oauth_url = 'https://www.facebook.com/dialog/oauth/';
			  oauth_url += '?client_id=################'; //Your Client ID
			  oauth_url += '&redirect_uri=' + ''; //Send them here if they're not logged in
			  oauth_url += '&scope=user_about_me,email,user_location,user_photos,publish_actions,user_birthday,user_likes';
		
			  window.top.location = oauth_url;


		} else {
			// User is not logged into Facebook at all
			window.top.location ='https://www.facebook.com/index.php';
		} //response.status
	}); //getLoginStatus
}; //fbAsyncInit

// Load the JavaScript SDK Asynchronously
(function(d){
  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
  d.getElementsByTagName('head')[0].appendChild(js);
}(document));

```

### Facebook Resize

```javascript

<div id="fb-root"></div>
<script type="text/javascript"> 
	window.fbAsyncInit = function() {
		FB.init({
		appId : '################',
		status : true, // check login status
		cookie : true, // enable cookies to allow the server to access the session
		xfbml : true // parse XFBML    
	});

	//this resizes the the i-frame
	//on an interval of 100ms
	FB.Canvas.setAutoGrow(100);

	};
	(function() {
		var e = document.createElement('script');
		e.async = true;
		e.src = document.location.protocol +
		'//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);   
	}());   

</script> 
	
```
