<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Vtiger_ListAjax_View extends Vtiger_List_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('getListViewCount');
		$this->exposeMethod('getRecordsCount');
		$this->exposeMethod('getPageCount');
		$this->exposeMethod('showSearchResults');
		$this->exposeMethod('ShowListColumnsEdit');
		$this->exposeMethod('showSearchResultsWithValue');
		$this->exposeMethod('searchAll');
	}

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	public function showSearchResults(Vtiger_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$listMode = $request->get('listMode');
		if(!empty($listMode)) {
			$request->set('mode', $listMode);
		}

		$customView = new CustomView();
		$this->viewName = $customView->getViewIdByName('All', $moduleName);

		$this->initializeListViewContents($request, $viewer);
		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORD_ACTIONS', $this->getRecordActionsFromModule($moduleModel));
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$moduleFields = $moduleModel->getFields();
		$fieldsInfo = array();
		foreach($moduleFields as $fieldName => $fieldModel){
			$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
		}
		$viewer->assign('ADV_SEARCH_FIELDS_INFO', json_encode($fieldsInfo));
		if($request->get('_onlyContents',false)){
			$viewer->view('UnifiedSearchResultsContents.tpl',$moduleName);
		}else{
			$viewer->view('UnifiedSearchResults.tpl', $moduleName);
		}
	}

	public function ShowListColumnsEdit(Vtiger_Request $request){
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$cvId = $request->get('cvid');
		$cvModel = CustomView_Record_Model::getInstanceById($cvId);

		$moduleModel = Vtiger_Module_Model::getInstance($request->get('source_module'));
		$recordStructureModel = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
		$recordStructure = $recordStructureModel->getStructure();

		$cvSelectedFields = $cvModel->getSelectedFields();

		$cvSelectedFieldModelsMapping = array();
		foreach ($recordStructure as $blockFields) {
			foreach ($blockFields as $field) {
				$cvSelectedFieldModelsMapping[$field->getCustomViewColumnName()] = $field;
			}
		}

		$selectedFields = array();
		foreach ($cvSelectedFields as $cvFieldName) {
			$selectedFields[$cvFieldName] = $cvSelectedFieldModelsMapping[$cvFieldName];
		}

		$viewer->assign('CV_MODEL',$cvModel);
		$viewer->assign('RECORD_STRUCTURE',$recordStructure);
		$viewer->assign('SELECTED_FIELDS',$selectedFields);
		$viewer->assign('MODULE',$moduleName);
		$viewer->view('ListColumnsEdit.tpl',$moduleName);
	}
	
	public function email_validation($email){
		$emailvalidation=preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email);
		return $emailvalidation;
	}

	public function email_search($searchValue,$searchModule=false){
		$db = PearDatabase::getInstance();
		$query = 'SELECT vtiger_emailslookup.crmid,vtiger_emailslookup.setype,vtiger_crmentity.createdtime ,vtiger_crmentity.label  FROM vtiger_emailslookup INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_emailslookup.crmid  AND vtiger_emailslookup.value LIKE ? ';
		$params = array("%$searchValue%");

		if($searchModule !== false) {
			$query .= ' AND vtiger_emailslookup.setype = ?';
			$params[] = $searchModule;
		}
		$result = $db->pquery($query, $params);
		$noOfRows = $db->num_rows($result);
		$moduleModels = $matchingRecords = $leadIdsList = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			if ($row['setype'] === 'Leads') {
				$leadIdsList[] = $row['crmid'];
			}
		}
		$convertedInfo = Leads_Module_Model::getConvertedInfo($leadIdsList);

		for($i=0, $recordsCount = 0; $i<$noOfRows && $recordsCount<100; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			if ($row['setype'] === 'Leads' && $convertedInfo[$row['crmid']]) {
				continue;
			}
			if(Users_Privileges_Model::isPermitted($row['setype'], 'DetailView', $row['crmid'])) {
				$row['id'] = $row['crmid'];
				$moduleName = $row['setype'];
				if(!array_key_exists($moduleName, $moduleModels)) {
					$moduleModels[$moduleName] = Vtiger_Module_Model::getInstance($moduleName);
				}
				$moduleModel = $moduleModels[$moduleName];
				$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Record', $moduleName);
				$recordInstance = new $modelClassName();
				$matchingRecords[$moduleName][$row['id']] = $recordInstance->setData($row)->setModuleFromInstance($moduleModel);
				$recordsCount++;
			}
		}		
		return $matchingRecords;
	}
   

	public function searchAll(Vtiger_Request $request, $for_mobile = false) {		
		$moduleName = $request->getModule(); 
		$searchValue = $request->get('value');
		$searchModule = $request->get('searchModule');
		# check the value is email or not
		$email_validation = $this->email_validation($searchValue);      
		if($email_validation=='1') { 
			if(!empty($searchModule))
                $email_searchresult = $this->email_search($searchValue,$searchModule);
            else
            	$email_searchresult = $this->email_search($searchValue);

            return $email_searchresult;
        }
		$range = array();
		$range['start'] = 0;

		$pageLimit = $this->getGlobalSearchPageLimit();
		$pagingModel = new Vtiger_Paging_Model();
		$pagingModel->set('range', $range);
		$pagingModel->set('limit', $pageLimit-1);
		
		// For Mobile API, search for specific module
            if(!empty($searchModule))   {
                    $matchingRecords = [];
	                $searchableModules = Vtiger_Module_Model::getSearchableModules();
        	    if(array_key_exists($searchModule, $searchableModules)) {
                	$searchedRecords = Vtiger_Record_Model::getSearchResult($searchValue, $searchModule);
	                $matchingRecords[$searchModule] = isset($searchedRecords[$searchModule]) ? $searchedRecords[$searchModule] : [];
        	    }
	            return $matchingRecords;
        	}
    
		$searchableModules = Vtiger_Module_Model::getSearchableModules();
		$matchingRecords = array();
		foreach ($searchableModules as $searchModule => $searchModuleModel) {
			$searchedRecords = Vtiger_Record_Model::getSearchResult($searchValue, $searchModule);
			if ($searchedRecords[$searchModule]) {
				$matchingRecords[$searchModule] = $searchedRecords[$searchModule];
			}
		}

		// Return if call is from Mobile API
                if($for_mobile)
                    return $matchingRecords;

		$matchingRecordsList = array();
		foreach ($matchingRecords as $module => $recordModelsList) {
			$recordsCount = count($recordModelsList);
			$recordModelsList = array_keys($recordModelsList);
			$recordModelsList = array_slice($recordModelsList, 0, $pageLimit);

			$customView = new CustomView();
			$cvId = $customView->getViewIdByName('All', $module);

			$listViewModel = Vtiger_ListView_Model::getInstance($module, $cvId);
			$listViewModel->listViewHeaders = $listViewModel->getListViewHeaders();
			$listViewModel->set('pageNumber', 1);

			$listviewPagingModel = clone $pagingModel;
			$listviewPagingModel->calculatePageRange($recordModelsList);
			$listViewModel->pagingModel = $listviewPagingModel;
			$listViewModel->recordsCount = $recordsCount;

			if (count($recordModelsList) == $pageLimit) {
				array_pop($recordModelsList);
			}

			$listViewEntries = array();
			foreach ($recordModelsList as $recordId) {
				$recordModel = Vtiger_Record_Model::getInstanceById($recordId, $listViewModel->getModule());
				$recordModel->setRawData($recordModel->getData());

				foreach ($listViewModel->listViewHeaders as $fieldName => $fieldModel) {
					$recordModel->set($fieldName, $fieldModel->getDisplayValue($recordModel->get($fieldName)));
				}
				$listViewModel->listViewEntries[$recordId] = $recordModel;
			}
			$matchingRecordsList[$module] = $listViewModel;
		}

		$viewer = $this->getViewer($request);
		$viewer->assign('SEARCH_VALUE', $searchValue);
		$viewer->assign('PAGE_NUMBER', 1);
		$viewer->assign('MATCHING_RECORDS', $matchingRecordsList);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());

		echo $viewer->view('SearchResults.tpl', '', true);
	}

	public function showSearchResultsWithValue(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$pageNumber = $request->get('page');
		$searchValue = $request->get('value');
		$recordsCount = $request->get('recordsCount');

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$nameFields = $moduleModel->getNameFields();
		$params = array();
		foreach ($nameFields as $fieldName) {
			$params[] = array($fieldName, 'c', $searchValue);
		}
		$searchParams[] = array();
		$searchParams[] = $params;
		$request->set('search_params', $searchParams);
		$request->set('orderby', $moduleModel->basetableid);

		$pageLimit = $this->getGlobalSearchPageLimit();
		$pagingModel = new Vtiger_Paging_Model();
		$pagingModel->set('limit', $pageLimit-1);
		$pagingModel->set('page', $pageNumber);

		$range = array();
		$previousPageRecordCount = (($pageNumber-1)*$pageLimit);
		$range['start'] = $previousPageRecordCount+1;
		$range['end'] = $previousPageRecordCount+$pageLimit;
		$pagingModel->set('range', $range);
		$this->pagingModel = $pagingModel;

		$customView = new CustomView();
		$this->viewName = $customView->getViewIdByName('All', $moduleName);

		$viewer = $this->getViewer($request);
		$this->initializeListViewContents($request, $viewer);

		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORDS_COUNT', $recordsCount);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('ModuleSearchResults.tpl', $moduleName);
	}

	public function getGlobalSearchPageLimit() {
		return 11;
	}
}
