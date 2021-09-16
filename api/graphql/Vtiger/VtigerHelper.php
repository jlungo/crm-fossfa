<?php

namespace Vtiger;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class VtigerHelper
{
    private $db;
    private $user;
    private $module_fields_info;
    private $records_per_page = 20;
    public function __construct($adb, $user)
    {
        $this->db = $adb;
        $this->user = $user;
    }

    public function resolve($requested_data, $args)
    {
        $data = [];
        if ($requested_data['action'] == 'get_modules') {
            $data = $this->getJoModules();
        } else if ($requested_data['action'] == 'menu') {
            $data = $this->getUserMenu();
        } else if ($requested_data['action'] == 'global_search') {
            $data = $this->globalSearch($args);
            $data = ['results' => $data];
        } else if ($requested_data['action'] == 'calendar_view') {
            $data = $this->getCalendarInfo($args);
            $data = ['events' => $data];
        } else if ($requested_data['action'] == 'describe') {
            $data = $this->getModuleFields($args['module']);
        } else if ($requested_data['action'] == 'filters') {
            $data = $this->getUserFilters($args);
        } else if ($requested_data['action'] == 'filter_columns') {
            $data = $this->getFilterColumns($args);
        } else if ($requested_data['action'] == 'get_module_relations') {
            $data = $this->returnRelatedModules($args['module']);
        } else if ($requested_data['action'] == 'get_related_records') {
            $data = $this->returnRelatedRecords($args);
        } else if ($requested_data['action'] == 'widget_info') {
            $data = $this->getWidgetData($args);
            $data = ['data' => $data];
        } else if ($requested_data['action'] == 'get_record') {
            $data = $this->retrieve($args['module'], $args['id']);
        } else if ($requested_data['action'] == 'list') {
	    $data = $this->listRecords($requested_data, $args);
        } else if ($requested_data['action'] == 'get_users') {
            $data = $this->returnUsers($requested_data, $args);
        } else if ($requested_data['action'] == 'get_forecast') {
            $data = $this->returnForecast($requested_data, $args);
        } else if ($requested_data['action'] == 'get_monthly_counts') {
            $data = $this->returnMonthlyCounts($requested_data, $args);
        }else if ($requested_data['action'] == 'tax') { 
            $data = $this->gettax($requested_data, $args); 

        }

        return $data;
    }

    public function gettax($requested_data, $args) {

        $productAndServicesTaxList = \Inventory_TaxRecord_Model::getProductTaxes();
        $count=count($productAndServicesTaxList);  
        for ($i=1; $i <=$count ; $i++) {  
            $taxes = [];
            $taxes['id']        =$productAndServicesTaxList[$i]->get('taxid');
            $taxes['taxname']   =$productAndServicesTaxList[$i]->get('taxname');
            $taxes['taxlabel']  =$productAndServicesTaxList[$i]->get('taxlabel');
            $taxes['percentage']=$productAndServicesTaxList[$i]->get('percentage');
            $taxes['method']    =$productAndServicesTaxList[$i]->get('method');
            $taxes['type']      =$productAndServicesTaxList[$i]->get('type');
            $taxes['compoundon']= html_entity_decode($productAndServicesTaxList[$i]->get('compoundon'));
            $reglist= json_decode( html_entity_decode($productAndServicesTaxList[$i]->get('regions')));
            $count_reg=count($reglist);
            for ($k=0; $k < $count_reg; $k++) { 
                $listcount=count($reglist[$k]->list);
                for ($j=0; $j < $listcount; $j++) { 
                    $regval=array();
                    $regval['list']=$reglist[$k]->list[$j];
                    $regval['value']=$reglist[$k]->value;
                    $list_region[]=$regval;
                }
            }          
            $taxes['regions']   =json_encode($list_region);
            $related_modules[] = $taxes;            
        } 
        $moreRecords = false;
        $response = [
            'records' => $related_modules,
        ];

        return $response;  
    }

    public function returnForecast($requested_data, $args)
    {
        $month = $args['month'];
        $year = $args['year'];

        $date = $year . '-' . $month . '-01';
        $date2 = $year . '-' . $month . '-31';
        $query = "SELECT potentialname,sales_stage,closingdate,amount,potentialid FROM vtiger_potential WHERE closingdate >= '" . $date . "' AND closingdate <= '" . $date2 . "' limit 100";
        $result = $this->db->pquery($query);
        $opportunities = [];
        while ($myrow = $this->db->fetch_array($result)) {
            $row = [];
            $row['oppurtinities_name'] = $myrow['potentialname'];
            $row['sales_stage'] = $myrow['sales_stage'];
            $row['closingdate'] = $myrow['closingdate'];
            $row['amount'] = $myrow['amount'];
            $row['id'] = $myrow['potentialid'];
            array_push($opportunities, $row);
        }
        $oppor = [];
        $oppor['opportunities'] = $opportunities;
        $final_array = [];
        $final_array["data"] = $oppor;
        print_r(json_encode($final_array));
        die();
    }

    public function retrieve($module, $record_id)
    {
	if(!isRecordExists($record_id))
	    return; //return if recordid is deleted or does not exist.

        $module_name = ucfirst($module);
        include_once 'include/Webservices/DescribeObject.php';
        $this->module_fields_info = \vtws_describe($module_name, $this->user);

        // TODO Check permission using vtws_retrieve function
        $moduleModel = \Vtiger_Module_Model::getInstance($module_name);
        $recordModel = \Vtiger_Record_Model::getInstanceById($record_id, $moduleModel);
        $unresolved_data = $recordModel->getData();
        if ($module == 'PurchaseOrder' || $module == 'Invoice' || $module == 'SalesOrder' || $module == 'Quotes'){
             $relatedProducts = $recordModel->getProducts();
        }
        if ($module == 'Products') {
            $recordTaxDetails = $recordModel->getTaxClassDetails();
            foreach ($recordTaxDetails as $tax_details) {
                if ($tax_details['check_value'] == 1) {
                    $unresolved_data[$tax_details['taxname']] = $tax_details['percentage'];
                }
            }
        } elseif ($module == 'Contacts') {
            global $site_URL;
            $user_image = $recordModel->getImageDetails();
            $user_profile_url = '';
            if ($user_image) {
                if (isset($user_image[0]['url']) && !empty($user_image[0]['url'])) {
                    $user_profile_url = $user_image[0]['url'];
                }
            }
            $unresolved_data['imageurl'] = $user_profile_url;
        }
        $data = $this->resolveRecordValues($unresolved_data, $moduleModel);
        if(!empty($relatedProducts)){          
            for ($i=1; $i <=count($relatedProducts); $i++) {
                $data['productid'.$i]=$relatedProducts[$i]['productName'.$i];
                $data['productid'.$i.'_id']=$relatedProducts[$i]['hdnProductId'.$i];
                $data['quantity'.$i]=$relatedProducts[$i]['qty'.$i];
                $data['listprice'.$i]=$relatedProducts[$i]['listPrice'.$i];
                $data['comment'.$i]=$relatedProducts[$i]['comment'.$i];                
            }
        } 
        return $data;
    }

    public function returnLocationDetails($request)
    {
        include_once 'include/Webservices/Query.php';

        $code = $request['code'];
        $city = $request['city'];
        $state = $request['state'];
        $request_array = array();

        if ($request['module'] == 'Leads') {
            $code_query = $city_query = $state_query = $query_concat = '';
            $query_concat = "SELECT concat(details.firstname,' ',details.lastname) as label,concat(address.lane,' ',address.city,' ',address.state,' ',address.code,' ',address.country) as address,address.code,address.city,address.state FROM vtiger_leaddetails details JOIN vtiger_crmentity crm ON crm.crmid = details.leadid JOIN vtiger_leadaddress address ON address.leadaddressid = crm.crmid WHERE crm.deleted = 0 AND";
            $code_query = $city_query = $state_query = $query_concat;
            if (!empty($code)) {
                $code_query .= " code =? LIMIT 30";
                $mailingzip = $this->db->pquery($code_query, array($code));
                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($city)) {
                $city_query .= " city =? LIMIT 30";

                $mailingzip = $this->db->pquery($city_query, array($city));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($state)) {
                $state_query .= " state =? LIMIT 30";

                $mailingzip = $this->db->pquery($state_query, array($state));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }

            $transform_array = array_unique($request_array, SORT_REGULAR);
            $temp_array = [];

            $i = 0;
            foreach ($transform_array as $key => $value) {
                $temp_array[$i] = ['label' => $value['label'], 'address' => $value['address']];
                $i = $i + 1;
            }

            $responcearray = array('data' => $temp_array);
            return $responcearray;
        } elseif ($request['module'] == 'Contacts') {  // for Contacts 
            $mailingzip_query = $mailingcode_query = $mailingstate_query = $query_concat = '';

            $query_concat = "SELECT concat(details.firstname,' ', details.lastname) as label, concat(address.mailingstreet, ' ', address.mailingcity,' ', address.mailingstate, ' ', address.mailingzip, ' ', address.mailingcountry) as address,address.mailingstreet,address.mailingzip,address.mailingcity,address.mailingstate,address.mailingcountry FROM vtiger_contactdetails details JOIN vtiger_crmentity crm ON crm.crmid = details.contactid JOIN vtiger_contactaddress address ON address.contactaddressid = crm.crmid WHERE crm.deleted = 0 AND";

            $mailingzip_query = $mailingcode_query = $mailingstate_query = $query_concat;

            if (!empty($code)) {
                $mailingzip_query .= " mailingzip =? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingzip_query, array($code));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($city)) {
                $mailingcode_query .= " mailingcity=? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingcode_query, array($city));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            if (!empty($state)) {
                $mailingstate_query .= " mailingstate=? LIMIT 30";

                $mailingzip = $this->db->pquery($mailingstate_query, array($state));

                while ($module_info = $this->db->fetchByAssoc($mailingzip)) {

                    $request_array[] = $module_info;
                }
            }
            $transform_array = array_unique($request_array, SORT_REGULAR);
            $temp_array = [];
            $i = 0;
            foreach ($transform_array as $key => $value) {
                $temp_array[$i] = ['label' => $value['label'], 'address' => $value['address']];
                $i = $i + 1;
            }
            $responcearray = array('data' => $temp_array);
            return $responcearray;
        }
    }

    public function returnUsers($requested_data, $args)
    {
        global $current_user;
        $current_user = $this->user;
        $userModal = \Users_Record_Model::getCurrentUserModel();
        $users_info = $userModal->getAccessibleUsers();
        $groups_info = $userModal->getAccessibleGroups();

        $response = array();
        foreach ($users_info as $user_id => $user_name) {
            $response[] = ['id' => $user_id, 'label' => $user_name, 'is_user' => true];
        }

        if ($args['groups'] === true) {
            foreach ($groups_info as $group_id => $group_name) {
                $response[] = ['id' => $group_id, 'label' => $group_name, 'is_user' => false];
            }
        }
        return ['response' => $response];
    }

