<?php /* Smarty version Smarty-3.1.7, created on 2020-10-26 19:22:00
         compiled from "/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/MailManager/FolderOpen.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9183401915f9721d8ecc2a4-60996165%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '006c1b61e5d450f0202ac16976950a57f7ecc375' => 
    array (
      0 => '/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/MailManager/FolderOpen.tpl',
      1 => 1584185178,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9183401915f9721d8ecc2a4-60996165',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FOLDER' => 0,
    'MODULE' => 0,
    'FOLDERLIST' => 0,
    'folder' => 0,
    'QUERY' => 0,
    'SEARCHOPTIONS' => 0,
    'arr' => 0,
    'TYPE' => 0,
    'option' => 0,
    'MAIL' => 0,
    'IS_READ' => 0,
    'IS_SENT_FOLDER' => 0,
    'SUBJECT' => 0,
    'DISPLAY_NAME' => 0,
    'ATTACHMENT' => 0,
    'INLINE_ATTCH' => 0,
    'ATTCHMENT_COUNT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f9721d90fefd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f9721d90fefd')) {function content_5f9721d90fefd($_smarty_tpl) {?>
<div class='col-lg-12 padding0px'><span class="col-lg-1 paddingLeft5px"><input type='checkbox' id='mainCheckBox' class="pull-left"></span><span class="col-lg-5 padding0px"><span class="fa-stack fa-sm cursorPointer mmActionIcon" id="mmMarkAsRead" data-folder="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" title="<?php echo vtranslate('LBL_MARK_AS_READ',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><img src="layouts/v7/skins/images/envelope-open.png" id="mmEnvelopeOpenIcon"></span><span class="fa-stack fa-sm cursorPointer mmActionIcon" id="mmMarkAsUnread" data-folder="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" title="<?php echo vtranslate('LBL_Mark_As_Unread',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-envelope fa-stack-lg"></i></span><span class="fa-stack fa-sm cursorPointer mmActionIcon" id="mmDeleteMail" data-folder="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" title="<?php echo vtranslate('LBL_Delete',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-trash-o fa-stack-lg"></i></span><span class="fa-stack fa-sm cursorPointer moveToFolderDropDown more dropdown action" title="<?php echo vtranslate('LBL_MOVE_TO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><span class='dropdown-toggle' data-toggle="dropdown"><i class="fa fa-folder mmMoveDropdownFolder"></i><i class="fa fa-arrow-right mmMoveDropdownArrow"></i><i class="fa fa-caret-down pull-right mmMoveDropdownCaret"></i></span><ul class="dropdown-menu" id="mmMoveToFolder"><?php  $_smarty_tpl->tpl_vars['folder'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['folder']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDERLIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['folder']->key => $_smarty_tpl->tpl_vars['folder']->value){
$_smarty_tpl->tpl_vars['folder']->_loop = true;
?><li data-folder="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" data-movefolder='<?php echo $_smarty_tpl->tpl_vars['folder']->value;?>
'><a class="paddingLeft15"><?php if (mb_strlen($_smarty_tpl->tpl_vars['folder']->value,'UTF-8')>20){?><?php echo mb_substr($_smarty_tpl->tpl_vars['folder']->value,0,20,'UTF-8');?>
...<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['folder']->value;?>
<?php }?></a></li><?php } ?></ul></span></span><span class="col-lg-6 padding0px"><span class="pull-right"><?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->mails()){?><span class="pageInfo"><?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->pageInfo();?>
&nbsp;&nbsp;</span> <span class="pageInfoData" data-start="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->getStartCount();?>
" data-end="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->getEndCount();?>
" data-total="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->count();?>
" data-label-of="<?php echo vtranslate('LBL_OF');?>
"></span><?php }?><button type="button" id="PreviousPageButton" class="btn btn-default marginRight0px" <?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->hasPrevPage()){?>data-folder='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
' data-page='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->pageCurrent(-1);?>
'<?php }else{ ?>disabled="disabled"<?php }?>><i class="fa fa-caret-left"></i></button><button type="button" id="NextPageButton" class="btn btn-default" <?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->hasNextPage()){?>data-folder='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
' data-page='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->pageCurrent(1);?>
'<?php }else{ ?>disabled="disabled"<?php }?>><i class="fa fa-caret-right"></i></button></span></span></div><div class='col-lg-12 padding0px'><div class="col-lg-10 mmSearchContainerOther"><div><div class="input-group col-lg-8 padding0px"><input type="text" class="form-control" id="mailManagerSearchbox" aria-describedby="basic-addon2" value="<?php echo $_smarty_tpl->tpl_vars['QUERY']->value;?>
" data-foldername='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
' placeholder="<?php echo vtranslate('LBL_TYPE_TO_SEARCH',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"></div><div class="col-lg-4 padding0px mmSearchDropDown"><select id="searchType" style="background: #DDDDDD url('layouts/v7/skins/images/arrowdown.png') no-repeat 95% 40%; padding-left: 9px;"><?php  $_smarty_tpl->tpl_vars['arr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['arr']->_loop = false;
 $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SEARCHOPTIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['arr']->key => $_smarty_tpl->tpl_vars['arr']->value){
$_smarty_tpl->tpl_vars['arr']->_loop = true;
 $_smarty_tpl->tpl_vars['option']->value = $_smarty_tpl->tpl_vars['arr']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['arr']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['arr']->value==$_smarty_tpl->tpl_vars['TYPE']->value){?>selected<?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['option']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php } ?></select></div></div></div><div class='col-lg-2' id="mmSearchButtonContainer"><button id='mm_searchButton' class="pull-right"><?php echo vtranslate('LBL_Search',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div></div><?php if ($_smarty_tpl->tpl_vars['FOLDER']->value->mails()){?><div class="col-lg-12 mmEmailContainerDiv padding0px" id='emailListDiv' style="margin-top:10px"><?php $_smarty_tpl->tpl_vars['IS_SENT_FOLDER'] = new Smarty_variable($_smarty_tpl->tpl_vars['FOLDER']->value->isSentFolder(), null, 0);?><input type="hidden" name="folderMailIds" value="<?php echo implode(',',$_smarty_tpl->tpl_vars['FOLDER']->value->mailIds());?>
"/><?php  $_smarty_tpl->tpl_vars['MAIL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MAIL']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FOLDER']->value->mails(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MAIL']->key => $_smarty_tpl->tpl_vars['MAIL']->value){
$_smarty_tpl->tpl_vars['MAIL']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['MAIL']->value->isRead()){?><?php $_smarty_tpl->tpl_vars['IS_READ'] = new Smarty_variable(1, null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['IS_READ'] = new Smarty_variable(0, null, 0);?><?php }?><div class="col-lg-12 cursorPointer mailEntry <?php if ($_smarty_tpl->tpl_vars['IS_READ']->value){?>mmReadEmail<?php }?>" id='mmMailEntry_<?php echo $_smarty_tpl->tpl_vars['MAIL']->value->msgNo();?>
' data-folder="<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
" data-read='<?php echo $_smarty_tpl->tpl_vars['IS_READ']->value;?>
'><span class="col-lg-1 paddingLeft5px"><input type='checkbox' class='mailCheckBox' class="pull-left"></span><div class="col-lg-11 mmfolderMails padding0px" title="<?php echo $_smarty_tpl->tpl_vars['MAIL']->value->subject();?>
"><input type="hidden" class="msgNo" value='<?php echo $_smarty_tpl->tpl_vars['MAIL']->value->msgNo();?>
'><input type="hidden" class='mm_foldername' value='<?php echo $_smarty_tpl->tpl_vars['FOLDER']->value->name();?>
'><div class="col-lg-8 nameSubjectHolder font11px padding0px stepText"><?php $_smarty_tpl->tpl_vars['DISPLAY_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAIL']->value->from(33), null, 0);?><?php if ($_smarty_tpl->tpl_vars['IS_SENT_FOLDER']->value){?><?php $_smarty_tpl->tpl_vars['DISPLAY_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAIL']->value->to(33), null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['SUBJECT'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAIL']->value->subject(), null, 0);?><?php if (mb_strlen($_smarty_tpl->tpl_vars['SUBJECT']->value,'UTF-8')>33){?><?php $_smarty_tpl->tpl_vars['SUBJECT'] = new Smarty_variable(mb_substr($_smarty_tpl->tpl_vars['MAIL']->value->subject(),0,30,'UTF-8'), null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['IS_READ']->value){?><?php echo strip_tags($_smarty_tpl->tpl_vars['DISPLAY_NAME']->value);?>
<br><?php echo strip_tags($_smarty_tpl->tpl_vars['SUBJECT']->value);?>
<?php }else{ ?><strong><?php echo strip_tags($_smarty_tpl->tpl_vars['DISPLAY_NAME']->value);?>
<br><?php echo strip_tags($_smarty_tpl->tpl_vars['SUBJECT']->value);?>
</strong><?php }?></div><div class="col-lg-4 padding0px"><?php $_smarty_tpl->tpl_vars['ATTACHMENT'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAIL']->value->attachments(), null, 0);?><?php $_smarty_tpl->tpl_vars['INLINE_ATTCH'] = new Smarty_variable($_smarty_tpl->tpl_vars['MAIL']->value->inlineAttachments(), null, 0);?><?php $_smarty_tpl->tpl_vars['ATTCHMENT_COUNT'] = new Smarty_variable((count($_smarty_tpl->tpl_vars['ATTACHMENT']->value)-count($_smarty_tpl->tpl_vars['INLINE_ATTCH']->value)), null, 0);?><span class="pull-right"><?php if ($_smarty_tpl->tpl_vars['ATTCHMENT_COUNT']->value){?><i class="fa fa-paperclip font14px"></i>&nbsp;<?php }?><span class='mmDateTimeValue' title="<?php echo Vtiger_Util_Helper::formatDateTimeIntoDayString(date('Y-m-d H:i:s',strtotime($_smarty_tpl->tpl_vars['MAIL']->value->_date)));?>
"><?php echo Vtiger_Util_Helper::formatDateDiffInStrings(date('Y-m-d H:i:s',strtotime($_smarty_tpl->tpl_vars['MAIL']->value->_date)));?>
</span></span></div><div class="col-lg-12 mmMailDesc"><img src="<?php echo vimage_path('128-dithered-regular.gif');?>
"></img></div></div></div><?php } ?></div><?php }else{ ?><div class="noMailsDiv"><center><strong><?php echo vtranslate('LBL_No_Mails_Found',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></center></div><?php }?>
<?php }} ?>