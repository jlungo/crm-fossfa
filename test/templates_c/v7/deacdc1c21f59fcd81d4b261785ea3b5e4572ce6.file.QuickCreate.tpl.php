<?php /* Smarty version Smarty-3.1.7, created on 2021-03-19 17:06:12
         compiled from "/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Documents/QuickCreate.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7343651426054da04a3c990-12872564%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'deacdc1c21f59fcd81d4b261785ea3b5e4572ce6' => 
    array (
      0 => '/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Documents/QuickCreate.tpl',
      1 => 1613657822,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7343651426054da04a3c990-12872564',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SCRIPTS' => 0,
    'jsModel' => 0,
    'FILE_LOCATION_TYPE' => 0,
    'MODULE' => 0,
    'FIELDS_INFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6054da04a4d74',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6054da04a4d74')) {function content_6054da04a4d74($_smarty_tpl) {?>

<?php  $_smarty_tpl->tpl_vars['jsModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['jsModel']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SCRIPTS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['jsModel']->key => $_smarty_tpl->tpl_vars['jsModel']->value){
$_smarty_tpl->tpl_vars['jsModel']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['jsModel']->key;
?><script type="<?php echo $_smarty_tpl->tpl_vars['jsModel']->value->getType();?>
" src="<?php echo $_smarty_tpl->tpl_vars['jsModel']->value->getSrc();?>
"></script><?php } ?><?php if ($_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value=='I'){?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("UploadDocument.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }else{ ?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("CreateDocument.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['FIELDS_INFO']->value!=null){?><script type="text/javascript">var quickcreate_uimeta = (function () {var fieldInfo = <?php echo $_smarty_tpl->tpl_vars['FIELDS_INFO']->value;?>
;return {field: {get: function (name, property) {if (name && property === undefined) {return fieldInfo[name];}if (name && property) {return fieldInfo[name][property]}},isMandatory: function (name) {if (fieldInfo[name]) {return fieldInfo[name].mandatory;}return false;},getType: function (name) {if (fieldInfo[name]) {return fieldInfo[name].type}return false;}}};})();</script><?php }?></div>
<?php }} ?>