    public function listRecords($requested_data, $args)
    {
        global $current_user;

        $current_user = $this->user;

        $module_name = ucfirst($args['module']);
        $moduleModel = \Vtiger_Module_Model::getInstance($module_name);
        $headerFieldModels = $moduleModel->getHeaderViewFieldsList();

        $orderBy = $args['order_by'];
        $sortOrder = $args['sort_by'];
        $filterId = $args['filter_id'];

        $headerFields = array();
        $fields = array();

        $nameFields = $moduleModel->getNameFields();
        if (is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
        } else if (is_array($nameFields)) {
            foreach ($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        foreach ($headerFieldModels as $fieldName => $fieldModel) {
            $headerFields[] = $fieldName;
            $fields[] = array('name' => $fieldName, 'label' => $fieldModel->get('label'), 'fieldType' => $fieldModel->getFieldDataType());
        }

        $listViewModel = \Vtiger_ListView_Model::getInstance($module_name, $filterId, $headerFields = array());
        if (!empty($sortOrder)) {
            $listViewModel->set('orderby', $orderBy);
            $listViewModel->set('sortorder', $sortOrder);
        }

        if (!empty($args['search_key']) && !empty($args['search_value'])) {
            $listViewModel->set('search_value', $args['search_value']);
            $listViewModel->set('search_key', $args['search_key']);
            $listViewModel->set('operator', 'c');
        }

        $pagingModel = new \Vtiger_Paging_Model();
        if (isset($args['limit']) && !empty($args['limit'])) {
            $pageLimit = $args['limit'];
        } else {
            $pageLimit = $pagingModel->getPageLimit();
        }
        $pagingModel->set('page', $args['page']);
        $pagingModel->set('limit', $pageLimit + 1);

        $listViewEntries = $listViewModel->getListViewEntries($pagingModel);

        $customView = new \CustomView($module_name);
        if (empty($filterId)) {
            $filterId = $customView->getViewId($module_name);
        }

        if ($listViewEntries) {
            foreach ($listViewEntries as $index => $listViewEntryModel) {
                $data = $listViewEntryModel->getData();
                $record = array('id' => $listViewEntryModel->getId());
                foreach ($data as $i => $value) {
                    if (is_string($i)) {
                        $record[$i] = decode_html($value);
                    }
		}
		if($module_name == 'HelpDesk'){
                    $moduleModel = \Vtiger_Module_Model::getInstance($module_name);
                    $recordModel = \Vtiger_Record_Model::getInstanceById($record['id'], $moduleModel);
                    $record = $recordModel->getData();
                }
                $records[] = $record;
            }
        }

        $moreRecords = false;
        if (count($listViewEntries) > $pageLimit) {
            $moreRecords = true;
            array_pop($records);
        }

        $response = [
            'records' => $records,
            'headers' => $fields,
            'selectedFilter' => $filterId,
            'nameFields' => $nameFields,
            'moreRecords' => $moreRecords,
            'orderBy' => $orderBy,
            'sortOrder' => $sortOrder,
            'page' => $args['page']
        ];

        return $response;
    }

    public function syncRecord($args, $module, $id = null)
    {
        global $current_user;
        $current_user = $this->user;

        if (empty($args)) {
            throw new \Exception('No post data');
        }

        $module_name = ucfirst($module);
	if($module_name == 'HelpDesk'){
            $args['ticketstatus'] ='Open';
            $args['ticketpriorities'] ='High';
            $args['assigned_user_id'] =$current_user->id;
        }
	if (empty($id)) {
            $recordModel = \Vtiger_Record_Model::getCleanInstance($module_name);
        } else {
            $record_exists = $this->checkRecordExists($id);
            if (!$record_exists) {
                throw new \Exception('Record not exists');
            }
            $recordModel = \Vtiger_Record_Model::getInstanceById($id, $module_name);
        }
        $moduleModel = \Vtiger_Module_Model::getInstance($module_name);
        $fieldModelList = $moduleModel->getFields();

        foreach ($fieldModelList as $fieldName => $fieldModel) {
	    if (isset($args[$fieldName])) {
		$fieldValue = $args[$fieldName];
		$recordModel->set($fieldName, $fieldValue);
            }
        }
        if (!empty($id)) {
            $recordModel->set('id', $id);
            $recordModel->set('mode', 'edit');
            $recordModel->save();
        } else {
            $recordModel->save();
        }
        if($module_name == 'Invoice' || $module_name == 'Quotes' || $module_name == 'SalesOrder' || $module_name == 'PurchaseOrder'){
            $this->addlineitem($recordModel->entity->column_fields,$args);
        }
        return $recordModel->getData();
    }

    public function deleteRecord($module, $record_id)
    {
        global $current_user;
        $current_user = $this->user;
        $Checkpermission=\Users_Privileges_Model::isPermitted($module, 'Delete', $record_id);
        if(!$Checkpermission) { 
                return ['success' => false, 'message' => vtranslate('LBL_PERMISSION_DENIED')];
        }else{ 
            try {
                $module_name = ucfirst($module);
                $recordModel = \Vtiger_Record_Model::getInstanceById($record_id, $module_name);

                $recordModel->delete();
                return ['success' => true, 'id' => $record_id];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                if (empty($message)) {
                    $message = 'Something went wrong';
                }
                return ['success' => false, 'message' => $message];
            }
        } 
    }

    public function checkRecordExists($id)
    {
        $checkRecord = $this->db->pquery('select crmid from vtiger_crmentity where crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecord, 0, 'crmid');
        if (empty($crm_id)) {
            return false;
        }
        $checkRecordDeleted = $this->db->pquery('select crmid from vtiger_crmentity where deleted = 0 and crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecordDeleted, 0, 'crmid');
        if (empty($crm_id)) {
            return false;
        }
        return true;
    }

    public function returnRelatedModules($module)
    {
        global $current_user;
        $current_user = $this->user;
        $module_name = ucfirst($module);
        $moduleModel = \Vtiger_Module_Model::getInstance($module_name);
        $relations = $moduleModel->getRelations();
        foreach ($relations as $relation) {
            $relation_info['module_name'] = $relation->get('relatedModuleName');
            $relation_info['label'] = $relation->get('label');
            $relation_info['tab_id'] = $relation->get('related_tabid');

            $related_modules[] = $relation_info;
        }
        return ['relations' => $related_modules];
    }

    public function returnRelatedRecords($args)
    {
        include_once 'include/Webservices/Query.php';
        global $current_user;
        $current_user = $this->user;

        $record_id = $args['id'];
        $related_module = ucfirst($args['related_module']);
        $currentPage = $args['page'];
        if (!empty($currentPage)) {
            $currentPage = $currentPage - 1;
        }

        if (empty($record_id)) {
            throw new \Exception('Record Id not passed');
        }

        $currentModule = ucfirst($args['module']);

        $functionHandler = $this->getRelatedFunctionHandler($currentModule, $related_module);

        if ($functionHandler) {
            $sourceFocus = \CRMEntity::getInstance($currentModule);
	      $moduleModel = \Vtiger_Module_Model::getInstance($currentModule);
             $nameFields = $moduleModel->getNameFields();

            if($args['related_module'] == 'ModComments'){ 
                $pagingModel = new \Vtiger_Paging_Model();
                $pagingModel->set('page', $currentPage); 
               
                $recentComments = \ModComments_Record_Model::getRecentComments($record_id, $pagingModel);
                $listrecentcomment =array();
                $relatedmoduleModel = \Vtiger_Module_Model::getInstance($related_module);
                $fieldModelList = $relatedmoduleModel->getFields();

                for ($i=0; $i <count($recentComments) ; $i++) { 
                    foreach ($fieldModelList as $key => $value) {
                        $makecommentarray[$key]=$recentComments[$i]->get($key);
		    }
		    $makecommentarray['id'] =$recentComments[$i]->get('modcommentsid');
		    $listrecentcomment[] =$makecommentarray;
		    if(!empty($listrecentcomment[$i]['userid'])){
                        $getUsermodule = \Vtiger_Module_Model::getInstance('Users');
                        $getuserrecord = \Vtiger_Record_Model::getInstanceById($listrecentcomment[$i]['userid'], $getUsermodule);
                        $userrecord = $getuserrecord->getData();
                        $listrecentcomment[$i]['userid'] =$userrecord['user_name'];
                   }
                }
                
                return ['related_records' => $listrecentcomment, 'page' => $currentPage, "nameFields" => $nameFields, "moreRecords" => $moreRecords];
            }
	    $relationResult = call_user_func_array(array($sourceFocus, $functionHandler), array($record_id, getTabid($currentModule), getTabid($related_module)));
            $query = $relationResult['query'];

            //$moduleModel = \Vtiger_Module_Model::getInstance($currentModule);
            ////$nameFields = $moduleModel->getNameFields();

            $querySEtype = "vtiger_crmentity.setype as setype";
            if ($related_module == 'Calendar') {
                $querySEtype = "vtiger_activity.activitytype as setype";
            }

            $query = sprintf("SELECT vtiger_crmentity.crmid, $querySEtype %s", substr($query, stripos($query, 'FROM')));
            $queryResult = $this->db->query($query);

            // Gather resolved record id's
            $relatedRecords = array();
            while ($row = $this->db->fetch_array($queryResult)) {
                $targetSEtype = $row['setype'];
                if ($related_module == 'Calendar') {
                    if ($row['setype'] != 'Task' && $row['setype'] != 'Emails') {
                        $targetSEtype = 'Events';
                    } else {
                        $targetSEtype = $related_module;
                    }
                }
                $moduleWSId = $this->getModuleWSId($targetSEtype);
                $relatedRecords[] = sprintf("%sx%s", $moduleWSId, $row['crmid']);
            }

            $FETCH_LIMIT = 0;
            $queryResult = null;
            if (count($relatedRecords) > 0) {
                // Perform query to get record information with grouping
                $ws_query = sprintf("SELECT * FROM %s WHERE id IN ('%s')", $related_module, implode("','", $relatedRecords));
                $FETCH_LIMIT = $this->records_per_page;
                $startLimit = $currentPage * $FETCH_LIMIT;

                $queryWithLimit = sprintf("%s LIMIT %u,%u;", $ws_query, $startLimit, ($FETCH_LIMIT + 1));
                $queryResult = \vtws_query($queryWithLimit, $current_user);
            }

            if (count($queryResult) > 0) {
                // Resolve the ID
                // TODO move the resolve to the GraphQL
                foreach ($queryResult as $key => $single_entity) {
                    list($entity_tab_id, $entity_id) = explode('x', $single_entity['id']);
                    $queryResult[$key]['id'] = $entity_id;
                }
            }

            $moreRecords = false;
            if ((count($queryResult) == $FETCH_LIMIT) && count($queryResult) != 0) {
                $moreRecords = true;
            }

            return ['related_records' => $queryResult, 'page' => $currentPage, "nameFields" => $nameFields, "moreRecords" => $moreRecords];
        }
        throw new \Exception('No handler for given module');
    }

    public function getRelatedFunctionHandler($module, $related_module)
    {
        $relationResult = $this->db->pquery("SELECT name FROM vtiger_relatedlists WHERE tabid = ? and related_tabid = ? and presence = 0", array(getTabid($module), getTabid($related_module)));
        $functionName = false;
        if ($this->db->num_rows($relationResult)) $functionName = $this->db->query_result($relationResult, 0, 'name');

        return $functionName;
    }
    
    public function getModuleWSId($module)
    {
        $result = $this->db->pquery("SELECT id FROM vtiger_ws_entity WHERE name = ?", array($module));
        if ($result && $this->db->num_rows($result)) {
            return $this->db->query_result($result, 0, 'id');
        }
        return false;
    }

    public function generateFields($args)
    {
        if ($args['action'] == 'get_modules') {
            return [
                'name' => $args['module'] . 'Field',
                'description' => "CRM {$args['module']} fields",
                'type' => 'list',
                'action' => 'get_modules',
                'fields' => [
                    'id' => [
                        'type' => Type::id(),
                    ],
                    'name' => [
                        'type' => Type::string(),
                    ],
                    'isEntity' => [
                        'type' => Type::id(),
                    ],
                    'label' => [
                        'type' => Type::string(),
                    ],
                    'singular' => [
                        'type' => Type::string()
                    ],
                ]
            ];
        } else if ($args['action'] == 'menu') {
            $more_menu = new ObjectType([
                'name' => 'MainMenuInformation',
                'description' => 'Main Menu information',
                'fields' => [
                    'tabid' => Type::id(),
                    'name' => Type::string(),
		    'label' => Type::string(),
		    'image_url' => Type::string(),
                ]
            ]);

            $module_info = new ObjectType([
                'name' => 'ModuleInformation',
                'description' => 'Module Information',
                'fields' => [
                    'tabid' => Type::int(),
                    'name' => Type::string(),
		    'label' => Type::string(),
		    'image_url' => Type::string(),
                ]
            ]);

            $section_module = new ObjectType([
                'name' => 'MoreMenuInformation',
                'description' => 'More Menu information',
                'fields' => [
                    'section' => Type::string(),
                    'module_info' => Type::listOf($module_info),
                ]
            ]);

            return [
                'name' => 'UserMenu',
                'description' => "User menu",
                'type' => 'single',
                'action' => 'get_menu',
                'fields' => [
                    'Main' => Type::listOf($section_module),
                    'More' => Type::listOf($more_menu)
                ]
            ];
        } else if ($args['action'] == 'global_search') {

            $vtiger_record = new ObjectType([
                'name' => 'RecordStructure',
                'description' => 'Record structure',
                'fields' => [
                    'label'       => Type::string(),
                    'crmid'       => Type::int(),
                    'unit_price'  => Type::string(),
                    'createdtime' => Type::string()
                ]
            ]);

            $search_results = new ObjectType([
                'name' => 'SearchResults',
                'description' => 'Search results',
                'fields' => [
                    'module' => Type::string(),
                    'data' => Type::listOf($vtiger_record),
                ]
            ]);

            return [
                'name' => 'GlobalSearch',
                'description' => "Global Search",
                'args' => [
                    'value' => Type::nonNull(Type::string()),
                    'searchModule' => Type::string()
                ],
                'action' => 'global_search',
                'fields' => [
                    'results' => Type::listOf($search_results),
                ]
            ];
        } else if ($args['action'] == 'calendar_view') {
            if (isset($args['day'])) {
                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'id' => Type::int(),
                        'visibility' => Type::string(),
                        'activitytype' => Type::string(),
                        'status' => Type::string(),
                        'priority' => Type::string(),
                        'userfullname' => Type::string(),
                        'title' => Type::string(),
                        'start' => Type::string(),
                        'startDate' => Type::string(),
                        'startTime' => Type::string(),
                        'end' => Type::string(),
                        'endDate' => Type::string(),
                        'endTime' => Type::string(),
                        'recurringcheck' => Type::string(),
                    ]
                ]);
            } else {
                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'date' => Type::string(),
                        'count' => Type::int()
                    ]
                ]);
            }

