<?php /* Smarty version Smarty-3.1.7, created on 2020-11-06 08:31:29
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/MailManager/SettingsEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11419279565fa509e164d3a8-30555510%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '34e7c23001cb36bc2ec197c7c76a502059bfb09b' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/MailManager/SettingsEdit.tpl',
      1 => 1584185178,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11419279565fa509e164d3a8-30555510',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MAILBOX' => 0,
    'MODULE' => 0,
    'SOURCE_MODULE' => 0,
    'MODAL_TITLE' => 0,
    'SERVERNAME' => 0,
    'FOLDERS' => 0,
    'FOLDER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5fa509e16be04',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5fa509e16be04')) {function content_5fa509e16be04($_smarty_tpl) {?>

<div class="modal-dialog modal-md mapcontainer"><div class="modal-content"><?php if ($_smarty_tpl->tpl_vars['MAILBOX']->value->exists()){?><?php $_smarty_tpl->tpl_vars['MODAL_TITLE'] = new Smarty_variable(vtranslate('LBL_EDIT_MAILBOX',$_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['MODAL_TITLE'] = new Smarty_variable(vtranslate('LBL_CREATE_MAILBOX',$_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php }?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['SOURCE_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['MODAL_TITLE']->value), 0);?>
<form id="EditView" method="POST"><div class="modal-body" id="mmSettingEditModal"><table class="table table-borderless"><tbody><tr><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_SELECT_ACCOUNT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><select id="serverType" class="select2 col-lg-9"><option></option><option value='gmail' <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value=='gmail'){?> selected <?php }?>><?php echo vtranslate('JSLBL_Gmail',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value='yahoo' <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value=='yahoo'){?> selected <?php }?>><?php echo vtranslate('JSLBL_Yahoo',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value='fastmail' <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value=='fastmail'){?> selected <?php }?>><?php echo vtranslate('JSLBL_Fastmail',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value='other' <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value=='other'){?> selected <?php }?>><?php echo vtranslate('JSLBL_Other',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></td></tr><tr class="settings_details <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value==''){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><font color="red">*</font> <?php echo vtranslate('LBL_Mail_Server',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input name="_mbox_server" id="_mbox_server" class="inputElement width75per" value="<?php echo $_smarty_tpl->tpl_vars['MAILBOX']->value->server();?>
" type="text" placeholder="mail.company.com or 192.168.X.X"></td></tr><tr class="settings_details <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value==''){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><font color="red">*</font> <?php echo vtranslate('LBL_Username',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input name="_mbox_user" class="inputElement width75per" id="_mbox_user" value="<?php echo $_smarty_tpl->tpl_vars['MAILBOX']->value->username();?>
" type="text" placeholder="<?php echo vtranslate('LBL_Your_Mailbox_Account',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"></td></tr><tr class="settings_details <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value==''){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><font color="red">*</font> <?php echo vtranslate('LBL_Password',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input name="_mbox_pwd" class="inputElement width75per" id="_mbox_pwd" value="<?php echo $_smarty_tpl->tpl_vars['MAILBOX']->value->password();?>
" type="password" placeholder="<?php echo vtranslate('LBL_Account_Password',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"></td></tr><tr class="additional_settings <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value!='other'){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_Protocol',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input type="radio" name="_mbox_protocol" class="mbox_protocol" value="IMAP2" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->protocol(),'imap2')===0){?>checked=true<?php }?>> <?php echo vtranslate('LBL_Imap2',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<input type="radio" name="_mbox_protocol" class="mbox_protocol" value="IMAP4" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->protocol(),'imap4')===0){?>checked=true<?php }?> style="margin-left: 10px;"> <?php echo vtranslate('LBL_Imap4',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td></tr><tr class="additional_settings <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value!='other'){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_SSL_Options',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input type="radio" name="_mbox_ssltype" class="mbox_ssltype" value="notls" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->ssltype(),'notls')===0){?>checked=true<?php }?>> <?php echo vtranslate('LBL_No_TLS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<input type="radio" name="_mbox_ssltype" class="mbox_ssltype" value="tls" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->ssltype(),'tls')===0){?>checked=true<?php }?> style="margin-left: 10px;"> <?php echo vtranslate('LBL_TLS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<input type="radio" name="_mbox_ssltype" class="mbox_ssltype" value="ssl" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->ssltype(),'ssl')===0){?>checked=true<?php }?>  style="margin-left: 10px;"> <?php echo vtranslate('LBL_SSL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td></tr><tr class="additional_settings <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value!='other'){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_Certificate_Validations',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><input type="radio" name="_mbox_certvalidate" class="mbox_certvalidate" value="validate-cert" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->certvalidate(),'validate-cert')===0){?>checked=true<?php }?> > <?php echo vtranslate('LBL_Validate_Cert',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<input type="radio" name="_mbox_certvalidate" class="mbox_certvalidate" value="novalidate-cert" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->certvalidate(),'novalidate-cert')===0){?>checked=true<?php }?> style="margin-left: 10px;"> <?php echo vtranslate('LBL_Do_Not_Validate_Cert',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td></tr><tr class="refresh_settings <?php if ($_smarty_tpl->tpl_vars['MAILBOX']->value&&$_smarty_tpl->tpl_vars['MAILBOX']->value->exists()){?><?php }else{ ?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_REFRESH_TIME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue"><select name="_mbox_refresh_timeout" class="select2 col-lg-9"><option value="" <?php if ($_smarty_tpl->tpl_vars['MAILBOX']->value->refreshTimeOut()==''){?>selected<?php }?>><?php echo vtranslate('LBL_NONE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="300000" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->refreshTimeOut(),'300000')==0){?>selected<?php }?>><?php echo vtranslate('LBL_5_MIN',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="600000" <?php if (strcasecmp($_smarty_tpl->tpl_vars['MAILBOX']->value->refreshTimeOut(),'600000')==0){?>selected<?php }?>><?php echo vtranslate('LBL_10_MIN',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></td></tr><tr class="settings_details <?php if ($_smarty_tpl->tpl_vars['SERVERNAME']->value==''){?>hide<?php }?>"><td class="fieldLabel width40per"><label class="pull-right detailViewButtoncontainer"><?php echo vtranslate('LBL_SAVE_SENT_MAILS_IN',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue selectFolderValue <?php if (!$_smarty_tpl->tpl_vars['MAILBOX']->value->exists()){?>hide<?php }?>"><select name="_mbox_sent_folder" class="select2 col-lg-9"><?php  $_smarty_tpl->tpl_vars['FOLDER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FOLDER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER']->key => $_smarty_tpl->tpl_vars['FOLDER']->value){
$_smarty_tpl->tpl_vars['FOLDER']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" <?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->name()==$_smarty_tpl->tpl_vars['MAILBOX']->value->folder()){?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
</option><?php } ?></select><i class="fa fa-info-circle cursorPointer" id="mmSettingInfo" title="<?php echo vtranslate('LBL_CHOOSE_FOLDER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"></i></td><td class="fieldValue selectFolderDesc alert alert-info <?php if ($_smarty_tpl->tpl_vars['MAILBOX']->value->exists()){?>hide<?php }?>"><?php echo vtranslate('LBL_CHOOSE_FOLDER_DESC',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td></tr></tbody></table></div><div class="modal-footer"><?php if ($_smarty_tpl->tpl_vars['MAILBOX']->value->exists()){?><button class="btn btn-danger" id="deleteMailboxBtn"><strong><?php echo vtranslate('LBL_DELETE_Mailbox',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php }?><button class="btn btn-success" id="saveMailboxBtn" type="submit" name="saveButton"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><a href="#" class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div></form></div></div>
<?php }} ?>