<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.2
 * ("License.txt"); You may not use this file except in compliance with the License
 * The Original Code is: Vtiger CRM Open Source
 * The Initial Developer of the Original Code is Vtiger.
 * Portions created by Vtiger are Copyright (C) Vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class Portal_Ping_API extends Portal_Default_API {

	public function process(Portal_Request $request) {
		$response = new Portal_Response();
		$response->setResult(Vtiger_Connector::getInstance()->authentication());
		return $response;
	}

}
