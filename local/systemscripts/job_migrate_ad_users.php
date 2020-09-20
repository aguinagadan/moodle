<?php
global $CFG;

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

function execCurl($data) {
	$curl = curl_init();

	$url = $data['url'];
	$postFields = $data['postFields'];
	$httpHeader = $data['httpHeader'];
	$httpMethod = $data['httpMethod'];

	$curlSetOptArray = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $httpMethod
	);

	if($httpMethod == 'POST') {
		$curlSetOptArray[CURLOPT_POSTFIELDS] = $postFields;
	}
	$curlSetOptArray[CURLOPT_HTTPHEADER] = $httpHeader;

	curl_setopt_array($curl, $curlSetOptArray);
	$response = curl_exec($curl);
	curl_close($curl);
	$responseData = json_decode($response,true);
	return $responseData;
}
function getADToken() {
	$data = array(
		'url' => 'https://login.microsoftonline.com/b7e26f48-2292-4a14-a355-1aeb8489ae3d/oauth2/token',
		'postFields' => http_build_query(array('grant_type' => 'client_credentials',
											'client_id' => '57f43017-1336-4356-8e65-c81a52eda0f3',
											'client_secret' => 'p._o2p72hK~C9N_op.z_YR93P~z~SjZijz',
											'scope' => 'https://graph.microsoft.com/.default'), '', '&'),
		'httpMethod' => 'POST',
		'httpHeader' => array('host: login.microsoftonline.com',
													'Content-Type: application/x-www-form-urlencoded',
													'Cookie: buid=0.AQYASG_it5IiFEqjVRrrhImuPRgFD1jTGCJMjrrTt_PN72QGAAA.AQABAAEAAAAGV_bv21oQQ4ROqh0_1-tAW_7_lkPgDNNQcc9ndJ6-VT_fKycsxUQA_fsiaenVHh0m1dZmFiOVou0VgVUcdSWQcKUXNWy0yeSTtMjrE4vBvIZsvOjiuXWYPgfnevpPNZAgAA; fpc=AlKOys_Nd-FDqTUucSXhED6Lv60HAQAAAEAZ29YOAAAA; x-ms-gateway-slice=estsfd; stsservicecookie=estsfd')
		);

	$responseData = execCurl($data);
	return $responseData['access_token'];
}
function getADUsers($key, $skipToken='') {
	if($key>0) {
		$skipToken = '&$skiptoken='.$skipToken;
	}

	$data = array(
		'url' => 'https://graph.windows.net/b7e26f48-2292-4a14-a355-1aeb8489ae3d/users?api-version=1.6'.$skipToken,
		'httpMethod' => 'GET',
		'httpHeader' => array("Authorization: ". getADToken())
	);
	$responseData = execCurl($data);
	return $responseData;
}
function createUser($userElement) {
	global $DB;

	$mail = !empty($userElement['mail']) ? $userElement['mail'] : $userElement['otherMails'][0];
	$username = !empty($mail) ? $mail : $userElement['extension_f356ba22a23b4c2fb35162e63d13246c_userDocumentNumber'];

	$user             = new StdClass();
	$user->auth       = 'manual';
	$user->confirmed  = 1;
	$user->mnethostid = 1;
	$user->email      = !empty($mail) ? $mail : 'mailtest@test.com';
	$user->username   = $username;
	$user->password   = md5($userElement['extension_f356ba22a23b4c2fb35162e63d13246c_userUOCode']);
	$user->lastname   = $userElement['surname'];
	$user->firstname  = $userElement['givenName'];
	$user->id         = $DB->insert_record('user', $user);

	$documentNumber = $userElement['extension_f356ba22a23b4c2fb35162e63d13246c_userDocumentNumber'];
	$employeeCode = $userElement['extension_f356ba22a23b4c2fb35162e63d13246c_userUOCode'];

	$userCreatedObj = $DB->get_record('user', array('id' => $user->id));

	$userCreatedObj->profile_field_DNI = $documentNumber;
	$userCreatedObj->profile_field_codigo = $employeeCode;
	//profile_save_data($userCreatedObj);
}

global $DB;

$key = 0;
$skipToken = '';
$usersValues = array();
$allUsers = array();

while(true) {
	if($key>1 && $skipToken=='') {
		break;
	}
	$allUsers[] = getADUsers($key, $skipToken);
	$needle = '$skiptoken=';
	$skipToken = substr($allUsers[$key]['odata.nextLink'], strpos($allUsers[$key]['odata.nextLink'], $needle) + strlen($needle));
	$key++;
}

$count = 0;

foreach($allUsers as $allUser) {
	foreach($allUser['value'] as $key=>$val) {
		$usersValues[$count] = $val;
		$count++;
	}
}

$usersValues = array_slice($usersValues,0,10);

foreach($usersValues as $key=>$user) {
	//consultar: filtrando si mail existe (?)
	$sqlDNI = "SELECT ud.userid
        	FROM {user_info_data} ud
       		WHERE ud.fieldid = 1 AND ud.data = :dni";
       $params = array('dni' => $user['extension_f356ba22a23b4c2fb35162e63d13246c_userDocumentNumber']);
	 $userId = $DB->get_field_sql($sqlDNI, $params);

	if(!empty($userId) || empty($user['extension_f356ba22a23b4c2fb35162e63d13246c_userUOCode'])) {
		continue;
	}
	createUser($user);
}