            return [
                'name' => $args['module'] . 'Module',
                'description' => "Vtiger - Calendar",
                'type' => 'single',
                'action' => 'calendar_view',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'date' => Type::nonNull(Type::string()),
                    'day' => Type::boolean(),
                ],
                'fields' => [
                    'events' => Type::listOf($calendarObject),
                ]
            ];
        } else if ($args['action'] == 'describe') {

            $picklistType = new ObjectType([
                'name' => 'PicklistType',
                'Description' => 'Picklist field type',
                'fields' => [
                    'label' => Type::string(),
                    'value' => Type::string()
                ]
            ]);

            // Vtiger Field Type - string, integer, boolean etc
            $type = new ObjectType([
                'name' => 'FieldType',
                'description' => 'Field type',
                'fields' => [
                    'name' => Type::string(),
                    'refersTo' => Type::listOf(Type::string()),
                    'defaultValue' => Type::string(),
                    'picklistValues' => Type::listOf($picklistType),
                    'format' => Type::string(),
                ]
            ]);

            $fields = new ObjectType([
                'name' => 'Fields',
                'description' => 'CRM Fields',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'mandatory' => Type::boolean(),
                    'nullable' => Type::boolean(),
                    'editable' => Type::boolean(),
                    'default' => Type::string(),
                    'headerfield' => Type::boolean(),
                    'summaryfield' => Type::boolean(),
                    'type' => $type,
                ]
            ]);

            return [
                'name' => $args['module'] . 'FieldInfo',
                'description' => "Vtiger - {$args['module']} fields info",
                'type' => 'single',
                'action' => 'get_schema',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'label' => Type::string(),
                    'name' => Type::string(),
                    'createable' => Type::boolean(),
                    'updateable' => Type::boolean(),
                    'deleteable' => Type::boolean(),
                    'retrieveable' => Type::id(),
                    'nameFields' => Type::listOf(Type::string()),
                    'fields' => Type::listOf($fields),
                ]
            ];
        } else if ($args['action'] == 'filters') {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'default' => Type::string()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Filters',
                'description' => "Vtiger - {$args['module']} filters",
                'type' => 'single',
                'action' => 'get_filters',
                'args' => [
                    'module' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'Mine' => Type::listOf($filter),
                    'Shared' => Type::listOf($filter),
                    'Others' => Type::listOf($filter),
                ]
            ];
        } else if ($args['action'] == 'filter_columns') {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'fieldname' => Type::string(),
                    'fieldlabel' => Type::string()
                ]
            ]);

            return [
                'name' => 'FilterColumns',
                'description' => "Filter column fields",
                'type' => 'single',
                'action' => 'get_filter_columns',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ],
                'fields' => [
                    'filter' => Type::listOf($filter),
                ]
            ];
        } else if ($args['action'] == 'get_module_relations') {

            $relationType = new ObjectType([
                'name' => 'RelationsInformation',
                'description' => 'Relations information',
                'fields' => [
                    'module_name' => Type::string(),
                    'label' => Type::string(),
                    'tab_id' => Type::id()
                ]
            ]);

            return [
                'name' => 'ModuleRelations',
                'description' => "Module relations information",
                'type' => 'single',
                'action' => 'get_module_relations',
                'args' => [
                    'module' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'relations' => Type::listOf($relationType),
                ]
            ];
        } else if ($args['action'] == 'widget_info') {

            $widgetType = new ObjectType([
                'name' => 'WidgetInfo',
                'description' => 'Widget Information',
                'fields' => [
                    'count' => Type::int(),
                    'value' => Type::string(),
                    'label' => Type::string()
                ]
            ]);

            return [
                'name' => 'VtigerWidget',
                'description' => "Vtiger widget",
                'type' => 'single',
                'action' => 'widget_info',
                'args' => [
                    'name' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'data' => Type::listOf($widgetType)
                ],
            ];
        } else if ($args['action'] == 'get_related_records') {

            $module_fields = $this->generateModuleFields($args['related_module']);

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => 'RelatedModuleRecords',
                'description' => "Related module records",
                'type' => 'single',
                'action' => 'get_related_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id()),
                    'related_module' => Type::nonNull(Type::string()),
                    'page' => Type::nonNull(Type::id()),
                ],
                'fields' => [
                    'related_records' => Type::listOf($moduleFieldsType),
                    'page' => Type::int(),
                    'moreRecords' => Type::boolean(),
                    'nameFields' => Type::listOf(Type::string())
                ]
            ];
        } else if ($args['action'] == 'get_users') {

            $userInfo = new ObjectType([
                'name' => 'UserInfo',
                'description' => 'User/Group Information',
                'fields' => [
                    'id' => Type::int(),
                    'label' => Type::string(),
                    'is_user' => Type::boolean()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_users',
                'args' => [
                    'groups' => Type::boolean(),
                ],
                'fields' => [
                    'response' => Type::listOf($userInfo),
                ]
            ];
        } //Forecast
        else if ($args['action'] == 'get_forecast') {
            $module_fields = [];
            $module_fields['potentialname'] = Type::string();
            $moduleFieldsType = new ObjectType([
                'name' => 'PotentialsFieldsType',
                'description' => 'Potentials fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => 'ForecastFields',
                'description' => "Forecast fields",
                'type' => 'single',
                'action' => 'get_forecast',
                'args' => [
                    'month' => Type::int(),
                    'year' => Type::int()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'moreRecords' => Type::boolean(),
                    'page' => Type::int(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string()
                ]
            ];
        }
        //Monthly Counts
        else if ($args['action'] == 'get_monthly_counts') {
            $module_fields = [];
            $module_fields['potentialname'] = Type::string();
            $moduleFieldsType = new ObjectType([
                'name' => 'PotentialsFieldsType',
                'description' => 'Potentials fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => 'MonthlyCounts',
                'description' => "Monthly Counts",
                'type' => 'single',
                'action' => 'get_monthly_counts',
                'args' => [
                    'month' => Type::int(),
                    'year' => Type::int()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'moreRecords' => Type::boolean(),
                    'page' => Type::int(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string()
                ]
            ];
        }
        // TODO Add related module type. Should be able to query in a single shot
        else if ($args['action'] == 'list') {

            $headerType = new ObjectType([
                'name' => 'HeaderFields',
                'description' => 'Header field information',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'fieldType' => Type::string()
                ]
            ]);

            $module_fields = $this->generateModuleFields($args['module']);

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'page' => Type::int(),
                    'limit' => Type::int(),
                    'order_by' => Type::string(),
                    'sort_by' => Type::string(),
                    'filter_id' => Type::int(),
                    'search_key' => Type::string(),
                    'search_value' => Type::string()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'headers' => Type::listOf($headerType),
                    'selectedFilter' => Type::int(),
                    'nameFields' => Type::listOf(Type::string()),
                    'moreRecords' => Type::boolean(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string(),
                    'page' => Type::int(),
                ]
            ];
        } else if ($args['action'] == 'get_record') {
            $module_fields = $this->generateModuleFields($args['module']);

            if ($args['module'] == 'Contacts') {
                $module_fields['imageurl'] = Type::string();
            }

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        } else if ($args['action'] == 'tax') { 
            $module_fields = $this->generateModuleFields($args['module']);

            $moduleFieldsType = new ObjectType([
                'name'        => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields'      => $module_fields
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'filter_id' => Type::int(),                  
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                ]
            ];

        }else {
            $module_fields = $this->generateModuleFields($args['module']);
            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [ 
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        }         
        return false;
    }

    /**
     * Generate Module fields
     *
     * @param $module
     * @return mixed
     * @throws \WebServiceException
     */
    public function generateModuleFields($module)
    { 
        $module_schema = [];
        $module_name = ucfirst($module);
        $user_module_info = '{"label":"Users","name":"Users","createable":true,"updateable":true,"deleteable":true,"retrieveable":true,"fields":[{"name":"user_name","label":"User Name","mandatory":true,"type":{"name":"string"},
        "isunique":false,"nullable":true,"editable":true,"default":""},{"name":"is_admin","label":"Admin","mandatory":false,"type":{"name":"boolean"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"user_password",
            "label":"Password","mandatory":true,"type":{"name":"password"},"isunique":false,
            "nullable":true,"editable":true,"default":""},{"name":"confirm_password","label":"Confirm Password",
                "mandatory":true,"type":{"name":"password"},"isunique":false,"nullable":true,"editable":true,"default":""},
                {"name":"first_name","label":"First Name","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,
                "editable":true,"default":""},{"name":"last_name","label":"Last Name","mandatory":true,"type":{"name":"string"},
                "isunique":false,"nullable":true,"editable":true,"default":""},{"name":"roleid","label":"Role","mandatory":true,"type":{"refersTo":[],"name":"reference"},
                "isunique":false,"nullable":false,"editable":true,"default":""},{"name":"email1","label":"Primary Email","mandatory":true,"type":{"name":"email"},"isunique":false,"nullable":true,"editable":true,"default":""},
                {"name":"status","label":"Status","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":"Active"},{"name":"activity_view","label":"Default MyCalendar View","mandatory":false,"type":{"picklistValues":[{"label":"Today","value":"Today"},{"label":"This Week","value":"This Week"},
                {"label":"This Month","value":"This Month"},{"label":"This Year","value":"This Year"},{"label":"Agenda","value":"Agenda"}],"defaultValue":"Today","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},
                {"name":"lead_view","label":"Default Lead View","mandatory":false,"type":{"picklistValues":[{"label":"Today","value":"Today"},{"label":"Last 2 Days","value":"Last 2 Days"},{"label":"Last Week","value":"Last Week"}],"defaultValue":"Today","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"hour_format","label":"Calendar Hour Format","mandatory":false,"type":{"picklistValues":[{"label":"12","value":"12"},{"label":"24","value":"24"}],"defaultValue":"12","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"12"},{"name":"end_hour","label":"Day ends at","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"start_hour","label":"Day starts at","mandatory":false,"type":{"picklistValues":[{"label":"00:00","value":"00:00"},{"label":"01:00","value":"01:00"},{"label":"02:00","value":"02:00"},{"label":"03:00","value":"03:00"},{"label":"04:00","value":"04:00"},{"label":"05:00","value":"05:00"},{"label":"06:00","value":"06:00"},{"label":"07:00","value":"07:00"},{"label":"08:00","value":"08:00"},{"label":"09:00","value":"09:00"},{"label":"10:00","value":"10:00"},{"label":"11:00","value":"11:00"},{"label":"12:00","value":"12:00"},{"label":"13:00","value":"13:00"},{"label":"14:00","value":"14:00"},{"label":"15:00","value":"15:00"},{"label":"16:00","value":"16:00"},{"label":"17:00","value":"17:00"},{"label":"18:00","value":"18:00"},{"label":"19:00","value":"19:00"},{"label":"20:00","value":"20:00"},{"label":"21:00","value":"21:00"},{"label":"22:00","value":"22:00"},{"label":"23:00","value":"23:00"}],"defaultValue":"00:00","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"is_owner","label":"Account Owner","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":"0"},{"name":"title","label":"Title","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_work","label":"Office Phone","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"department","label":"Department","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_mobile","label":"Mobile Phone","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"reports_to_id","label":"Reports To","mandatory":false,"type":{"refersTo":["Users"],"name":"reference"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_other","label":"Secondary Phone","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"email2","label":"Other Email","mandatory":false,"type":{"name":"email"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_fax","label":"Fax","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"secondaryemail","label":"Secondary Email","mandatory":false,"type":{"name":"email"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_home","label":"Home Phone","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"date_format","label":"Date Format","mandatory":false,"type":{"picklistValues":[{"label":"dd-mm-yyyy","value":"dd-mm-yyyy"},{"label":"mm-dd-yyyy","value":"mm-dd-yyyy"},{"label":"yyyy-mm-dd","value":"yyyy-mm-dd"}],"defaultValue":"dd-mm-yyyy","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"signature","label":"Signature","mandatory":false,"type":{"name":"text"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"description","label":"Documents","mandatory":false,"type":{"name":"text"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"address_street","label":"Street Address","mandatory":false,"type":{"name":"text"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"address_city","label":"City","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"address_state","label":"State","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"address_postalcode","label":"Postal Code","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"address_country","label":"Country","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"accesskey","label":"Access Key","mandatory":false,"type":{"name":"autogenerated"},"isunique":false,"nullable":true,"editable":false,"default":""},{"name":"time_zone","label":"Time Zone","mandatory":false,"type":{"picklistValues":[{"label":"(UTC-11:00) Coordinated Universal Time-11","value":"Pacific\/Midway"},{"label":"(UTC-11:00) Samoa","value":"Pacific\/Samoa"},{"label":"(UTC-10:00) Hawaii","value":"Pacific\/Honolulu"},{"label":"(UTC-09:00) Alaska","value":"America\/Anchorage"},{"label":"(UTC-08:00) Pacific Time (US &amp; Canada)","value":"America\/Los_Angeles"},{"label":"(UTC-08:00) Tijuana, Baja California","value":"America\/Tijuana"},{"label":"(UTC-07:00) Mountain Time (US &amp; Canada)","value":"America\/Denver"},{"label":"(UTC-07:00) Chihuahua, La Paz, Mazatlan","value":"America\/Chihuahua"},{"label":"(UTC-07:00) Mazatlan","value":"America\/Mazatlan"},{"label":"(UTC-07:00) Arizona","value":"America\/Phoenix"},{"label":"(UTC-06:00) Saskatchewan","value":"America\/Regina"},{"label":"(UTC-06:00) Central America","value":"America\/Tegucigalpa"},{"label":"(UTC-06:00) Central Time (US &amp; Canada)","value":"America\/Chicago"},{"label":"(UTC-06:00) Mexico City","value":"America\/Mexico_City"},{"label":"(UTC-06:00) Monterrey","value":"America\/Monterrey"},{"label":"(UTC-05:00) Eastern Time (US &amp; Canada)","value":"America\/New_York"},{"label":"(UTC-05:00) Bogota, Lima, Quito","value":"America\/Bogota"},{"label":"(UTC-05:00) Lima","value":"America\/Lima"},{"label":"(UTC-05:00) Rio Branco","value":"America\/Rio_Branco"},{"label":"(UTC-05:00) Indiana (East)","value":"America\/Indiana\/Indianapolis"},{"label":"(UTC-04:30) Caracas","value":"America\/Caracas"},{"label":"(UTC-04:00) Atlantic Time (Canada)","value":"America\/Halifax"},{"label":"(UTC-04:00) Manaus","value":"America\/Manaus"},{"label":"(UTC-04:00) Santiago","value":"America\/Santiago"},{"label":"(UTC-04:00) La Paz","value":"America\/La_Paz"},{"label":"(UTC-04:00) Cuiaba","value":"America\/Cuiaba"},{"label":"(UTC-04:00) Asuncion","value":"America\/Asuncion"},{"label":"(UTC-03:30) Newfoundland","value":"America\/St_Johns"},{"label":"(UTC-03:00) Buenos Aires","value":"America\/Argentina\/Buenos_Aires"},{"label":"(UTC-03:00) Brasilia","value":"America\/Sao_Paulo"},{"label":"(UTC-03:00) Greenland","value":"America\/Godthab"},{"label":"(UTC-03:00) Montevideo","value":"America\/Montevideo"},{"label":"(UTC-02:00) Mid-Atlantic","value":"Atlantic\/South_Georgia"},{"label":"(UTC-01:00) Azores","value":"Atlantic\/Azores"},{"label":"(UTC-01:00) Cape Verde Is.","value":"Atlantic\/Cape_Verde"},{"label":"(UTC) London, Edinburgh, Dublin, Lisbon","value":"Europe\/London"},{"label":"(UTC) Coordinated Universal Time, Greenwich Mean Time","value":"UTC"},{"label":"(UTC) Monrovia, Reykjavik","value":"Africa\/Monrovia"},{"label":"(UTC) Casablanca","value":"Africa\/Casablanca"},{"label":"(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague","value":"Europe\/Belgrade"},{"label":"(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb","value":"Europe\/Sarajevo"},{"label":"(UTC+01:00) Brussels, Copenhagen, Madrid, Paris","value":"Europe\/Brussels"},{"label":"(UTC+01:00) West Central Africa","value":"Africa\/Algiers"},{"label":"(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna","value":"Europe\/Amsterdam"},{"label":"(UTC+02:00) Minsk","value":
        "Europe\/Minsk"},
        {"label":"(UTC+02:00) Cairo","value":"Africa\/Cairo"},
        {"label":"(UTC+02:00) Helsinki, Riga, Sofia, Tallinn, Vilnius","value":"Europe\/Helsinki"},{"label":"(UTC+02:00) Athens, Bucharest","value":"Europe\/Athens"},{"label":"(UTC+02:00) Istanbul","value":"Europe\/Istanbul"},{"label":"(UTC+02:00) Jerusalem","value":"Asia\/Jerusalem"},{"label":"(UTC+02:00) Amman","value":"Asia\/Amman"},{"label":"(UTC+02:00) Beirut","value":"Asia\/Beirut"},
        {"label":"(UTC+02:00) Windhoek","value":"Africa\/Windhoek"},
        {"label":"(UTC+02:00) Harare","value":"Africa\/Harare"},
        {"label":"(UTC+03:00) Kuwait, Riyadh","value":"Asia\/Kuwait"},
        {"label":"(UTC+03:00) Baghdad","value":"Asia\/Baghdad"},
        {"label":"(UTC+03:00) Nairobi","value":"Africa\/Nairobi"},{"label":"(UTC+03:30) Tehran","value":"Asia\/Tehran"},{"label":"(UTC+04:00) Tbilisi","value":"Asia\/Tbilisi"},{"label":"(UTC+03:00) Moscow, Volgograd","value":"Europe\/Moscow"},{"label":"(UTC+04:00) Abu Dhabi, Muscat","value":"Asia\/Muscat"},{"label":"(UTC+04:00) Baku","value":"Asia\/Baku"},{"label":"(UTC+04:00) Yerevan","value":"Asia\/Yerevan"},{"label":"(UTC+05:00) Islamabad, Karachi","value":"Asia\/Karachi"},{"label":"(UTC+05:00) Tashkent","value":"Asia\/Tashkent"},{"label":"(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi","value":"Asia\/Kolkata"},{"label":"(UTC+05:30) Sri Jayawardenepura","value":"Asia\/Colombo"},{"label":"(UTC+05:45) Kathmandu","value":"Asia\/Katmandu"},{"label":"(UTC+06:00) Dhaka","value":"Asia\/Dhaka"},{"label":"(UTC+06:00) Almaty","value":"Asia\/Almaty"},{"label":"(UTC+06:00) Ekaterinburg","value":"Asia\/Yekaterinburg"},{"label":"(UTC+06:30) Yangon (Rangoon)","value":"Asia\/Rangoon"},{"label":"(UTC+07:00) Novosibirsk","value":"Asia\/Novosibirsk"},{"label":"(UTC+07:00) Bangkok, Jakarta","value":"Asia\/Bangkok"},{"label":"(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi","value":"Asia\/Brunei"},{"label":"(UTC+08:00) Krasnoyarsk","value":"Asia\/Krasnoyarsk"},{"label":"(UTC+08:00) Ulaan Bataar","value":"Asia\/Ulaanbaatar"},{"label":"(UTC+08:00) Kuala Lumpur, Singapore","value":"Asia\/Kuala_Lumpur"},{"label":"(UTC+08:00) Taipei","value":"Asia\/Taipei"},{"label":"(UTC+08:00) Perth","value":"Australia\/Perth"},{"label":"(UTC+09:00) Irkutsk","value":"Asia\/Irkutsk"},{"label":"(UTC+09:00) Seoul","value":"Asia\/Seoul"},{"label":"(UTC+09:00) Tokyo","value":"Asia\/Tokyo"},{"label":"(UTC+09:30) Darwin","value":"Australia\/Darwin"},{"label":"(UTC+09:30) Adelaide","value":"Australia\/Adelaide"},{"label":"(UTC+10:00) Canberra, Melbourne, Sydney","value":"Australia\/Canberra"},{"label":"(UTC+10:00) Brisbane","value":"Australia\/Brisbane"},{"label":"(UTC+10:00) Hobart","value":"Australia\/Hobart"},{"label":"(UTC+10:00) Vladivostok","value":"Asia\/Vladivostok"},{"label":"(UTC+10:00) Guam, Port Moresby","value":"Pacific\/Guam"},{"label":"(UTC+10:00) Yakutsk","value":"Asia\/Yakutsk"},{"label":"(UTC+12:00) Fiji","value":"Pacific\/Fiji"},{"label":"(UTC+12:00) Kamchatka","value":"Asia\/Kamchatka"},{"label":"(UTC+12:00) Auckland","value":"Pacific\/Auckland"},{"label":"(UTC+12:00) Magadan","value":"Asia\/Magadan"},{"label":"(UTC+13:00) Nukualofa","value":"Pacific\/Tongatapu"},{"label":"(UTC+11:00) Solomon Is., New Caledonia","value":"Etc\/GMT-11"}],"defaultValue":"Pacific\/Midway","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"currency_id","label":"Currency","mandatory":false,"type":{"refersTo":["Currency"],"name":"reference"},"isunique":false,"nullable":false,"editable":true,"default":""},{"name":"currency_grouping_pattern","label":"Digit Grouping Pattern","mandatory":false,"type":{"picklistValues":[{"label":"123,456,789","value":"123,456,789"},{"label":"123456789","value":"123456789"},{"label":"123456,789","value":"123456,789"},{"label":"12,34,56,789","value":"12,34,56,789"}],"defaultValue":"123,456,789","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"currency_decimal_separator","label":"Decimal Separator","mandatory":false,"type":{"picklistValues":[{"label":".","value":"."},{"label":",","value":","},{"label":"\'","value":"\'"},{"label":" ","value":" "},{"label":"$","value":"$"}],"defaultValue":".","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"."},{"name":"currency_grouping_separator","label":"Digit Grouping Separator","mandatory":false,"type":{"picklistValues":[{"label":",","value":","},{"label":".","value":"."},{"label":"\'","value":"\'"},{"label":" ","value":" "},{"label":"$","value":"$"}],"defaultValue":",","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":","},{"name":"currency_symbol_placement","label":"Symbol Placement","mandatory":false,"type":{"picklistValues":[{"label":"$1.0","value":"$1.0"},{"label":"1.0$","value":"1.0$"}],"defaultValue":"$1.0","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"imagename","label":"User Image","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"internal_mailer","label":"Internal Mail Composer","mandatory":false,"type":{"name":"boolean"},"isunique":false,"nullable":false,"editable":true,"default":""},{"name":"theme","label":"Theme","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":"softed"},{"name":"language","label":"Language","mandatory":false,"type":{"name":"string"},"isunique":false,"nullable":true,"editable":true,"default":"en_us"},{"name":"reminder_interval","label":"Popup Reminder Interval","mandatory":false,"type":{"picklistValues":[{"label":"1 Minute","value":"1 Minute"},{"label":"5 Minutes","value":"5 Minutes"},{"label":"15 Minutes","value":"15 Minutes"},{"label":"30 Minutes","value":"30 Minutes"},{"label":"45 Minutes","value":"45 Minutes"},{"label":"1 Hour","value":"1 Hour"},{"label":"1 Day","value":"1 Day"}],"defaultValue":"1 Minute","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"phone_crm_extension","label":"CRM Phone Extension","mandatory":false,"type":{"name":"phone"},"isunique":false,"nullable":true,"editable":true,"default":""},{"name":"no_of_currency_decimals","label":"Number Of Currency Decimals","mandatory":false,"type":{"picklistValues":[{"label":"2","value":"2"},{"label":"3","value":"3"},{"label":"4","value":"4"},{"label":"5","value":"5"},{"label":"0","value":"0"},{"label":"1","value":"1"}],"defaultValue":"2","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"2"},{"name":"truncate_trailing_zeros","label":"Truncate Trailing Zeros","mandatory":false,"type":{"name":"boolean"},"isunique":false,"nullable":true,"editable":true,"default":"0"},{"name":"dayoftheweek","label":"Starting Day of the week","mandatory":false,"type":{"picklistValues":[{"label":"Sunday","value":"Sunday"},{"label":"Monday","value":"Monday"},{"label":"Tuesday","value":"Tuesday"},{"label":"Wednesday","value":"Wednesday"},{"label":"Thursday","value":"Thursday"},{"label":"Friday","value":"Friday"},{"label":"Saturday","value":"Saturday"}],"defaultValue":"Sunday","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"Monday"},{"name":"callduration","label":"Default Call Duration (Mins)","mandatory":false,"type":{"picklistValues":[{"label":"5","value":"5"},{"label":"10","value":"10"},{"label":"30","value":"30"},{"label":"60","value":"60"},{"label":"120","value":"120"}],"defaultValue":"5","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"5"},{"name":"othereventduration","label":"Other Event Duration (Mins)","mandatory":false,"type":{"picklistValues":[{"label":"5","value":"5"},{"label":"10","value":"10"},{"label":"30","value":"30"},{"label":"60","value":"60"},{"label":"120","value":"120"}],"defaultValue":"5","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"5"},{"name":"calendarsharedtype","label":"Calendar Shared Type","mandatory":false,"type":{"picklistValues":[{"label":"shared","value":"public"},{"label":"private","value":"private"},{"label":"seletedusers","value":"seletedusers"}],"defaultValue":"public","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"Public"},{"name":"default_record_view","label":"Default Record View","mandatory":false,"type":{"picklistValues":[{"label":"Summary","value":"Summary"},{"label":"Detail","value":"Detail"}],"defaultValue":"Summary","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"Summary"},{"name":"leftpanelhide","label":"Left Panel Hide","mandatory":false,"type":{"name":"boolean"},"isunique":false,"nullable":true,"editable":true,"default":"0"},{"name":"rowheight","label":"Row Height","mandatory":false,"type":{"picklistValues":[{"label":"wide","value":"wide"},{"label":"medium","value":"medium"},{"label":"narrow","value":"narrow"}],"defaultValue":"wide","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"medium"},{"name":"defaulteventstatus","label":"Default Event Status","mandatory":false,"type":{"picklistValues":[{"label":"Planned","value":"Planned"},{"label":"Held","value":"Held"},{"label":"Not Held","value":"Not Held"}],"defaultValue":"Planned","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"Planned"},{"name":"defaultactivitytype","label":"Default Activity Type","mandatory":false,"type":{"picklistValues":[{"label":"Call","value":"Call"},{"label":"Meeting","value":"Meeting"}],"defaultValue":"Call","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"Call"},{"name":"hidecompletedevents","label":"Hide Completed Calendar Events","mandatory":false,"type":{"name":"boolean"},"isunique":false,"nullable":true,"editable":true,"default":"0"},{"name":"defaultcalendarview","label":"Default Calendar View","mandatory":false,"type":{"picklistValues":[{"label":"List View","value":"ListView"},{"label":"My Calendar","value":"MyCalendar"},{
            "label":"Shared Calendar","value":"SharedCalendar"}],"defaultValue":"ListView","name":"picklist"},"isunique":false,"nullable":true,"editable":true,"default":"MyCalendar"},{"name":"id","label":"id","mandatory":false,"type":{"name":"autogenerated"},"editable":false,"nullable":false,"default":""}],"idPrefix":"19","isEntity":true,"allowDuplicates":true,"labelFields":"first_name,last_name"}';
        include_once 'include/Webservices/DescribeObject.php';
        if ($module_name != "Users") {
            $this->module_fields_info = vtws_describe($module_name, $this->user);
        } else {
            $this->module_fields_info = json_decode($user_module_info, true);
        }
        if (isset($this->module_fields_info['fields'])) {
            foreach ($this->module_fields_info['fields'] as $module_field) {             
                // Check if field is related to other module or field is assigned_user_id. If so, add the module type as string
                if ($module_field['type']['name'] == 'reference' || $module_field['name'] == 'assigned_user_id') {
                    // We are passing reference as String. Need to resolve the Id to Value before passing.
                    $module_schema[$module_field['name']] = Type::string();
                } else if ($module_field['name'] == 'id') {
                    $module_schema[$module_field['name']] = Type::int();
                } else {
                    $module_schema[$module_field['name']] = $this->typesMapping($module_field['type']['name']);
                }
            }
        }  


        $looping_array = array("productid", "quantity", "listprice","comment", "product_id" );
        if($module_name=='Invoice' || $module_name=='Quotes' || $module_name=='SalesOrder' || $module_name=='PurchaseOrder'){
            for ($i=1;$i <=50 ; $i++) {
                foreach ($module_schema as $key_change  => $value) { 
                    if(in_array($key_change, $looping_array)){
                       $module_schema[$key_change.$i] =$value ; 
                       if($key_change=='productid') 
                       $module_schema[$key_change.$i.'_id'] =$value ;
                    }                   
                }
            }                
        }  
        return $module_schema;
    }

    /**
     * Return filter columns
     *
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function getFilterColumns($args)
    {
        $module = $args['module'];
        $module_name = ucfirst($module);
        $tab_id = getTabid($module_name);
        $filter_id = $args['id'];
        if (empty($filter_id)) {
            throw new \Exception('Filter Id is mandatory');
        }

        $customView = new \CustomView($module_name);
        $filter_columns = $customView->getColumnsListByCvid($filter_id);
        if (empty($filter_columns)) {
            throw new \Exception('No column present in given Filter Id');
        }

        foreach ($filter_columns as $filter_column) {
            $details = explode(':', $filter_column);
            if (empty($details[2]) && $details[1] == 'crmid' && $details[0] == 'vtiger_crmentity') {
                $name = 'id';
                $customViewFields[] = $name;
            } else {
                $fields[] = $details[2];
                $customViewFields[] = $details[2];
            }
        }

        $filter_field_names = "'" . implode("','", $fields) . "'";
        $getFieldLabels = $this->db->pquery("select fieldname, fieldlabel from vtiger_field where fieldname IN ($filter_field_names) and tabid = ?", array($tab_id));
        while ($field_info = $this->db->fetch_row($getFieldLabels)) {
            $response[] = $field_info;
        }

        return ['filter' => $response];
    }

    /**
     * Return entity modules of Vtiger 
     *
     * @return array
     * @throws \WebServiceException
     */
    public function getJoModules()
    {
        $result = $this->db->pquery("SELECT id, name FROM vtiger_ws_entity WHERE ismodule = 1 and name NOT IN ('Users', 'Events')", array());
        while ($module_info = $this->db->fetch_array($result)) {
            $modules[$module_info['name']] = $module_info['id'];
        }

        $list_types = vtws_listtypes(null, $this->user);

        $listing = array();
        foreach ($list_types['types'] as $index => $module_name) {
            if (!isset($modules[$module_name])) continue;

            $listing[] = [
                'id'   => $modules[$module_name],
                'name' => $module_name,
                'isEntity' => $list_types['information'][$module_name]['isEntity'],
                'label' => $list_types['information'][$module_name]['label'],
                'singular' => $list_types['information'][$module_name]['singular'],
            ];
        }
        return $listing;
    }

    public function getUserFilters($args)
    {
        global $current_user;
        $current_user = $this->user;

        $allFilters = \CustomView_Record_Model::getAllByGroup($args['module']);
        unset($allFilters['Public']);
        $result = array();
        if ($allFilters) {
            foreach ($allFilters as $group => $filters) {
                $result[$group] = array();
                foreach ($filters as $filter) {
                    $result[$group][] = array('id' => $filter->get('cvid'), 'name' => $filter->get('viewname'), 'default' => $filter->isDefault());
                }
            }
        }
        return $result;
    }

    public function getModuleFields($module)
    { 
        $module = ucfirst($module);
        include_once 'include/Webservices/DescribeObject.php';
        $describeInfo = vtws_describe($module, $this->user);
        $fields = $describeInfo['fields'];
        $moduleModel = \Vtiger_Module_Model::getInstance($module);
        $nameFields = $moduleModel->getNameFields();
        if (is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
        } else if (is_array($nameFields)) {
            foreach ($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name' => $nameFieldModel->get('name'), 'label' => $nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        $fieldModels = $moduleModel->getFields();
        if ($module=='ModComments') {
            for ($i=0; $i <count($fields) ; $i++) { 
                unset($fields[15]);
            }
        }
        foreach ($fields as $index => $field) {
            if ($module == 'PurchaseOrder' || $module == 'Invoice' || $module == 'SalesOrder' || $module == 'Quotes') {
                if ($field['name'] == 'shipping_&_handling' || $field['name'] == 'shipping_&_handling_shtax1' || $field['name'] == 'shipping_&_handling_shtax2' || $field['name'] == 'shipping_&_handling_shtax3') {
                    continue;
                }
            }
            if ($field['type']['name'] == 'boolean' && $field['default'] == 'on') {
                $field['default'] = true;
            }
            if ($field['name'] == 'activitytype' && $module == 'Calendar') {
                $field['mandatory'] = true;
            }
            if ($field['name'] == 'salutationtype') {
                $field['type']['name'] = picklist;
                $field['type']['picklistValues'] = array(array('label' => 'None', 'value' => 'None'), array('label' => 'Mr.', 'value' => 'Mr.'), array('label' => 'Mrs.', 'value' => 'Mrs.'), array('label' => 'Ms.', 'value' => 'Ms.'), array('label' => 'Dr.', 'value' => 'Dr.'), array('label' => 'Prof.', 'value' => 'Prof.'));
            }

            $fieldModel = $fieldModels[$field['name']];
            if ($fieldModel) {
                $field['headerfield'] = $fieldModel->get('headerfield');
                $field['summaryfield'] = $fieldModel->get('summaryfield');
	    }
	 /*    if($module=='HelpDesk'){
                $ticketFields =array('ticket_no','assigned_user_id','ticketpriorities','ticketstatus','createdtime','modifiedtime','from_portal','modifiedby','source','starred','id','solution');

                if(in_array($field['name'], $ticketFields)){
                   continue;
                }
	    } */

            //tags taken out from schema
            if ($field['name'] == 'tags') {
                continue;
            } else {
                $newFields[] = $field;
            }
        }
        $fields = null;
        $looping_array = array("productid", "quantity", "listprice","comment" );
        if($module=='Invoice' || $module=='Quotes' || $module=='SalesOrder' || $module=='PurchaseOrder'){ 
            if(!empty($id=$_REQUEST['id'])){
                $recordModel = \Vtiger_Record_Model::getInstanceById($id, $module);
                $relatedProducts = $recordModel->getProducts();
                $itemcount = count($relatedProducts); 
            }                       
            $addloop =array();
            for ($i=1;$i <=$itemcount ; $i++) {
                foreach ($newFields as $key_change  => $value) { 
                    if(in_array($value['name'], $looping_array)){ 
                       $value['name']= $value['name'].$i;
                       $value['label']= $value['label'].$i;
                       $newFields[] =$value ;  
                       if($value['name']=='productid'.$i){ 
                            $value['name']= $value['name'].'_id';
                            $value['label']= $value['label'];
                            $value['editable']= 0;
                            $newFields[] =$value ; 
                        }                      
                    }                   
                }
            } 
            for ($i=0; $i < count($newFields); $i++) {
               if(in_array($newFields[$i]['name'], $looping_array)){ 
                    $newFields[$i]['editable']='0';
               }
            }             
	}
        if($module=='HelpDesk'){
		$ticketFields =array();
		$field_list = array();
		$satisfied = false;
            for ($z=count($newFields); $z >=1 ; $z--) { 
		    if($newFields[$z]['name'] == 'description'){
			    $ticketFields[]=$newFields[$z];
			    if(count($field_list)!=0)
			     $ticketFields[]=$field_list[0];
			    $satisfied = true;
		}  
		    elseif($satisfied==false && $newFields[$z]['name'] == 'parent_id'){
			$field_list[]=$newFields[$z];
		    }else{
			    if($newFields[$z] !='')
			$ticketFields[]=$newFields[$z];
		}
	    }
	    array_pop($newFields);
	    $newField = $ticketFields;
	}else{	
        array_pop($newFields);	
	$newField = array_reverse($newFields, true);
	}$describeInfo['nameFields'] = $nameFields;
        $describeInfo['fields'] = $newField;  
   	
	#echo'<pre>';print_r($newField); die("vf");
	return $describeInfo;
    }

    public function typesMapping($type)
    {
        if (
            $type == 'string' || $type == 'email' || $type == 'phone' || $type == 'date' || $type == 'datetime' || $type == 'text'
            || $type == 'picklist' || $type == 'url' || $type == 'password' || $type == 'autogenerated'
        ) {
            return Type::string();
        } else if ($type == 'boolean') {
            return Type::boolean();
        } else if ($type == 'owner') {
            return Type::int();
        } else {
            return Type::string();
        }
    }

    function resolveRecordValues($data)
    {
        foreach ($this->module_fields_info['fields'] as $field_info) {
            if ($field_info['type']['name'] == 'reference') {
                $data[$field_info['name']] = decode_html(\Vtiger_Functions::getCRMRecordLabel($data[$field_info['name']]));
            } else if ($field_info['name'] == 'assigned_user_id') {
                $assigned_user_id_label = decode_html(\Vtiger_Functions::getUserRecordLabel($data[$field_info['name']]));
                if (empty($assigned_user_id_label)) {
                    $assigned_user_id_label = decode_html(\Vtiger_Functions::getGroupRecordLabel($data[$field_info['name']]));
                }
                $data[$field_info['name']] = $assigned_user_id_label;
            } else if ($field_info['type']['name'] == 'boolean') {
                $boolean_response = false;
                if (!empty($data[$field_info['name']]) && $data[$field_info['name']] == 1)
                    $boolean_response = true;

                $data[$field_info['name']] = $boolean_response;
            } else if ($field_info['type']['name'] == 'time' && ($field_info['name'] == 'time_start' || $field_info['name'] == 'time_end')) {
                if (!empty($data[$field_info['name']])) {
                    $date = $data['date_start'];
                    if ($field_info['name'] == 'time_end')
                        $date = $data['due_date'];

                    $temp_value = $date . ' ' . $data[$field_info['name']];
                    if ($this->user->hour_format == 12) {
                        $dateTimeObject = new \DateTime($temp_value);
                        $updated_datetime = $dateTimeObject->format('Y-m-d h:i:s');
                        $temp_value = $updated_datetime;
                    }

                    $converted_date = \DateTimeField::convertToUserFormat($temp_value, $this->user);
                    $dateTime = new \DateTimeField($temp_value);
                    $converted_time = $dateTime->getDisplayTime();
                    $data[$field_info['name']] = $converted_time;
                }
            }
        }
        return $data;
    }

    public function getWidgetData($args)
    {
        global $current_user;
        $data = [];
        $current_user = $this->user;
        $allowed_widget_info = [
            'GroupedBySalesStage' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesStage'],
            'GroupedBySalesPerson' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesPerson'],
            'LeadsByStatus' => ['module' => 'Leads', 'function' => 'getLeadsByStatus'],
            'LeadsBySource' => ['module' => 'Leads', 'function' => 'getLeadsBySource'],
        ];

        if (!array_key_exists($args['name'], $allowed_widget_info)) {
            throw new \Exception('Widget not allowed');
        }
        $moduleModel = \Vtiger_Module_Model::getInstance($allowed_widget_info[$args['name']]['module']);
        if($this->user->is_owner == 1){ 
            $user ='all';
        }else{
             $user =$this->user->id;
        }       
	$response_data = $moduleModel->{$allowed_widget_info[$args['name']]['function']}($user, null);
            
	if ($args['name'] == 'GroupedBySalesStage') {
       
		foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data[1];
                $data[$data_key]['value'] = $resolve_data[0];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        } else if ($args['name'] == 'GroupedBySalesPerson') {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data['count'];
                $data[$data_key]['value'] = $resolve_data['last_name'];
                $data[$data_key]['label'] = $resolve_data['last_name'];
            }
        } else {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data[0];
                $data[$data_key]['value'] = $resolve_data[1];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        }
        return $data;
    }

    public function getCalendarInfo($args)
    {
        global $current_user;
        $current_user = $this->user;

        $start = $args['date'];
        // TODO Need improvements on this function
        if (!empty($args['day']) && $args['day'] === true) {
            $noOfDays = 1;
        } else {
            $noOfDays = 31;
        }

        $user_formatted_date = \DateTimeField::convertToUserFormat($start, $current_user);

        $dbStartDateOject = \DateTimeField::convertToDBTimeZone($start, $current_user);
        $dbStartDateTime = $dbStartDateOject->format('Y-m-d H:i:s');

        $cache_datetime = strtotime($dbStartDateTime);
        $secondsDelta = 24 * 60 * 60 * $noOfDays;
        $futureDate = $cache_datetime + $secondsDelta;
        $dbEndDateTime = date("Y-m-d H:i:s", $futureDate);

        $currentUser = \Users_Record_Model::getCurrentUserModel();
        $db = $this->db;

        $query = 'SELECT vtiger_activity.subject, vtiger_activity.eventstatus, vtiger_activity.priority ,vtiger_activity.visibility,
        vtiger_activity.date_start, vtiger_activity.time_start, vtiger_activity.due_date, vtiger_activity.time_end,
        vtiger_crmentity.smownerid, vtiger_activity.activityid, vtiger_activity.activitytype, vtiger_activity.recurringtype,
        vtiger_activity.location FROM vtiger_activity
        INNER JOIN vtiger_crmentity ON vtiger_activity.activityid = vtiger_crmentity.crmid
        LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid = vtiger_users.id
        LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid = vtiger_groups.groupid
        WHERE vtiger_crmentity.deleted=0 AND vtiger_activity.activityid > 0 AND vtiger_activity.activitytype NOT IN ("Emails","Task") AND ';

        $hideCompleted = $currentUser->get('hidecompletedevents');
        if ($hideCompleted) {
            $query .= "vtiger_activity.eventstatus != 'HELD' AND ";
        }
        $query .= " (concat(date_start,' ',time_start)) >= '$dbStartDateTime' AND (concat(date_start,' ',time_start)) < '$dbEndDateTime'";

        $eventUserId = $currentUser->getId();
        $params = array_merge(array($eventUserId), $this->getGroupsIdsForUsers($eventUserId));
        $query .= " AND vtiger_crmentity.smownerid IN (" . generateQuestionMarks($params) . ")";
        $query .= ' ORDER BY time_start';
        $queryResult = $db->pquery($query, $params);
        $items = [];
        while ($record = $db->fetchByAssoc($queryResult)) {
            $item = array();
            $item['id']                = $record['activityid'];
            $item['visibility']        = $record['visibility'];
            $item['activitytype']    = $record['activitytype'];
            $item['status']            = $record['eventstatus'];
            $item['priority']        = $record['priority'];
            $item['userfullname']    = getUserFullName($record['smownerid']);
            $item['title']            = decode_html($record['subject']);
            $dateTimeFieldInstance = new \DateTimeField($record['date_start'] . ' ' . $record['time_start']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $startDateComponents = explode(' ', $userDateTimeString);
            //$startDateComponents[0] = \DateTimeField::convertToUserFormat($startDateComponents[0], $current_user);
            $item['start'] = $userDateTimeString;
            $item['startDate'] = $startDateComponents[0];
            $item['startTime'] = $startDateComponents[1];
            $dateTimeFieldInstance = new \DateTimeField($record['due_date'] . ' ' . $record['time_end']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $endDateComponents = explode(' ', $userDateTimeString);
            $item['end'] = $userDateTimeString;
            $item['endDate'] = $endDateComponents[0];
            $item['endTime'] = $endDateComponents[1];
            if ($currentUser->get('hour_format') == '12') {
                $item['startTime'] = \Vtiger_Time_UIType::getTimeValueInAMorPM($item['startTime']);
                $item['endTime'] = \Vtiger_Time_UIType::getTimeValueInAMorPM($item['endTime']);
            }
            $recurringCheck = false;
            if ($record['recurringtype'] != '' && $record['recurringtype'] != '--None--') {
                $recurringCheck = true;
            }
            $item['recurringcheck'] = $recurringCheck;
            array_push($items, $item);
            $result[$startDateComponents[0]][] = $item;
        } 
        if (!isset($args['day']) || $args['day'] === false) {
            if (empty($result)) {
                $response[] = ['date' => $start, 'count' => 0];
            } else {
                foreach ($result as $date => $date_wise) {
                    $response[] = ['date' => $date, 'count' => count($result[$date])];
                }
            }
            return $response; //for count
        } else {
            // return isset($result[$user_formatted_date]) && !empty($result[$user_formatted_date]) ? $result[$user_formatted_date] : [];
            $events = [];
            foreach ($items as  $value) {
                $event = [];
                $event['id'] = $value['id'];
                $event['title'] = $value['title'];
                $event['startTime'] = $value['startTime'];
                $event['endTime'] = $value['endTime'];
                array_push($events, $event);
            }

            $calendar_view = [];
            $calendar_view['date'] = $start;
            $calendar_view['events'] = $events;
            $data = [];
            $data['calendar_view'] = $calendar_view;
            $finalresult = [];
            $finalresult['data'] = $data;
            print_r(json_encode($finalresult));
            die();
        }
    }

    public function getUserMenu()
    {
	global $site_URL;
        $data = [];
        $more_section = [];
        $user_id = $this->user->id;
        $modules = array();
        $presence = array('0', '2');
        global $current_user;
        $userPrivModel = \Users_Privileges_Model::getCurrentUserPrivilegesModel();
        $APP_GROUPED_MENU = \Settings_MenuEditor_Module_Model::getAllVisibleModules();
        $APP_LIST =\Vtiger_MenuStructure_Model::getAppMenuList();
        foreach ($APP_LIST as $APP_NAME) { 
            $count= count($APP_GROUPED_MENU[$APP_NAME]);
            for ($i = 0; $i < $count; $i++) {
                foreach ($APP_GROUPED_MENU[$APP_NAME] as $APP_MENU_MODEL) {
                    $appname = $APP_NAME;
                    if($appname == null)
                        $appname = 'MORE';
                    $tabid = $APP_MENU_MODEL->id;
                    $sequence = $APP_MENU_MODEL->tabsequence;
                    $moduleName = getTabModuleName($tabid);
                    $modules[$appname][$tabid] = $moduleName;                        
                }  
            }          
        }
        $more_section = $modules['MORE'];
        $more_section_array = [];
        unset($modules['MORE']);
        $i = 0;
        foreach($more_section as $tabid => $module_name)
        {
            $more_section_array[$i] = ['name' => $module_name, 'label' => vtranslate($module_name), 'tabid' => $tabid];
            $i = $i + 1;
        }

        $i = 0;
        $main_section = [];
        foreach($modules as $section => $module_array)  {
            $main_section[$i]['section'] = vtranslate('LBL_'.$section,$section); #section language
            $main_section[$i]['module_info'] = [];

            foreach($module_array as $tab_id => $module_name) {
                $is_entity_module = $this->checkEntityModule($module_name);

                if($is_entity_module) {
                    $main_section[$i]['module_info'][] = ['name' => $module_name, 'label' => vtranslate($module_name), 'tabid' => $tab_id, 'image_url' => $site_URL.'layouts/v7/skins/images/'.$module_name.'.png'];
                }
            }

            // If no modules present in the section, unset the section
            if(count($main_section[$i]['module_info']) == 0) {
                unset($main_section[$i]);
                continue;
            }
            $i = $i + 1;
        }
        $data['More'] = $more_section_array;
       // $staticmenulist =array('section'=>'HelpDesk');
  //$staticmenulist =array('section'=>'HelpDesk', "module_info"=> array("name"=>"HelpDesk","label"=>"HelpDesk"));	
$staticmenulist =array('section'=>'HelpDesk', "module_info"=> array('0'=>array("name"=>"HelpDesk","label"=>"Tickets" ,'tabid'=>'13','image_url' =>$site_URL.'layouts/v7/skins/images/HelpDesk.png')));
	array_push($main_section, $staticmenulist);       
	$data['Main'] = $main_section;
              //print_R($data); die("vf");       
	return $data;
    }

    public function checkEntityModule($module_name)
    {
        $getEntity = $this->db->pquery('select isentitytype from vtiger_tab where name = ?', array($module_name));
        if ($getEntity && $this->db->num_rows($getEntity)) {
            return $this->db->query_result($getEntity, 0, 'isentitytype');
        }
        return false;
    }

    public function globalSearch($args)
    {
        global $current_user;
        $current_user = $this->user;

        $request = new \Vtiger_Request($args);
        $listView = new \Vtiger_ListAjax_View();
        $search_result = $listView->searchAll($request, true);

        $i = 0;
        $response = [];
        if ($search_result) {
            foreach ($search_result as $module => $module_records) {
                $response[$i]['module'] = $module;
                if (!empty($module_records)) {

                    foreach ($module_records as $module_record) {                       
                        if($module='Products'){
                            $getvalue=$module_record->getData();
                            $moduleModel = \Vtiger_Module_Model::getInstance($response[$i]['module']);
                            $recordModel = \Vtiger_Record_Model::getInstanceById($getvalue['crmid'], $moduleModel);
                            $module_record->set('unit_price',$recordModel->entity->column_fields['unit_price']);
                        }      
                        $response[$i]['data'][] = $module_record->getData();                  
                    }
                } else {
                    $response[$i]['data'] = [];
                }

                $i = $i + 1;
            }
        }  
        return $response;
    }

    protected function getGroupsIdsForUsers($userId)
    {
        vimport('~~/includes/utils/GetUserGroups.php');
        $userGroupInstance = new \GetUserGroups();
        $userGroupInstance->getAllUserGroups($userId);
        return $userGroupInstance->user_groups;
    }

    public function addlineitem($focus,$lineitem){  

    global $log, $adb;
    $moduleName=$module=$focus['record_module'];
    $id=$focus['id']; 
    if(empty($moduleName))
        $moduleName=$module=$_REQUEST['module']; 
    $ext_prod_arr = Array();
    $all_available_taxes = getAllTaxes('available', '', 'edit', $id);  
    $tableName = '';
    switch($moduleName) {
        case 'Quotes'       : $tableName = 'vtiger_quotes';         $index = 'quoteid';         break;
        case 'Invoice'      : $tableName = 'vtiger_invoice';        $index = 'invoiceid';       break;
        case 'SalesOrder'   : $tableName = 'vtiger_salesorder';     $index = 'salesorderid';    break;
        case 'PurchaseOrder': $tableName = 'vtiger_purchaseorder';  $index = 'purchaseorderid'; break;
    }
    $tot_no_prod = 10;
    //If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
    $prod_seq=1;
    for($i=1; $i<=$tot_no_prod; $i++)
    {   
        if($focus["deleted".$i] == 1)
            continue;

        $prod_id = vtlib_purify($lineitem['productid'.$i]);   

        if (!$prod_id) {
            continue;
        }  
        if(isset($lineitem['productDescription'.$i]))
        $description = vtlib_purify($lineitem['productDescription'.$i]);
        $qty = vtlib_purify($lineitem['quantity'.$i]);
        $listprice = vtlib_purify($lineitem['listprice'.$i]);
        $comment = vtlib_purify($lineitem['comment'.$i]);
        $purchaseCost = ($qty*$listprice);
        $$margin =($qty*$listprice); 

        if($module == 'SalesOrder') {
            if($updateDemand == '-')
            {
                deductFromProductDemand($prod_id,$qty);
            }
            elseif($updateDemand == '+')
            {
                addToProductDemand($prod_id,$qty);
            }
        }

        $query = 'INSERT INTO vtiger_inventoryproductrel(id, productid, sequence_no, quantity, listprice, comment, description, purchase_cost, margin)
                    VALUES(?,?,?,?,?,?,?,?,?)';
        $qparams = array($id,$prod_id,$prod_seq,$qty,$listprice,$comment,$description, $purchaseCost, $margin);  
        $adb->pquery($query,$qparams);
        $lineitem_id = $adb->getLastInsertID(); 
        $sub_prod_str = vtlib_purify($lineitem['subproduct_ids'.$i]);
        if (!empty($sub_prod_str)) {
             $sub_prod = split(',', rtrim($sub_prod_str, ','));
             foreach ($sub_prod as $subProductInfo) {
                 list($subProductId, $subProductQty) = explode(':', $subProductInfo);
                 $query = 'INSERT INTO vtiger_inventorysubproductrel VALUES(?, ?, ?, ?)';
                 if (!$subProductQty) {
                     $subProductQty = 1;
                 }
                 $qparams = array($id, $prod_seq, $subProductId, $subProductQty);
                $adb->pquery($query,$qparams);
            }
        }
        $prod_seq++; 
        if($module != 'PurchaseOrder')
        {
            //update the stock with existing details
            updateStk($prod_id,$qty,$lineitem['mode'],$ext_prod_arr,$module);
        } 
        //we should update discount and tax details
        $updatequery = "update vtiger_inventoryproductrel set ";
        $updateparams = array();

        //set the discount percentage or discount amount in update query, then set the tax values
        if($lineitem['discount_type'.$i] == 'percentage')
        {
            $updatequery .= " discount_percent=?,";
            array_push($updateparams, vtlib_purify($lineitem['discount_percentage'.$i]));
        }
        elseif($lineitem['discount_type'.$i] == 'amount')
        {
            $updatequery .= " discount_amount=?,";
            $discount_amount = vtlib_purify($lineitem['discount_amount'.$i]);
            array_push($updateparams, $discount_amount);
        }
 
        $compoundTaxesInfo = getCompoundTaxesInfoForInventoryRecord($id, $module);
    
        if($lineitem['hdnTaxType'] == 'group')
        {
            for($tax_count=0;$tax_count<count($all_available_taxes);$tax_count++)
            {
                $taxDetails = $all_available_taxes[$tax_count];
                if ($taxDetails['method'] === 'Deducted') {
                    continue;
                } else if ($taxDetails['method'] === 'Compound') {
                    $compoundExistingInfo = $compoundTaxesInfo[$taxDetails['taxid']];
                    if (!is_array($compoundExistingInfo)) {
                        $compoundExistingInfo = array();
                    }
                    $compoundNewInfo = Zend_Json::decode(html_entity_decode($taxDetails['compoundon']));
                    $compoundFinalInfo = array_merge($compoundExistingInfo, $compoundNewInfo);
                    $compoundTaxesInfo[$taxDetails['taxid']] = array_unique($compoundFinalInfo);
                }

                $tax_name = $taxDetails['taxname'];
                $request_tax_name = $tax_name."_group_percentage";
                $tax_val = 0;
                if(isset($lineitem[$request_tax_name])) {
                    $tax_val = vtlib_purify($lineitem[$request_tax_name]);
                }
                $updatequery .= " $tax_name = ?,";
                array_push($updateparams, $tax_val);
            }
        }
        else
        {
            $taxes_for_product = getTaxDetailsForProduct($prod_id,'all');
            for($tax_count=0;$tax_count<count($taxes_for_product);$tax_count++)
            {
                $taxDetails = $taxes_for_product[$tax_count];
                if ($taxDetails['method'] === 'Compound') {
                    $compoundExistingInfo = $compoundTaxesInfo[$taxDetails['taxid']];
                    if (!is_array($compoundExistingInfo)) {
                        $compoundExistingInfo = array();
                    }

                    $compoundFinalInfo = array_merge($compoundExistingInfo, $taxDetails['compoundon']);
                    $compoundTaxesInfo[$taxDetails['taxid']] = array_unique($compoundFinalInfo);
                }
                $tax_name = $taxDetails['taxname'];
                $request_tax_name = $tax_name."_percentage".$i;

                $updatequery .= " $tax_name = ?,";
                array_push($updateparams, vtlib_purify($lineitem[$request_tax_name]));
            }
        }

        //Adding deduct tax value to query
        for($taxCount=0; $taxCount<count($all_available_taxes); $taxCount++) {
            if ($all_available_taxes[$taxCount]['method'] === 'Deducted') {
                $taxName = $all_available_taxes[$taxCount]['taxname'];
                $requestTaxName = $taxName.'_group_percentage';
                $taxValue = 0;
                if(isset($lineitem[$requestTaxName])) {
                    $taxValue = vtlib_purify($lineitem[$requestTaxName]);
                }

                $updatequery .= " $taxName = ?,";
                array_push($updateparams, (-$taxValue));
            }
        }

        $updatequery = trim($updatequery, ',').' WHERE id = ? AND productid = ? AND lineitem_id = ?';
        array_push($updateparams, $id, $prod_id, $lineitem_id);

        if( !preg_match( '/set\s+where/i', $updatequery)) {
            $adb->pquery($updatequery,$updateparams);
        }
    }  

    $updatequery  = " update " .$tableName. " set";
    $updateparams = array();
    $subtotal = vtlib_purify($lineitem['hdnSubTotal']);
    $updatequery .= " subtotal=?,";
    array_push($updateparams, $subtotal);

    $pretaxTotal = vtlib_purify($lineitem['pre_tax_total']); 
    $updatequery .= " pre_tax_total=?,"; 
    array_push($updateparams, $pretaxTotal);

    $updatequery .= " taxtype=?,";
    array_push($updateparams, $lineitem['hdnTaxType']);

    $discount_amount_final = vtlib_purify($lineitem['discount_amount']);
    $updatequery .= " discount_amount=?,discount_percent=?,";
    array_push($updateparams, $discount_amount_final);
    array_push($updateparams, null);

    
    $shipping_handling_charge = vtlib_purify($lineitem['hdnS_H_Amount']);
    $updatequery .= " s_h_amount=?,";
    array_push($updateparams, $shipping_handling_charge);

    //if the user gave - sign in adjustment then add with the value
    $adjustmentType = '';
    if($lineitem['adjustmentType'] == '-')
        $adjustmentType = vtlib_purify($lineitem['adjustmentType']);

    $adjustment = vtlib_purify($lineitem['adjustment']);   
    $updatequery .= " adjustment=?,";
    array_push($updateparams, $adjustmentType.$adjustment);

    $total = vtlib_purify($lineitem['hdnSubTotal']);
    $updatequery .= " total=?,";
    array_push($updateparams, $total);
    
    if(!empty($compoundTaxesInfo)){
    $updatequery .= ' compound_taxes_info = ?,'; 
    $result = \Zend\Json\Json::decode($compoundTaxesInfo);
    array_push($updateparams, $result); }

    if (isset($lineitem['region_id'])) {
        $updatequery .= " region_id = ?,";
        array_push($updateparams, vtlib_purify($lineitem['region_id']));
    }
    //to save the S&H tax details in vtiger_inventoryshippingrel table 
    $chargesInfo = array();
    if (isset($lineitem['charges'])) {
        $chargesInfo = $lineitem['charges'];
    } 
    $updatequery .= " s_h_percent=?"; //hdnS_H_Percent
    array_push($updateparams, $shipping_handling_charge);
 
    //Added where condition to which entity we want to update these values
    $updatequery .= " where ".$index."=?";
    array_push($updateparams, $id); 
    $adb->pquery($updatequery,$updateparams); 
    $log->debug("Exit from function saveInventoryProductDetails($module).");    
    
    }

    public function Register($request){
       error_log(print_r(array('SSS-2'),true),3,'/var/lib/crm-tcraccc/error.log'); 
        global $current_user,$site_URL;
        $current_user->id =1;
        $user_name = $request['user_name']; 
        
        $moduleName ='Users';
        $userModuleModel = \Users_Module_Model::getCleanInstance($moduleName);
        $status = $userModuleModel->checkDuplicateUser($user_name);
   error_log(print_r(array($status),true),3,'/var/lib/crm-tcraccc/error.log');
        if ($status == true) {
            return 'Duplicate User Exists';
        }else{
            $focus = \CRMEntity::getInstance($moduleName);
            $focus->column_fields["last_name"] = $request['last_name'];
            $focus->column_fields["first_name"] = $request['first_name'];
            $focus->column_fields["user_name"] = $user_name;
            $focus->column_fields["status"] = 'InActive';
            $focus->column_fields["is_admin"] = 'off';
            $focus->column_fields["user_password"] =$request['user_password'];
            $focus->column_fields["email1"] = $request['email1'];
            $focus->column_fields["roleid"] = 'H6';
	    $focus->column_fields["phone_mobile"] = $request['phone_mobile'];
	    $focus->save($moduleName);
               if(!empty($focus->id)){ 
                $redirecturl =$site_URL.'index.php?module=Users&action=UpdateUseractive&user_name='.$user_name.'&user_password='.$request['user_password'].'&record='.$focus->id; 
                
                $mail = new \Vtiger_Mailer();   
                $mail->IsHTML();
                $mail->Body = '<center><table style="padding-top:32px;background-color:#ffffff" cellspacing="0" cellpadding="0"><tbody>
                <tr><td>
                <table cellspacing="0" cellpadding="0"><tbody>
                <tr><td style="max-width:560px;padding:24px 24px 32px;background-color:#fafafa;border:1px solid #e0e0e0;border-radius:2px">
                <img style="padding:0 24px 16px 0;float:left" alt="Error Icon" src="http://212.71.252.209/tcracrm/layouts/v7/resources/Images/tcra-ccc.png" data-image-whitelisted="" class="CToWUd" width="72" height="72">
                <table style="min-width:272px;padding-top:8px"><tbody>
                <tr><td><h2 style="font-size:20px;color:#212121;font-weight:bold;margin:0">
                Thank you for signing up ,
                </h2></td></tr> 
                <tr><td style="padding-top:24px;color:#4285f4;font-size:14px;font-weight:bold;text-align:left">
                <a style="text-decoration:none" href="'.$redirecturl.'" target="_blank" data-saferedirecturl="">Click here to verify</a>
                </td></tr>
                </tbody></table>
                </td></tr>
                </tbody></table>
                </td></tr>
                </tbody></table></center>';
                $mail->Subject = 'Verify your email';
                $mail->AddAddress($request['email1']);

                $status = $mail->Send(true);
            }	    
	    //return $focus->column_fields;
	} 
	
        return $focus->id;
    }
}
