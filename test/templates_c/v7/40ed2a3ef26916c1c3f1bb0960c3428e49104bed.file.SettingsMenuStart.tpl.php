<?php /* Smarty version Smarty-3.1.7, created on 2020-10-19 14:33:16
         compiled from "/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/Settings/ExtensionStore/SettingsMenuStart.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12969362705f8da3ac5b8fb0-26051200%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40ed2a3ef26916c1c3f1bb0960c3428e49104bed' => 
    array (
      0 => '/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/Settings/ExtensionStore/SettingsMenuStart.tpl',
      1 => 1572870387,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12969362705f8da3ac5b8fb0-26051200',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f8da3ac5e511',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f8da3ac5e511')) {function content_5f8da3ac5e511($_smarty_tpl) {?>


<?php echo $_smarty_tpl->getSubTemplate ("modules/Vtiger/partials/Topbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="container-fluid app-nav">
	<div class="row">
		<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("partials/SidebarHeader.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModuleHeader.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	</div>
</div>
</nav>    

<div class="main-container clearfix">
	<div class=" ExtensionscontentsDiv contentsDiv"><?php }} ?>