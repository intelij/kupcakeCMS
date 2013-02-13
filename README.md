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


####Things in here are unfinished and a lot of it is undocumented. I've done most of what I can and there will be lots of bugs. This is just a starting point for what I'm hoping will turn into a nice bootstrap for plonking into a new client's website or even your own.
More files will be uploaded when i get the time.


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


##Sample implementation 2:


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

##Not the best and secure implementation, i would suggest you use prepared statements with PDO.  I personally prefere PDO over MySQLi implementation.

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
