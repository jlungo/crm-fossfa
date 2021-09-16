<?php

namespace Vtiger;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class VtigerNotificationHelper
{
    private $db;

    private $user;

    private $module_fields_info;

    private $records_per_page = 20;

    /**
     * JoHelper constructor.
     *
     * @param $adb
     * @param $user
     */
    public function __construct($adb, $user)
    {
        $this->db = $adb;

        $this->user = $user;
    }

    public function Notification($request_data)
    {  
        
        if($request_data['mode'] =="Read" || $request_data['mode'] =="ReadAll" || $request_data['action']=='list'){ 
            $notificationstatus=self::notificationstatus();
            if($notificationstatus == 'disabled'){
                return array('success' => false, 'message' => 'Notification is off mode');
            }
        }
        
        if($request_data['mode']=='Read'){
            $data =$this->Readnotification($request_data);
        }elseif($request_data['mode']=='ReadAll'){  
            $data =$this->ReadAllnotification($request_data);
        }elseif($request_data['action'] == 'notifystatus') {
            $data =$this->Notificationenable($request_data);
        }elseif($request_data['action']=='list'){  
            $data =$this->getAllnotify($request_data['userid']);
        }elseif($request_data['action']=='Register'){  
            $data =$this->Registernotification($request_data);
        } 
        return $data;
    }

    public function getAllnotify($userid){
        
        $user_notifications = $this->getNotifications($userid);
        $splitnotices = [];
        for ($i=0; $i <count($user_notifications); $i++) { 
            $describtion =$this->smackdescribtion($user_notifications[$i]);
            $user_notify[$i]=array(
                "notification_id"=> $user_notifications[$i]['notificationid'],
                "notifier_id"=> $user_notifications[$i]['notifier_id'],
                "notification_content"=>$describtion,
                "module_name" => $user_notifications[$i]['module_name'],
                "recordid"=> $user_notifications[$i]['entity_id'],
                "is_seen" => $user_notifications[$i]['is_seen']
            );
        }

        foreach($user_notify as $key=>$value){
            $group = $value['is_seen'];
            if(!isset($splitnotices[$group])) $splitnotices[$group ] = [];

            $splitnotices[$group][] = $value;
        } 
    
        $notificationsection=array(
               array("section_name"=> "Unread","notifications"=>$splitnotices[0]) ,
               array("section_name"=> "Read","notifications"=>$splitnotices[1])
        );      
        $data = array('notification_sections' => $notificationsection);
        $allnotice =array('success' => true, 'data' => $data);
        return $allnotice;   

    }

    public function Readnotification($request_data){
        global $adb; 
        $sql = 'UPDATE `vtiger_smacknotification` SET `is_seen`=1 WHERE `notificationid`=?';
	$value_array = array($request_data['notification_id']);

	if(\Vtiger_Utils::CheckTable('vtiger_smacknotification')) {
		$result = $adb->pquery($sql, $value_array);
	        return array('success' => true, 'message' => 'Updated successfully');
	} else {
		return array('success' => false, 'message' => 'Table does not exist');
	}
    }

    public function ReadAllnotification($request_data) {  
        global $adb, $current_user;        
        $current_user_id = $request_data['userid'];  
	$sql = 'UPDATE `vtiger_smacknotification` SET `is_seen`=1 WHERE `notifier_id`=? ';

	if(\Vtiger_Utils::CheckTable('vtiger_smacknotification')) {
            $adb->pquery($sql,array($current_user_id));
            return array('success' => true, 'message' => 'Updated successfully');
        } else {
	    return array('success' => false, 'message' => 'Table does not exist');
        }
    }  

    public function Notificationenable($request_data){
        global $adb, $current_user; 
        $user_id = $request_data['userid'];       
        $global_notification_settings = $request_data['notificationenable'];

        if($global_notification_settings=='1'){
            $updatevalue ='enabled';
        }else{
            $updatevalue ='disabled';
	}

	if(\Vtiger_Utils::CheckTable('vtiger_smacknotificationsetting')) {
            $adb->pquery("UPDATE `vtiger_smacknotificationsetting` SET `globalenable`=?  WHERE `id`=1 ",array($updatevalue)); 
            return array('success' => true ,'message' =>'Settings are saved successfully.');
        } else {
            return array('success' => false, 'message' => 'Table does not exist');
        }
    }

