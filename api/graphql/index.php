<?php

chdir('../../');
require_once 'vendor/autoload.php';

use Vtiger\GraphQL;
use Vtiger\VtigerHelper;
use Vtiger\VtigerNotificationHelper;

require_once 'include/utils/utils.php';
require_once 'config.inc.php';
require_once 'includes/main/WebUI.php';
require_once 'vtlib/Vtiger/Mailer.php';
if (isset($_REQUEST['api'])) {
    global $application_unique_key, $adb;
    if ($_REQUEST['api'] == "graphql_version") {
        $response_data = ['success' => true, 'message' => 'v2.0'];
        echo json_encode($response_data);
        return;
    }elseif($_REQUEST['api'] == 'register'){
        $response =VtigerHelper::Register($_POST);
        if($response =='Duplicate User Exists'){
            $message  = ['success' => false, 'message' => $response];
        }else{
            $message  = ['success' => true, 'message' => 'You have been successfully Registered','userid'=>$response];
        }        
        echo json_encode($message);
        return;
    }elseif ($_REQUEST['api'] == 'oauth' || $_REQUEST['api'] == 'authorize') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        //If credentials are not passed, return error
        if (empty($username) || empty($password)) {
            $message  = ['success' => false, 'message' => 'username and password can not be empty'];
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode($message);
            return;
        }
        //Check credentials are valid
        $current_user = CRMEntity::getInstance('Users');
        $current_user->column_fields['user_name'] = $username;

        // If credentials are wrong, return
        if (!$current_user->doLogin($password)) {
            $response_data = ['success' => false, 'message' => 'Authentication invalid', 'function' => 'if-oauth'];
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode($response_data);
            return;
        }
        $current_user->id = $current_user->retrieve_user_id($username);
        $current_user->retrieveCurrentUserInfoFromFile($current_user->id);
	$roleid = $current_user->roleid;
        $allRoles = Settings_Roles_Record_Model::getAll();
        $rolename=$allRoles[$roleid]->get('rolename');
	$issued_at = time();
        $jwt_data = [
            'iat'  => $issued_at,
            'jti'  => base64_encode(generateRandomString(30)),
            'iss'  => '',
            'exp'  => $issued_at + 866400,
            'data' => [
                'userId'   => $current_user->id,
                'userName' => $username,
                'user_timezone' => $current_user->time_zone,
                'user_currency' => $current_user->currency_code,
                'user_currency_id' => fetchCurrency($current_user->id),
                'version_type' => 'Pro',
		'date_format' => $current_user->date_format,
		'role' => $rolename,
            ]
        ];
        $refresh_token = \Firebase\JWT\JWT::encode($jwt_data, $application_unique_key);
        if ($_REQUEST["api"] == "authorize") {
            global $site_URL;
            $user_info = $jwt_data['data'];
            $user_info['success'] =true;
            $user_info['token'] = $refresh_token; 
            $detailViewModel = \Vtiger_DetailView_Model::getInstance('Users', $current_user->id);
            $userModel = $detailViewModel->getRecord();
            $user_image = $userModel->getImageDetails();
            $user_profile_url = null; 
            if ($user_image) {
                if (isset($user_image[0]['url']) && !empty($user_image[0]['url'])) {
                    $user_profile_url = $user_image[0]['url'];
                }
            }          
            $user_info['fullname'] = $current_user->first_name.' '.$current_user->last_name;
            $user_info['user_profile_url'] = $user_profile_url;
            $user_info['user_currency_id'] = fetchCurrency($current_user->id);
            $user_info['version'] = 'v2.0'; 
            $notificationstatus=VtigerNotificationHelper::notificationstatus($current_user->id);
            $user_info['notificationstatus'] = $notificationstatus;
            echo json_encode($user_info);
            return;
        }
        $access_token = access_token($refresh_token, $application_unique_key);
        $response_data = ['success' => true, 'refresh_token' => $refresh_token, 'access_token' => $access_token, 'date_format' => $current_user->date_format];
        echo json_encode($response_data);
        return;
    } elseif ($_REQUEST['api'] == 'refresh_token') {
        $access_token = access_token($_POST['refresh_token'], $application_unique_key);
        $response_data = ['success' => true, 'access_token' => $access_token, 'date_format' => $current_user->date_format];
        echo json_encode($response_data);
        return;
    } elseif ($_REQUEST['api'] == 'graphql') {
        try {
            $requested_data = [];
            foreach ($_REQUEST as $key => $value) {
                $requested_data[$key] = $value;
            }
            $current_user = CRMEntity::getInstance('Users');
	        $token = $requested_data['token'];
            unset($requested_data['api']);
	        unset($requested_data['token']);
            $decoded = tokenAuthentication($token, $application_unique_key);
            $user_info = $decoded->data;
            $current_user->retrieveCurrentUserInfoFromFile($user_info->userId);

            $graphql =  new GraphQL(new VtigerHelper($adb, $current_user));
	        $queryType = $graphql->generateQueryType($requested_data);
            $mutationType = $graphql->generateMutationType($requested_data);
            $schema = $graphql->schema($queryType, $mutationType);
	        $graphql_response = $graphql->execute($schema, $requested_data);

            echo json_encode($graphql_response);
        } catch (Exception $e) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['success' => 'false', 'message' => $e->getMessage()]);
            die();
        }

        return;
    } elseif ($_REQUEST['api'] == 'location') {
        $requested_data = [];
        foreach ($_REQUEST as $key => $value) {
            $requested_data[$key] = $value;
        }
        $current_user = CRMEntity::getInstance('Users');
        $token = $requested_data['token'];
        $decoded = tokenAuthentication($token, $application_unique_key);
        $joHelper =  new VtigerHelper($adb, $current_user);
        $request_for_responce = $joHelper->returnLocationDetails($requested_data);
        echo json_encode($request_for_responce);
        return;
    }elseif($_REQUEST['api'] == 'notification'){
        $token = $_REQUEST['token'];
        $decoded = tokenAuthentication($token,$application_unique_key);
        $_REQUEST['userid']=$decoded->data->userId;
        $joNotifyHelper =  new VtigerNotificationHelper($adb,$current_user);
        $request_for_responce = $joNotifyHelper->Notification($_REQUEST);
        echo json_encode($request_for_responce);
        return;
    }elseif ($_REQUEST['api'] == 'logout') { 
        global $site_URL;
        $token = $_REQUEST['token'];
        $decoded = tokenAuthentication($token, $application_unique_key); 
        $response = VtigerNotificationHelper::DeletenotificationToken($decoded->data->userId);
        session_regenerate_id(true);
        Vtiger_Session::destroy(); 
        //Track the logout History
        $moduleModel = Users_Module_Model::getInstance('Users');
        $moduleModel->saveLogoutHistory();
        echo json_encode(['success' => 'true', 'message' => 'Logout successfully', 'siteurl'=>$site_URL.'index.php']);
        return;
    }else{
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['success' => 'false', 'message' => 'Unauthorized Url']);
        die();
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(['success' => 'false', 'message' => 'Invalid Url']);
    die();
}

