<?php 
  require 'php-sdk/facebook.php';
	$facebook = new Facebook(array(
		'appId'  => 'YOUR_APP_ID',
		'secret' => 'YOUR_APP_SECRET'
	));
	$qty = 3;
	$currentoffset = $_GET['offset'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Movie Recommendator</title>
	<link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
	<p>Love watching movies, but can't find new stuff to watch? Put your friends to work! This app will let you check out your friends' favorite movies.</p>
</header>
<?php
	//get user from facebook object
	$user = $facebook->getUser();
	
	if ($user): //check for existing user id
		//print logout link
		echo '<p class="notes"><a href="logout.php">logout</a></p>';
		
		$user_graph = $facebook->api(array(
			'method'=>'fql.query',
			'query'=>"SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND movies !=''"
		));

		$howmanyfriends = count($user_graph);
		$offsettext = ($currentoffset) ? "OFFSET $currentoffset" : "";

		$moviefriends_graph = $facebook->api(array(
			'method'=>'fql.query',
			'query'=> "SELECT name, uid, movies, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND movies !='' LIMIT $qty $offsettext"
		));
		
		echo '<div class="moviegroup">';
		foreach ($moviefriends_graph as $key => $value) {
			echo '<div class="friend group">';
			echo '<div class="friendinfo group">';
			echo '<a href="http://facebook.com/', $value['uid'], '" target="_top">';
			echo '<img class="friendthumb" src="https://graph.facebook.com/', $value['uid'],'/picture" alt="',$value['name'],'"/>';
			echo "</a>";
			echo "<h2>", $value['name'],'</h2>';
			echo '<h3>Recommends</h3>';
			echo '</div>'; //friendinfo
			echo '<ul class="movies group">';
			
			$moviespath = '/'.$value['uid'].'/movies?fields=id,name,description,picture.type(square).height(100).width(100)';
			$movies_graph = $facebook->api($moviespath);
			
			foreach ($movies_graph['data'] as $moviekey => $movievalue) {
				echo '<li>';
				echo '<a href="',$movievalue['link'],'" target="_top">';
				echo '<img class="moviethumb" src="',$movievalue['picture']['data']['url'],'" alt="',$movievalue['name'],'" title="',$movievalue['name'],'" />';
				echo '</a>';
				echo '<div class="movieinfo">';
				echo '<div class="wrapper">';
				echo '<h3>', $movievalue['name'], '</h3>';
				echo '<p>', $movievalue['description'], '</p>';
				echo '</div>'; // wrapper
				echo '</div>'; // movie info
				echo '</li>'; // list
			} //go through each list of recommendations
			
			echo '</ul>'; //list of movies
			echo "</div>"; // movie group
		} //iterate through friends graph
		
		$totalpages = ceil($howmanyfriends/$qty); //total pages
		$currentpage = ($currentoffset/$qty)+1; //current page
		$nextoffset = $currentoffset + $qty; //increment offset
		
		if ($totalpages > 1) :
			echo '<div class="paging">';
				echo '<div class="pagenav">';
				
				if ($currentoffset >= $qty):
					echo '<span class="previous">';
					echo '<a href="',$_SERVER['SELF'],'?offset=',$currentoffset-$qty,'">&laquo; Previous</a>';
					echo '</span>';
				endif; // previous link


				for ($i = 0; $i < $totalpages; $i++) {
					echo '<span class="number';
					if ($i===($currentpage-1)) { echo ' current '; }
					echo '">';
					echo '<a href="',$_SERVER['SELF'],'?offset=', $i * $qty,'">', $i+1, '</a>';
					echo '</span>';
				}

				if ($nextoffset < $howmanyfriends):
					echo '<span class="next">';
					echo '<a href="',$_SERVER['SELF'],'?offset=',$nextoffset,'">Next &raquo;</a>';
					echo '</span>';
				endif; // next link
				
				echo '</div>'; //pagenav
				echo '<div class="info">Page ', $currentpage ,' of ', $totalpages, '</div>';
				echo '<p>You have ', $howmanyfriends, ' friends</p>';
			echo '</div'; // paging section
		endif; // there is at least one page
		echo '</div>'; //moviegroup
				
	else: //user doesn't exist
		$loginUrl = $facebook->getLoginUrl(array(
			'diplay'=>'popup',
			'scope'=>'email, friends_likes',
			'redirect_uri' => 'REDIRECT_URL'
		));
		echo '<div class="notes">';
		echo '<p>To get access to the movie recommendator, please <a href="', $loginUrl, '" target="_top">login</a></p>';
		echo '</div>';
	endif; //check for user id
?>
</body>
</html>
