<?php /* Smarty version Smarty-3.1.7, created on 2020-10-27 09:17:59
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/dashboards/HistoryContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17120225365f97e5c7891131-92515048%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c99b0b293e058ea733afb061fb598a330caff03d' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/dashboards/HistoryContents.tpl',
      1 => 1572870387,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17120225365f97e5c7891131-92515048',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'HISTORIES' => 0,
    'index' => 0,
    'HISTORY' => 0,
    'MODELNAME' => 0,
    'MOD_NAME' => 0,
    'RELATION' => 0,
    'PROCEED' => 0,
    'VT_ICON' => 0,
    'PARENT' => 0,
    'USER' => 0,
    'DETAILVIEW_URL' => 0,
    'FIELDS' => 0,
    'INDEX' => 0,
    'FIELD' => 0,
    'MODULE_NAME' => 0,
    'LINKED_RECORD_DETAIL_URL' => 0,
    'PARENT_DETAIL_URL' => 0,
    'TIME' => 0,
    'TRANSLATED_MODULE_NAME' => 0,
    'NEXTPAGE' => 0,
    'PAGE' => 0,
    'HISTORY_TYPE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f97e5c791a7f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f97e5c791a7f')) {function content_5f97e5c791a7f($_smarty_tpl) {?>

<div style='padding:5px;'>
	<?php if ($_smarty_tpl->tpl_vars['HISTORIES']->value!=false){?>
		<?php  $_smarty_tpl->tpl_vars['HISTORY'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['HISTORY']->_loop = false;
 $_smarty_tpl->tpl_vars[$_smarty_tpl->tpl_vars['index']->value] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['HISTORIES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['HISTORY']->key => $_smarty_tpl->tpl_vars['HISTORY']->value){
$_smarty_tpl->tpl_vars['HISTORY']->_loop = true;
 $_smarty_tpl->tpl_vars[$_smarty_tpl->tpl_vars['index']->value]->value = $_smarty_tpl->tpl_vars['HISTORY']->key;
?>
			<?php $_smarty_tpl->tpl_vars['MODELNAME'] = new Smarty_variable(get_class($_smarty_tpl->tpl_vars['HISTORY']->value), null, 0);?>
			<?php if ($_smarty_tpl->tpl_vars['MODELNAME']->value=='ModTracker_Record_Model'){?>
				<?php $_smarty_tpl->tpl_vars['USER'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getModifiedBy(), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['TIME'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getActivityTime(), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['PARENT'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getParent(), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['MOD_NAME'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getParent()->getModule()->getName(), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['SINGLE_MODULE_NAME'] = new Smarty_variable(('SINGLE_').($_smarty_tpl->tpl_vars['MOD_NAME']->value), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['TRANSLATED_MODULE_NAME'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['MOD_NAME']->value,$_smarty_tpl->tpl_vars['MOD_NAME']->value), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['PROCEED'] = new Smarty_variable(true, null, 0);?>
				<?php if (($_smarty_tpl->tpl_vars['HISTORY']->value->isRelationLink())||($_smarty_tpl->tpl_vars['HISTORY']->value->isRelationUnLink())){?>
					<?php $_smarty_tpl->tpl_vars['RELATION'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getRelationInstance(), null, 0);?>
					<?php if (!($_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord())){?>
						<?php $_smarty_tpl->tpl_vars['PROCEED'] = new Smarty_variable(false, null, 0);?>
					<?php }?>
				<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['PROCEED']->value){?>
					<div class="row entry clearfix">
						<div class='col-lg-1 pull-left'>
							<?php $_smarty_tpl->tpl_vars['VT_ICON'] = new Smarty_variable($_smarty_tpl->tpl_vars['MOD_NAME']->value, null, 0);?>
							<?php if ($_smarty_tpl->tpl_vars['MOD_NAME']->value=="Events"){?>
								<?php $_smarty_tpl->tpl_vars["TRANSLATED_MODULE_NAME"] = new Smarty_variable("Calendar", null, 0);?>
								<?php $_smarty_tpl->tpl_vars['VT_ICON'] = new Smarty_variable("Calendar", null, 0);?>
							<?php }elseif($_smarty_tpl->tpl_vars['MOD_NAME']->value=="Calendar"){?>
								<?php $_smarty_tpl->tpl_vars['VT_ICON'] = new Smarty_variable("Task", null, 0);?>
							<?php }?>
							<span><?php echo $_smarty_tpl->tpl_vars['HISTORY']->value->getParent()->getModule()->getModuleIcon($_smarty_tpl->tpl_vars['VT_ICON']->value);?>
</span>&nbsp;&nbsp;
						</div>
						<div class="col-lg-10 pull-left">
							<?php $_smarty_tpl->tpl_vars['DETAILVIEW_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['PARENT']->value->getDetailViewUrl(), null, 0);?>
							<?php if ($_smarty_tpl->tpl_vars['HISTORY']->value->isUpdate()){?>
								<?php $_smarty_tpl->tpl_vars['FIELDS'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getFieldInstances(), null, 0);?>
								<div>
									<div><b><?php echo $_smarty_tpl->tpl_vars['USER']->value->getName();?>
</b> <?php echo vtranslate('LBL_UPDATED');?>
 <a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,'javascript:')===0){?>
																								  onclick='<?php echo substr($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,strlen("javascript:"));?>
' <?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value;?>
"' <?php }?>>
											<?php echo $_smarty_tpl->tpl_vars['PARENT']->value->getName();?>
</a>
									</div>
									<?php  $_smarty_tpl->tpl_vars['FIELD'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FIELD']->_loop = false;
 $_smarty_tpl->tpl_vars['INDEX'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['FIELDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FIELD']->key => $_smarty_tpl->tpl_vars['FIELD']->value){
$_smarty_tpl->tpl_vars['FIELD']->_loop = true;
 $_smarty_tpl->tpl_vars['INDEX']->value = $_smarty_tpl->tpl_vars['FIELD']->key;
?>
										<?php if ($_smarty_tpl->tpl_vars['INDEX']->value<2){?>
											<?php if ($_smarty_tpl->tpl_vars['FIELD']->value&&$_smarty_tpl->tpl_vars['FIELD']->value->getFieldInstance()&&$_smarty_tpl->tpl_vars['FIELD']->value->getFieldInstance()->isViewableInDetailView()){?>
												<div>
													<i><?php echo vtranslate($_smarty_tpl->tpl_vars['FIELD']->value->getName(),$_smarty_tpl->tpl_vars['FIELD']->value->getModuleName());?>
</i>
													<?php if ($_smarty_tpl->tpl_vars['FIELD']->value->get('prevalue')!=''&&$_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')!=''&&!($_smarty_tpl->tpl_vars['FIELD']->value->getFieldInstance()->getFieldDataType()=='reference'&&($_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')=='0'||$_smarty_tpl->tpl_vars['FIELD']->value->get('prevalue')=='0'))){?>
														&nbsp;<?php echo vtranslate('LBL_FROM');?>
 <b><?php echo Vtiger_Util_Helper::toVtiger6SafeHTML($_smarty_tpl->tpl_vars['FIELD']->value->getDisplayValue(decode_html($_smarty_tpl->tpl_vars['FIELD']->value->get('prevalue'))));?>
</b>
													<?php }elseif($_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')==''||($_smarty_tpl->tpl_vars['FIELD']->value->getFieldInstance()->getFieldDataType()=='reference'&&$_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')=='0')){?>
														&nbsp; <b> <?php echo vtranslate('LBL_DELETED');?>
 </b> ( <del><?php echo Vtiger_Util_Helper::toVtiger6SafeHTML($_smarty_tpl->tpl_vars['FIELD']->value->getDisplayValue(decode_html($_smarty_tpl->tpl_vars['FIELD']->value->get('prevalue'))));?>
</del> )
													<?php }else{ ?>
														&nbsp;<?php echo vtranslate('LBL_CHANGED');?>

													<?php }?>
													<?php if ($_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')!=''&&!($_smarty_tpl->tpl_vars['FIELD']->value->getFieldInstance()->getFieldDataType()=='reference'&&$_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue')=='0')){?>
														<?php echo vtranslate('LBL_TO');?>
 <b><?php echo Vtiger_Util_Helper::toVtiger6SafeHTML($_smarty_tpl->tpl_vars['FIELD']->value->getDisplayValue(decode_html($_smarty_tpl->tpl_vars['FIELD']->value->get('postvalue'))));?>
</b>
													<?php }?>    
												</div>
											<?php }?>
										<?php }else{ ?>
											<a href="<?php echo $_smarty_tpl->tpl_vars['PARENT']->value->getUpdatesUrl();?>
"><?php echo vtranslate('LBL_MORE');?>
</a>
											<?php break 1?>
										<?php }?>
									<?php } ?>
								</div>
							<?php }elseif($_smarty_tpl->tpl_vars['HISTORY']->value->isCreate()){?>
								<div>
									<b><?php echo $_smarty_tpl->tpl_vars['USER']->value->getName();?>
</b> <?php echo vtranslate('LBL_ADDED');?>
 
									<a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,'javascript:')===0){?> onclick='<?php echo substr($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,strlen("javascript:"));?>
' <?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value;?>
"' <?php }?>><?php echo $_smarty_tpl->tpl_vars['PARENT']->value->getName();?>
</a>
								</div>
							<?php }elseif(($_smarty_tpl->tpl_vars['HISTORY']->value->isRelationLink()||$_smarty_tpl->tpl_vars['HISTORY']->value->isRelationUnLink())){?>
								<?php $_smarty_tpl->tpl_vars['RELATION'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getRelationInstance(), null, 0);?>
								<?php $_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getDetailViewUrl(), null, 0);?>
								<?php $_smarty_tpl->tpl_vars['PARENT_DETAIL_URL'] = new Smarty_variable($_smarty_tpl->tpl_vars['RELATION']->value->getParent()->getParent()->getDetailViewUrl(), null, 0);?>
								<div>
									<b><?php echo $_smarty_tpl->tpl_vars['USER']->value->getName();?>
</b>
									<?php if ($_smarty_tpl->tpl_vars['HISTORY']->value->isRelationLink()){?>
										<?php echo vtranslate('LBL_ADDED',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>

									<?php }else{ ?>
										<?php echo vtranslate('LBL_REMOVED',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>

									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getModuleName()=='Calendar'){?>
										<?php if (isPermitted('Calendar','DetailView',$_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getId())=='yes'){?>
											<a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value,'javascript:')===0){?> onclick='<?php echo substr($_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value,strlen("javascript:"));?>
'
											<?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value;?>
"' <?php }?>><?php echo $_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getName();?>
</a>
									<?php }else{ ?>
										<?php echo vtranslate($_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getModuleName(),$_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getModuleName());?>

									<?php }?>
								<?php }elseif($_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getModuleName()=='ModComments'){?>
									<i>"<?php echo $_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getName();?>
"</i>
								<?php }else{ ?>
									<a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value,'javascript:')===0){?> onclick='<?php echo substr($_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value,strlen("javascript:"));?>
'
									<?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['LINKED_RECORD_DETAIL_URL']->value;?>
"' <?php }?>><?php echo $_smarty_tpl->tpl_vars['RELATION']->value->getLinkedRecord()->getName();?>
</a>
							<?php }?><?php echo vtranslate('LBL_FOR');?>
 <a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['PARENT_DETAIL_URL']->value,'javascript:')===0){?>
							   onclick='<?php echo substr($_smarty_tpl->tpl_vars['PARENT_DETAIL_URL']->value,strlen("javascript:"));?>
' <?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['PARENT_DETAIL_URL']->value;?>
"' <?php }?>>
									<?php echo $_smarty_tpl->tpl_vars['RELATION']->value->getParent()->getParent()->getName();?>
</a>
							</div>
						<?php }elseif($_smarty_tpl->tpl_vars['HISTORY']->value->isRestore()){?>
							<div>
								<b><?php echo $_smarty_tpl->tpl_vars['USER']->value->getName();?>
</b> <?php echo vtranslate('LBL_RESTORED');?>
 <a class="cursorPointer" <?php if (stripos($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,'javascript:')===0){?>
																						  onclick='<?php echo substr($_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value,strlen("javascript:"));?>
' <?php }else{ ?> onclick='window.location.href="<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_URL']->value;?>
"' <?php }?>>
									<?php echo $_smarty_tpl->tpl_vars['PARENT']->value->getName();?>
</a>
							</div>
						<?php }elseif($_smarty_tpl->tpl_vars['HISTORY']->value->isDelete()){?>
							<div>
								<b><?php echo $_smarty_tpl->tpl_vars['USER']->value->getName();?>
</b> <?php echo vtranslate('LBL_DELETED');?>
 
								<strong> <?php echo $_smarty_tpl->tpl_vars['PARENT']->value->getName();?>
</strong>
							</div>
						<?php }?>
					</div>
					<?php if ($_smarty_tpl->tpl_vars['TIME']->value){?><p class="pull-right muted" style="padding-right:10px;"><small title="<?php echo Vtiger_Util_Helper::formatDateTimeIntoDayString(($_smarty_tpl->tpl_vars['TIME']->value));?>
"><?php echo Vtiger_Util_Helper::formatDateDiffInStrings(($_smarty_tpl->tpl_vars['TIME']->value));?>
</small></p><?php }?>
				</div>
			<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['MODELNAME']->value=='ModComments_Record_Model'){?>
				<div class="row">
					<div class="col-lg-1 pull-left">
						<span><i class="vicon-chat entryIcon" title=<?php echo $_smarty_tpl->tpl_vars['TRANSLATED_MODULE_NAME']->value;?>
></i></span>
					</div>
					<div class="col-lg-10 pull-left" style="margin-top:5px;">
						<?php $_smarty_tpl->tpl_vars['COMMENT_TIME'] = new Smarty_variable($_smarty_tpl->tpl_vars['HISTORY']->value->getCommentedTime(), null, 0);?>
						<div>
							<b><?php echo $_smarty_tpl->tpl_vars['HISTORY']->value->getCommentedByName();?>
</b> <?php echo vtranslate('LBL_COMMENTED');?>
 <?php echo vtranslate('LBL_ON');?>
 <a class="textOverflowEllipsis" href="<?php echo $_smarty_tpl->tpl_vars['HISTORY']->value->getParentRecordModel()->getDetailViewUrl();?>
"><?php echo $_smarty_tpl->tpl_vars['HISTORY']->value->getParentRecordModel()->getName();?>
</a>
						</div>
						<div><i>"<?php echo nl2br($_smarty_tpl->tpl_vars['HISTORY']->value->get('commentcontent'));?>
"</i></div>
					</div>
					<p class="pull-right muted" style="padding-right:10px;"><small title="<?php echo Vtiger_Util_Helper::formatDateTimeIntoDayString(($_smarty_tpl->tpl_vars['COMMENT_TIME']->value));?>
"><?php echo Vtiger_Util_Helper::formatDateDiffInStrings(($_smarty_tpl->tpl_vars['COMMENT_TIME']->value));?>
</small></p>
				</div>
			<?php }?>
		<?php } ?>

		<?php if ($_smarty_tpl->tpl_vars['NEXTPAGE']->value){?>
			<div class="row">
				<div class="col-lg-12">
					<a href="javascript:;" class="load-more" data-page="<?php echo $_smarty_tpl->tpl_vars['PAGE']->value;?>
" data-nextpage="<?php echo $_smarty_tpl->tpl_vars['NEXTPAGE']->value;?>
"><?php echo vtranslate('LBL_MORE');?>
...</a>
				</div>
			</div>
		<?php }?>

	<?php }else{ ?>
		<span class="noDataMsg">
			<?php if ($_smarty_tpl->tpl_vars['HISTORY_TYPE']->value=='updates'){?>
				<?php echo vtranslate('LBL_NO_UPDATES',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>

			<?php }elseif($_smarty_tpl->tpl_vars['HISTORY_TYPE']->value=='comments'){?>
				<?php echo vtranslate('LBL_NO_COMMENTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>

			<?php }else{ ?>
				<?php echo vtranslate('LBL_NO_UPDATES_OR_COMMENTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>

			<?php }?>
		</span>
	<?php }?>
</div>
<?php }} ?>