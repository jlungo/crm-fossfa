<?php /* Smarty version Smarty-3.1.7, created on 2020-10-29 06:29:53
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Settings/Tags/ListViewContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16063467025f9a61617fa150-63143623%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db682233356afac455b003edf3360829934966b6' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Settings/Tags/ListViewContents.tpl',
      1 => 1572870387,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16063467025f9a61617fa150-63143623',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'MODULE' => 0,
    'HEADER_TITLE' => 0,
    'BUTTON_ID' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f9a616180d41',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f9a616180d41')) {function content_5f9a616180d41($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ListViewContents.tpl",'Settings:Vtiger'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div id="editTagContainer" class="hide modal-dialog modelContainer"><input type="hidden" name="id" value="" /><?php ob_start();?><?php echo vtranslate('LBL_EDIT_TAG',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars["HEADER_TITLE"] = new Smarty_variable($_tmp1, null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<div class="modal-content"><div class="editTagContents col-lg-12 modal-body"><div class='col-lg-4'></div><div class='col-lg-8'><input type="text" name="tagName" class='inputElement' value=""/><div class="checkbox"><label><input type="hidden" name="visibility" value="<?php echo Vtiger_Tag_Model::PRIVATE_TYPE;?>
"/><input type="checkbox" name="visibility" value="<?php echo Vtiger_Tag_Model::PUBLIC_TYPE;?>
" style="vertical-align: text-top;"/>&nbsp; <?php echo vtranslate('LBL_SHARE_TAGS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></div></div></div><div class="modal-footer col-lg-12"><center><button <?php if ($_smarty_tpl->tpl_vars['BUTTON_ID']->value!=null){?> id="<?php echo $_smarty_tpl->tpl_vars['BUTTON_ID']->value;?>
" <?php }?> class="btn btn-success saveTag" type="submit" name="saveButton"><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button><a href="#" class="cancelLink cancelSaveTag" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></center></div></div></div><?php }} ?>