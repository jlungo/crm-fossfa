API

	This is the API for the Open source Vtiger CRM. By using this , you can manage your CRM with mobile application.
	
Core file changes

	1) libraries/htmlpurifier/library/HTMLPurifier.auto.php
	2) include/utils/VtlibUtils.php
	3) modules/Vtiger/views/ListAjax.php

1) Slim frame work used for API. Auto load functionality available in both vtiger crm and slim frame work. That's why any one of the autoload function have to be enabled and another one have to be disabled. So we made changes on "HTMLPurifier.auto.php" .

2) Function added to get the module names with section name. It will get data from vtiger_app2tab.

3) Small parameter added to get the list view entries for mobile api.

Installation Process

	1) Download the zip file of vtiger graphql.
	2) Unzip that file in the root of your vtiger instance. The files will be replaced.
	3) Now install Vibe on your mobile. Give your vtiger instance URL, User name and password.
	4) After the completion of authentication , you can see the records present in your CRM.
