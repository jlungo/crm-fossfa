<?php /* Smarty version Smarty-3.1.7, created on 2021-03-25 10:29:39
         compiled from "/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Vtiger/AddDashBoardTabForm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1533667199605c66137bd4d9-00109822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35bc1a53316db650e0aae70c08ac719d01b6229a' => 
    array (
      0 => '/var/lib/crm-tcraccc/includes/runtime/../../layouts/v7/modules/Vtiger/AddDashBoardTabForm.tpl',
      1 => 1613657822,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1533667199605c66137bd4d9-00109822',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_605c66137cac8',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_605c66137cac8')) {function content_605c66137cac8($_smarty_tpl) {?>
<div class="modal-dialog modelContainer"><?php ob_start();?><?php echo vtranslate('LBL_ADD_DASHBOARD');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_tmp1), 0);?>
<div class="modal-content"><form id="AddDashBoardTab" name="AddDashBoardTab" method="post" action="index.php"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
"/><input type="hidden" name="action" value="DashBoardTab"/><input type="hidden" name="mode" value="addTab"/><div class="modal-body clearfix"><div class="col-lg-5"><label class="control-label pull-right marginTop5px"><?php echo vtranslate('LBL_TAB_NAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label></div><div class="col-lg-6"><input type="text" name="tabName" data-rule-required="true" size="25" class="inputElement" maxlength='30'/></div><div class="col-lg-12" style='margin-top: 10px; padding: 5px;'><div class="alert-info"><center><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?php echo vtranslate('LBL_MAX_CHARACTERS_ALLOWED_DASHBOARD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</center></div></div></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalFooter.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form></div></div>
<?php }} ?>