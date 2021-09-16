<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Users_UpdateUseractive_Action extends Vtiger_Action_Controller {

    function loginRequired() {
		return false;
	}	

	function checkPermission(Vtiger_Request $request) {
		return true;
	} 

	function process(Vtiger_Request $request) {  

		global $current_user , $site_URL;
		$current_user->id =1;
	
		$user_name = $request->get('user_name');
		$password = $request->getRaw('user_password');
		$id =$request->getRaw('record');
		$recordModel = Vtiger_Record_Model::getInstanceById($id, 'Users');
		$recordModel->set("status", 'Active');
		$recordModel->set('id', $id);
        $recordModel->set('mode', 'edit');
        $recordModel->save();  
        $email = $recordModel->get('email1');   

		#send mail
		require_once 'vtlib/Vtiger/Mailer.php';
		$mail = new Vtiger_Mailer(); 
		$mail->IsHTML();
		$mail->Body = '<p>Dear '.$user_name.',</p>  <center><table style="padding-top:32px;background-color:#ffffff" cellspacing="0" cellpadding="0"><tbody>
		<tr><td>
		<table cellspacing="0" cellpadding="0"><tbody>
		<tr><td style="max-width:560px;padding:24px 24px 32px;background-color:#fafafa;border:1px solid #e0e0e0;border-radius:2px">
		<img style="padding:0 24px 16px 0;float:left" alt="Error Icon" src="http://212.71.252.209/tcracrm/layouts/v7/resources/Images/tcra-ccc.png" data-image-whitelisted="" class="CToWUd" width="72" height="72">
		<table style="min-width:272px;padding-top:8px"><tbody>
		<tr><td style="padding-top:20px;color:#757575;font-size:16px;font-weight:normal;text-align:left"><p><b>Credentials :</b></p><p> Username : '.$user_name.'</p><p>Password : '.$password.'</p>
		</td></tr>
		<tr><td style="padding-top:24px;color:#4285f4;font-size:14px;font-weight:bold;text-align:left">
		<a style="text-decoration:none" href="'.$site_URL.'" target="_blank" data-saferedirecturl="">Click here to Login</a>
		</td></tr>
		</tbody></table>
		</td></tr>
		</tbody></table>
		</td></tr>
		</tbody></table></center>';
		$mail->Subject = 'Welcome to our Team !';
		$mail->AddAddress($email);
		$status = $mail->Send(true); 
		 
		 $user = CRMEntity::getInstance('Users');
		$user->column_fields['user_name'] = $user_name;

		if ($user->doLogin($password)) {
			session_regenerate_id(true); // to overcome session id reuse.

			$userid = $user->retrieve_user_id($user_name);
			Vtiger_Session::set('AUTHUSERID', $userid);

			$_SESSION['authenticated_user_id'] = $userid;
			$_SESSION['app_unique_key'] = vglobal('application_unique_key');
			$_SESSION['authenticated_user_language'] = vglobal('default_language');
			//Enabled session variable for KCFINDER 
			$_SESSION['KCFINDER'] = array(); 
			$_SESSION['KCFINDER']['disabled'] = false; 
			$_SESSION['KCFINDER']['uploadURL'] = "test/upload"; 
			$_SESSION['KCFINDER']['uploadDir'] = "../test/upload";
			$deniedExts = implode(" ", vglobal('upload_badext'));
			$_SESSION['KCFINDER']['deniedExts'] = $deniedExts;
			// End 
			//Track the login History
			$moduleModel = Users_Module_Model::getInstance('Users');
			$moduleModel->saveLoginHistory($user->column_fields['user_name']);
			//End	
			if(isset($_SESSION['return_params'])){
				$return_params = $_SESSION['return_params'];
			} 
			header ('Location: index.php?module=Users&parent=Settings&view=SystemSetup');
			exit();
		} else {
			header ('Location: index.php?module=Users&parent=Settings&view=Login');
			exit;
		}
			
	}

}
 

		
	