function tokenAuthentication($token, $application_unique_key)
{
    try {
        $decoded = \Firebase\JWT\JWT::decode($token, $application_unique_key, array('HS256'));
        return $decoded;
    } catch (Exception $e) {
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['success' => 'false', 'message' => 'Authentication invalid', 'function' => 'tokenAuthentication']);
        die();
    }
}
//function to generate random string.
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function access_token($refresh_token, $application_unique_key)
{
    $issued_at = time();
    try {
        $decoded = \Firebase\JWT\JWT::decode($refresh_token, $application_unique_key, array('HS256'));
        $jwt_data = [
            'iat'  => $issued_at,
            'jti'  => base64_encode(generateRandomString(30)),
            'iss'  => '',
            'exp'  => $issued_at + 86400,
            'data' => [
                'userId'   => $decoded->data->userId,
                'userName' => $decoded->data->userName,
                'crm_timezone' => DateTimeField::getDBTimeZone(),
                'user_timezone' => $decoded->data->user_timezone,
                'user_currency' => $decoded->data->user_currency,
                'user_currency_id' => fetchCurrency($decoded->data->userId),
                'version_type' => 'Pro',
                'date_format' => $decoded->data->date_format,
            ]
        ];
        $access_token = \Firebase\JWT\JWT::encode($jwt_data, $application_unique_key);
    } catch (Exception $e) {
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['success' => 'false', 'message' => 'Authentication invalid', 'function' => 'access_token']);
        die();
    }
    return  $access_token;
}