    public function Registernotification($request_data) {
        global $adb;
	$exists=self::checknotifytokenexits($request_data['userid']);
	if(\Vtiger_Utils::CheckTable('vtiger_notifyauthtoken')) {
            if(!empty($exists)){
            	$sql = "UPDATE `vtiger_notifyauthtoken` SET `token`=?,`devicetype`=? WHERE `userid`=?";
            	$adb->pquery($sql,array($request_data['notifytoken'],$request_data['devicetype'],$request_data['userid']));   
            } else {
            	$sql = "insert into vtiger_notifyauthtoken(userid,token,devicetype) values (?,?,?)";
            	$adb->pquery($sql,array($request_data['userid'],$request_data['notifytoken'],$request_data['devicetype']));
            }
	    return array('success' => true, 'message' => 'Updated successfully');
	} else {
	    return array('success' => false, 'message' => 'Table does not exist');
	}
    }

    public function checknotifytokenexits($userid){
	global $adb;
	if(\Vtiger_Utils::CheckTable('vtiger_notifyauthtoken')) {
            $select_query = "SELECT * FROM `vtiger_notifyauthtoken` WHERE `userid` = ?";
	    $fetch_values = $adb->pquery($select_query, array($userid));
	    if($adb->num_rows($fetch_values) > 0) {
		$getuserdetails = $adb->fetchByAssoc($fetch_values);
	    } else {
		$getuserdetails = array();
	    }
	    return $getuserdetails;
	} else {
	     return array('success' => false, 'message' => 'Table does not exist');
	}
    }

    public function DeletenotificationToken($userid){
	global $adb;
	if(\Vtiger_Utils::CheckTable('vtiger_notifyauthtoken')) {
            $sql = "DELETE FROM `vtiger_notifyauthtoken` WHERE userid=?";
            $adb->pquery($sql,array($userid));
	    return array('success' => true, 'message' => 'Updated successfully.');
	} else {
	    return array('success' => false, 'message' => 'Table does not exist');
	}
    }

    public function getNotifications($userid){
        global $adb, $current_user;
	$current_user_id = $request_data['userid']; 
	$select_array = array($userid);
	$Allnotification = [];

	if(\Vtiger_Utils::CheckTable('vtiger_smacknotification')) {
            $select_query = "SELECT * FROM vtiger_smacknotification WHERE notifier_id = ?";
            $fetch_values = $adb->pquery($select_query, $select_array);

	    if($adb->num_rows($fetch_values) > 0) {
            	while($fetch_array = $adb->fetch_array($fetch_values)){
	            array_push($Allnotification, $fetch_array);
            	} 
	    }
	} else {
	    return array('success' => false, 'message' => 'Table does not exist');
	}
        return $Allnotification;
    }

    public function notificationstatus(){
	global $adb;   
	if(\Vtiger_Utils::CheckTable('vtiger_smacknotificationsetting')) {
            $select_query = "SELECT globalenable FROM `vtiger_smacknotificationsetting` WHERE `id` = 1";
	    $fetch_values = $adb->pquery($select_query);
	    if($adb->num_rows($fetch_values) > 0) {
	    	$notificationstatus = $adb->fetchByAssoc($fetch_values);
	    } else {
	    	$notificationstatus['globalenable'] = false;
	    }
	} else {
	    return array('success' => false, 'message' => 'Table does not exist');
	}
        return $notificationstatus['globalenable'];
    }

    public function smackdescribtion($data) {
        $createdname=ucwords(self::getuserdetails($data['user_id']));

        if(empty($data['module_name']))
            $data['module_name']=$data['module'];

        if($data['action_type'] =='Created' || $data['status'] =='Created' ){
            $describtion = $createdname.' '.$data['action_type'].' a '.$data['module_name'];
        }elseif ($data['action_type'] == 'Created and Assigned' || $data['status'] =='Created and Assigned') {
            $describtion = $createdname.' Created and Assigned a '.$data['module_name']. ' to You';
        }elseif ($data['action_type'] == 'Updated' || $data['status'] == 'Updated') {
            $describtion =$createdname.' '.$data['action_type'] .' the '.$data['module_name'];
        }elseif ($data['action_type'] == 'Assignee Changed' || $data['status'] == 'Assignee Changed') {
            if(!empty($data['newvalue'])){
                $oldvalue=ucwords(self::getuserdetails($data['oldvalue']));
                $newvalue=ucwords(self::getuserdetails($data['newvalue']));
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' from '.$oldvalue.' to '. $newvalue;

            }else{
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' to you ';
            }
        }
        return  $describtion;
    }

    public function getuserdetails($id){
        $moduleModel = \Vtiger_Module_Model::getInstance('Users');
        $recordModel = \Vtiger_Record_Model::getInstanceById($id, $moduleModel);
        return $recordModel->get('user_name');
    }
}
