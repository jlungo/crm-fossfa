<?php /* Smarty version Smarty-3.1.7, created on 2021-03-01 05:21:44
         compiled from "/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Vtiger/partials/SidebarAppMenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:520044205603c79e87a8a40-17846406%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'abe30984bd346a667748ba8fa5704afd6c4daa15' => 
    array (
      0 => '/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Vtiger/partials/SidebarAppMenu.tpl',
      1 => 1613657822,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '520044205603c79e87a8a40-17846406',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DASHBOARD_MODULE_MODEL' => 0,
    'USER_PRIVILEGES_MODEL' => 0,
    'HOME_MODULE_MODEL' => 0,
    'MODULE' => 0,
    'DOCUMENTS_MODULE_MODEL' => 0,
    'APP_LIST' => 0,
    'APP_NAME' => 0,
    'USER_MODEL' => 0,
    'MAILMANAGER_MODULE_MODEL' => 0,
    'APP_GROUPED_MENU' => 0,
    'APP_MENU_MODEL' => 0,
    'FIRST_MENU_MODEL' => 0,
    'APP_IMAGE_MAP' => 0,
    'moduleModel' => 0,
    'moduleName' => 0,
    'translatedModuleLabel' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_603c79e87ec9b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_603c79e87ec9b')) {function content_603c79e87ec9b($_smarty_tpl) {?>

<div class="app-menu hide" id="app-menu">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-xs-2 cursorPointer app-switcher-container">
				<div class="row app-navigator">
					<span id="menu-toggle-action" class="app-icon fa fa-bars"></span>
				</div>
			</div>
		</div>
		<?php $_smarty_tpl->tpl_vars['USER_PRIVILEGES_MODEL'] = new Smarty_variable(Users_Privileges_Model::getCurrentUserPrivilegesModel(), null, 0);?>
		<?php $_smarty_tpl->tpl_vars['HOME_MODULE_MODEL'] = new Smarty_variable(Vtiger_Module_Model::getInstance('Home'), null, 0);?>
		<?php $_smarty_tpl->tpl_vars['DASHBOARD_MODULE_MODEL'] = new Smarty_variable(Vtiger_Module_Model::getInstance('Dashboard'), null, 0);?>
		<div class="app-list row">
			<?php if ($_smarty_tpl->tpl_vars['USER_PRIVILEGES_MODEL']->value->hasModulePermission($_smarty_tpl->tpl_vars['DASHBOARD_MODULE_MODEL']->value->getId())){?>
				<div class="menu-item app-item dropdown-toggle" data-default-url="<?php echo $_smarty_tpl->tpl_vars['HOME_MODULE_MODEL']->value->getDefaultUrl();?>
">
					<div class="menu-items-wrapper">
						<span class="app-icon-list fa fa-dashboard"></span>
						<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('LBL_DASHBOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span>
					</div>
				</div>
			<?php }?>
			<div class="menu-item app-item app-item-misc" data-default-url="index.php?module=HelpDesk&view=List&app=SUPPORT">
				<div class="menu-items-wrapper">
				    <i class="vicon-helpdesk" title="Tickets"></i>
					<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('Tickets');?>
</span>
				</div>
			</div>

			<?php $_smarty_tpl->tpl_vars['DOCUMENTS_MODULE_MODEL'] = new Smarty_variable(Vtiger_Module_Model::getInstance('Documents'), null, 0);?>
			<?php if ($_smarty_tpl->tpl_vars['USER_PRIVILEGES_MODEL']->value->hasModulePermission($_smarty_tpl->tpl_vars['DOCUMENTS_MODULE_MODEL']->value->getId())){?>
				<div class="menu-item app-item app-item-misc" data-default-url="index.php?module=Documents&view=List">
					<div class="menu-items-wrapper">
						<span class="app-icon-list fa"><?php echo $_smarty_tpl->tpl_vars['DOCUMENTS_MODULE_MODEL']->value->getModuleIcon();?>
</span>
						<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('Documents');?>
</span>
					</div>
				</div>
			<?php }?>	
      
			<?php $_smarty_tpl->tpl_vars['APP_GROUPED_MENU'] = new Smarty_variable(Settings_MenuEditor_Module_Model::getAllVisibleModules(), null, 0);?>
			<?php $_smarty_tpl->tpl_vars['APP_LIST'] = new Smarty_variable(Vtiger_MenuStructure_Model::getAppMenuList(), null, 0);?>
			<?php  $_smarty_tpl->tpl_vars['APP_NAME'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['APP_NAME']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['APP_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['APP_NAME']->key => $_smarty_tpl->tpl_vars['APP_NAME']->value){
$_smarty_tpl->tpl_vars['APP_NAME']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='ANALYTICS'){?> <?php continue 1?><?php }?>
			<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>
				<?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='COMMUNICATION'){?>
					<div class="dropdown app-modules-dropdown-container dropdown-compact">
					<div class="menu-item app-item dropdown-toggle app-item-misc" data-app-name="TOOLS" id="TOOLS_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>index.php?module=Vtiger&parent=Settings&view=Index<?php }else{ ?>index.php?module=Users&view=Settings<?php }?>">
						<div class="menu-items-wrapper app-menu-items-wrapper">
							<i class="fa fa-inbox" aria-hidden="true"></i>
							<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('COMMUNICATIONS');?>
</span>
							<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>
								<span class="fa fa-chevron-right pull-right"></span>
							<?php }?>
						</div>
					</div>
					<?php $_smarty_tpl->tpl_vars['MAILMANAGER_MODULE_MODEL'] = new Smarty_variable(Vtiger_Module_Model::getInstance('MailManager'), null, 0);?>
					<ul class="dropdown-menu app-modules-dropdown dropdown-modules-compact" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
_modules_dropdownMenu" data-height="0.27">
					    <li>
							<a href="index.php?module=SMSNotifier&amp;view=List&amp;app=SUPPORT" title="SMS Notifier">
								<span class="module-icon"><i class="vicon-smsnotifier" title="SMS Notifier"></i></span>
								<span class="module-name textOverflowEllipsis"> SMS Notifier</span>
							</a>
						</li>
						<li>
							<a href="index.php?module=MailManager&view=List">
							<span class="app-icon-list fa"><?php echo $_smarty_tpl->tpl_vars['MAILMANAGER_MODULE_MODEL']->value->getModuleIcon();?>
</span>
							<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('MailManager');?>
</span>
							</a>
						</li>
						<li>
							<a href="index.php?module=EmailTemplates&amp;view=List&amp;app=TOOLS" title="Email Templates">
								<span class="module-icon"><i class="vicon-emailtemplates" title="Email Templates"></i></span>
								<span class="module-name textOverflowEllipsis"> Email Templates</span>
							</a>
						</li>
					</ul>
				</div>
		        <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='CALENDAR'){?>
				<div class="dropdown app-modules-dropdown-container dropdown-compact">
					<div class="menu-item app-item dropdown-toggle app-item-misc" data-app-name="TOOLS" id="TOOLS_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>index.php?module=Vtiger&parent=Settings&view=Index<?php }else{ ?>index.php?module=Users&view=Settings<?php }?>">
						<div class="menu-items-wrapper app-menu-items-wrapper">
							<i class="fa fa-calendar" aria-hidden="true"></i>
							<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('CALENDAR');?>
</span>
							<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>
								<span class="fa fa-chevron-right pull-right"></span>
							<?php }?>
						</div>
					</div>
		            <ul class="dropdown-menu app-modules-dropdown dropdown-modules-compact" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
_modules_dropdownMenu" data-height="0.27">
				       <li>
				       <a href="index.php?module=Calendar&amp;view=Calendar" title="Email Templates">
							<span class="module-icon"><i class="vicon-emailtemplates" title="Email Templates"></i></span>
							<span class="module-name textOverflowEllipsis"> Calendar</span>
						</a></li>
					</ul>
				</div>
		        <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='SETTINGS'){?>
		        <div class="dropdown app-modules-dropdown-container dropdown-compact">
					<div class="menu-item app-item dropdown-toggle app-item-misc" data-app-name="TOOLS" id="TOOLS_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>index.php?module=Vtiger&parent=Settings&view=Index<?php }else{ ?>index.php?module=Users&view=Settings<?php }?>">
						<div class="menu-items-wrapper app-menu-items-wrapper">
							<span class="app-icon-list fa fa-cog"></span>
							<span class="app-name textOverflowEllipsis"> <?php echo vtranslate('LBL_SETTINGS','Settings:Vtiger');?>
</span>
							<?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?>
								<span class="fa fa-chevron-right pull-right"></span>
							<?php }?>
						</div>
					</div>
					<ul class="dropdown-menu app-modules-dropdown dropdown-modules-compact" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
_modules_dropdownMenu" data-height="0.27">
						<li>
							<a href="?module=Vtiger&parent=Settings&view=Index">
								<span class="fa fa-cog module-icon"></span>
								<span class="module-name textOverflowEllipsis"> <?php echo vtranslate('LBL_CRM_SETTINGS','Vtiger');?>
</span>
							</a>
						</li>
						<li>
							<a href="?module=Users&parent=Settings&view=List">
								<span class="fa fa-user module-icon"></span>
								<span class="module-name textOverflowEllipsis"> <?php echo vtranslate('LBL_MANAGE_USERS','Vtiger');?>
</span>
							</a>
						</li>
					</ul>
				</div>
			<?php }?>
		    <?php }?>
             <?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='SETTINGS'){?><?php continue 1?><?php }?>
				<?php if (count($_smarty_tpl->tpl_vars['APP_GROUPED_MENU']->value[$_smarty_tpl->tpl_vars['APP_NAME']->value])>0){?>
					<div class="dropdown app-modules-dropdown-container">
						<?php  $_smarty_tpl->tpl_vars['APP_MENU_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['APP_MENU_MODEL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['APP_GROUPED_MENU']->value[$_smarty_tpl->tpl_vars['APP_NAME']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['APP_MENU_MODEL']->key => $_smarty_tpl->tpl_vars['APP_MENU_MODEL']->value){
$_smarty_tpl->tpl_vars['APP_MENU_MODEL']->_loop = true;
?>
							<?php $_smarty_tpl->tpl_vars['FIRST_MENU_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['APP_MENU_MODEL']->value, null, 0);?>
							<?php if ($_smarty_tpl->tpl_vars['APP_MENU_MODEL']->value){?>
								<?php break 1?>
							<?php }?>
						<?php } ?>
						<div class="menu-item app-item dropdown-toggle app-item-color-<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
" data-app-name="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="<?php echo $_smarty_tpl->tpl_vars['FIRST_MENU_MODEL']->value->getDefaultUrl();?>
&app=<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
">
							<div class="menu-items-wrapper app-menu-items-wrapper">
								<span class="app-icon-list fa <?php echo $_smarty_tpl->tpl_vars['APP_IMAGE_MAP']->value[$_smarty_tpl->tpl_vars['APP_NAME']->value];?>
"></span>
								<?php if ($_smarty_tpl->tpl_vars['APP_NAME']->value=='MARKETING'){?> 
								<span class="app-name textOverflowEllipsis"> 
								<?php echo vtranslate("INDIVIDUALS");?>
</span>
								<?php }elseif($_smarty_tpl->tpl_vars['APP_NAME']->value=='SUPPORT'){?>
								<span class="app-name textOverflowEllipsis"> 
								<?php echo vtranslate("HELP");?>
</span>
								<?php }else{ ?>
								<span class="app-name textOverflowEllipsis"> 
								<?php echo vtranslate("LBL_".($_smarty_tpl->tpl_vars['APP_NAME']->value));?>
</span>
								<?php }?>								
								<span class="fa fa-chevron-right pull-right"></span>
							</div>
						</div>
						<ul class="dropdown-menu app-modules-dropdown" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
_modules_dropdownMenu">
							<?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['APP_GROUPED_MENU']->value[$_smarty_tpl->tpl_vars['APP_NAME']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
?>
								<?php $_smarty_tpl->tpl_vars['translatedModuleLabel'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['moduleModel']->value->get('label'),$_smarty_tpl->tpl_vars['moduleName']->value), null, 0);?>
								<li>
									<a href="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getDefaultUrl();?>
&app=<?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
">
										<span class="module-icon"><?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getModuleIcon();?>
</span>
										<span class="module-name textOverflowEllipsis"> <?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
</span>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php }?>
			<?php } ?>
				
			
			
				
		</div>
	</div>
</div>

<?php }} ?>