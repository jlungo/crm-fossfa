<?php /* Smarty version Smarty-3.1.7, created on 2021-03-04 11:16:56
         compiled from "/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Settings/SharingAccess/Index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21242997146040c1a81bf762-37288934%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3e4daa279b71c7c39b7dcdfb63a34d2830fe14a' => 
    array (
      0 => '/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Settings/SharingAccess/Index.tpl',
      1 => 1613657822,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21242997146040c1a81bf762-37288934',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DEPENDENT_MODULES' => 0,
    'QUALIFIED_MODULE' => 0,
    'ALL_ACTIONS' => 0,
    'ACTION_MODEL' => 0,
    'ALL_MODULES' => 0,
    'MODULE_MODEL' => 0,
    'TABID' => 0,
    'ACTION_ID' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_6040c1a81d886',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6040c1a81d886')) {function content_6040c1a81d886($_smarty_tpl) {?>




<div class="listViewPageDiv " id="sharingAccessContainer"><div class="col-sm-12 col-xs-12"><form name="EditSharingAccess" action="index.php" method="post" class="form-horizontal" id="EditSharingAccess"><input type="hidden" name="module" value="SharingAccess" /><input type="hidden" name="action" value="SaveAjax" /><input type="hidden" name="parent" value="Settings" /><input type="hidden" class="dependentModules" value='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['DEPENDENT_MODULES']->value);?>
' /><br><div class="contents"><table class="table table-bordered table-condensed sharingAccessDetails marginBottom50px"><colgroup><col width="20%"><col width="15%"><col width="15%"><col width="20%"><col width="10%"><col width="20%"></colgroup><thead><tr class="blockHeader"><th><?php echo vtranslate('LBL_MODULE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</th><?php  $_smarty_tpl->tpl_vars['ACTION_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ACTION_MODEL']->_loop = false;
 $_smarty_tpl->tpl_vars['ACTION_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ALL_ACTIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ACTION_MODEL']->key => $_smarty_tpl->tpl_vars['ACTION_MODEL']->value){
$_smarty_tpl->tpl_vars['ACTION_MODEL']->_loop = true;
 $_smarty_tpl->tpl_vars['ACTION_ID']->value = $_smarty_tpl->tpl_vars['ACTION_MODEL']->key;
?><th><?php echo vtranslate($_smarty_tpl->tpl_vars['ACTION_MODEL']->value->getName(),$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</th><?php } ?><th nowrap="nowrap"><?php echo vtranslate('LBL_ADVANCED_SHARING_RULES',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</th></tr></thead><tbody><tr data-module-name="Calendar"><td><?php echo vtranslate('SINGLE_Calendar','Calendar');?>
</td><td class=""><center><div><input type="radio" disabled="disabled" /></div></center></td><td class=""><center><div><input type="radio" disabled="disabled" /></div></center></td><td class=""><center><div><input type="radio" disabled="disabled" /></div></center></td><td class=""><center><div><input type="radio" checked="true" disabled="disabled" /></div></center></td><td><div class="row"><span class="col-sm-4">&nbsp;</span><span class="col-sm-4"><button type="button" class="btn btn-sm btn-default vtButton arrowDown row-fluid" disabled="disabled" style="padding-right: 20px; padding-left: 20px;"><i class="fa fa-chevron-down"></i></button></span></div></td></tr><?php  $_smarty_tpl->tpl_vars['MODULE_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MODULE_MODEL']->_loop = false;
 $_smarty_tpl->tpl_vars['TABID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ALL_MODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MODULE_MODEL']->key => $_smarty_tpl->tpl_vars['MODULE_MODEL']->value){
$_smarty_tpl->tpl_vars['MODULE_MODEL']->_loop = true;
 $_smarty_tpl->tpl_vars['TABID']->value = $_smarty_tpl->tpl_vars['MODULE_MODEL']->key;
?><tr data-module-name="<?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('name');?>
"><td><?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->get('label'),$_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getName());?>
</td><?php  $_smarty_tpl->tpl_vars['ACTION_MODEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ACTION_MODEL']->_loop = false;
 $_smarty_tpl->tpl_vars['ACTION_ID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ALL_ACTIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ACTION_MODEL']->key => $_smarty_tpl->tpl_vars['ACTION_MODEL']->value){
$_smarty_tpl->tpl_vars['ACTION_MODEL']->_loop = true;
 $_smarty_tpl->tpl_vars['ACTION_ID']->value = $_smarty_tpl->tpl_vars['ACTION_MODEL']->key;
?><td class=""><?php if ($_smarty_tpl->tpl_vars['ACTION_MODEL']->value->isModuleEnabled($_smarty_tpl->tpl_vars['MODULE_MODEL']->value)){?><center><div><input type="radio" name="permissions[<?php echo $_smarty_tpl->tpl_vars['TABID']->value;?>
]" data-action-state="<?php echo $_smarty_tpl->tpl_vars['ACTION_MODEL']->value->getName();?>
" value="<?php echo $_smarty_tpl->tpl_vars['ACTION_ID']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getPermissionValue()==$_smarty_tpl->tpl_vars['ACTION_ID']->value){?>checked="true"<?php }?>></div></center><?php }?></td><?php } ?><td class="triggerCustomSharingAccess"><div class="row"><span class="col-sm-4">&nbsp;</span><span class="col-sm-4"><button type="button" class="btn btn-sm btn-default vtButton" data-handlerfor="fields" data-togglehandler="<?php echo $_smarty_tpl->tpl_vars['TABID']->value;?>
-rules" style="padding-right: 20px; padding-left: 20px;"><i class="fa fa-chevron-down"></i></button></span></div></td></tr><?php } ?></tbody></table></div><div class='modal-overlay-footer clearfix saveSharingAccess hide'><div class="row clearfix"><div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '><button class="btn btn-success saveButton" name="saveButton" type="submit"><?php echo vtranslate('LBL_APPLY_NEW_SHARING_RULES',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</button>&nbsp;&nbsp;</div></div></div></form></div></div>
<?php }} ?>