<?php
	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	$from = "samuel_ro4@hotmail.com";
	$to = "samuel_ro4@hotmail.com";

	$subject = "Checking PHP mail";
	$message = "PHP mail works just fine";
	$headers = "From:" . $from;

	$success = mail($to, $subject, $message, $headers);
	if (!$success) {
		print_r(error_get_last()['message']);
	}

	echo "The email message was sent.";
