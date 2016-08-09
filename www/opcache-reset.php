<?php
	
	// Used to flush php5.5 OpCache on deployments.
	// Called via http to flush php-fpm cache.

	$token = "1U2_2XJz";

	if (!function_exists('opcache_reset'))
		die('Error: function opcache_reset() does not exists!');

	if ($_GET['_token'] != $token)
		die('Error: invalid request');

    // reset
    opcache_reset();

    echo 'success';
