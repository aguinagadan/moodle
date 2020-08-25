<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

require_once('../config.php');

$message = $_POST['message'];
//cambiar produccion - samuel
$from = 'samuelro444@gmail.com';
$subject = 'Correo de prueba para Seguimiento';

if($_POST['idUsersAll']) {
	$userIds = explode( ',', $_POST['idUsersAll']);

	foreach($userIds as $userId) {
		$foruser = core_user::get_user($userId);
		$emailTo = $foruser->email;
		var_dump('email => ' . $emailTo);
		var_dump('mensaje => ' . $message);
		var_dump('de => '. $from);
		var_dump('subject => ' . $subject);
		//email_to_user($foruser, $from, $subject, $message);
	}
} else {
	$foruser = core_user::get_user($_POST['idUser']);

	$emailTo = $foruser->email;

	var_dump('email => ' . $emailTo);
	var_dump('mensaje => ' . $message);
	var_dump('de => '. $from);
	var_dump('subject => ' . $subject);
	//email_to_user($foruser, $from, $subject, $message);
}