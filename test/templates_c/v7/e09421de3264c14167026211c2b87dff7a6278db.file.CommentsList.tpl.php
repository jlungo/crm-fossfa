<?php /* Smarty version Smarty-3.1.7, created on 2020-10-27 19:25:46
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/CommentsList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18800865865f98743a482404-62520037%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e09421de3264c14167026211c2b87dff7a6278db' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/CommentsList.tpl',
      1 => 1572870387,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18800865865f98743a482404-62520037',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'COMMENTS_MODULE_MODEL' => 0,
    'PARENT_COMMENTS' => 0,
    'CURRENT_COMMENT' => 0,
    'CURRENT_COMMENT_PARENT_MODEL' => 0,
    'TEMP_COMMENT' => 0,
    'COMMENT' => 0,
    'CHILDS_ROOT_PARENT_MODEL' => 0,
    'PARENT_COMMENT_ID' => 0,
    'CHILD_COMMENTS_MODEL' => 0,
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f98743a49dc6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f98743a49dc6')) {function content_5f98743a49dc6($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['IS_CREATABLE'] = new Smarty_variable($_smarty_tpl->tpl_vars['COMMENTS_MODULE_MODEL']->value->isPermitted('CreateView'), null, 0);?><?php $_smarty_tpl->tpl_vars['IS_EDITABLE'] = new Smarty_variable($_smarty_tpl->tpl_vars['COMMENTS_MODULE_MODEL']->value->isPermitted('EditView'), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['PARENT_COMMENTS']->value)){?><ul class="unstyled"><?php if ($_smarty_tpl->tpl_vars['CURRENT_COMMENT']->value){?><?php $_smarty_tpl->tpl_vars['CHILDS_ROOT_PARENT_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_COMMENT']->value, null, 0);?><?php $_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_COMMENT']->value->getParentCommentModel(), null, 0);?><?php while ($_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL']->value!=false){?><?php $_smarty_tpl->tpl_vars['TEMP_COMMENT'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL']->value, null, 0);?><?php $_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL']->value->getParentCommentModel(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['CURRENT_COMMENT_PARENT_MODEL']->value==false){?><?php $_smarty_tpl->tpl_vars['CHILDS_ROOT_PARENT_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['TEMP_COMMENT']->value, null, 0);?><?php }?><?php }?><?php }?><?php if (is_array($_smarty_tpl->tpl_vars['PARENT_COMMENTS']->value)){?><?php  $_smarty_tpl->tpl_vars['COMMENT'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['COMMENT']->_loop = false;
 $_smarty_tpl->tpl_vars['Index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['PARENT_COMMENTS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['COMMENT']->key => $_smarty_tpl->tpl_vars['COMMENT']->value){
$_smarty_tpl->tpl_vars['COMMENT']->_loop = true;
 $_smarty_tpl->tpl_vars['Index']->value = $_smarty_tpl->tpl_vars['COMMENT']->key;
?><?php $_smarty_tpl->tpl_vars['PARENT_COMMENT_ID'] = new Smarty_variable($_smarty_tpl->tpl_vars['COMMENT']->value->getId(), null, 0);?><li class="commentDetails"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('Comment.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('COMMENT'=>$_smarty_tpl->tpl_vars['COMMENT']->value,'COMMENT_MODULE_MODEL'=>$_smarty_tpl->tpl_vars['COMMENTS_MODULE_MODEL']->value), 0);?>
<?php if ($_smarty_tpl->tpl_vars['CHILDS_ROOT_PARENT_MODEL']->value){?><?php if ($_smarty_tpl->tpl_vars['CHILDS_ROOT_PARENT_MODEL']->value->getId()==$_smarty_tpl->tpl_vars['PARENT_COMMENT_ID']->value){?><?php $_smarty_tpl->tpl_vars['CHILD_COMMENTS_MODEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['CHILDS_ROOT_PARENT_MODEL']->value->getChildComments(), null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('CommentsListIteration.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('CHILD_COMMENTS_MODEL'=>$_smarty_tpl->tpl_vars['CHILD_COMMENTS_MODEL']->value), 0);?>
<?php }?><?php }?></li><?php } ?><?php }else{ ?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('Comment.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('COMMENT'=>$_smarty_tpl->tpl_vars['PARENT_COMMENTS']->value), 0);?>
<?php }?></ul><?php }else{ ?><div class="noCommentsMsgContainer" style='padding:20px;'><p class="textAlignCenter"><?php echo vtranslate('LBL_NO_COMMENTS',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</p></div><?php }?><?php }} ?>