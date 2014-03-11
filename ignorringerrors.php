<?php

//If you don't want file_get_contents to report HTTP errors as PHP Warnings, then this is the clean way to do it, using a stream context (there is something specifically for that):

$context = stream_context_create(array(
    'http' => array('ignore_errors' => true),
));

$result = file_get_contents('http://your/url', false, $context);
