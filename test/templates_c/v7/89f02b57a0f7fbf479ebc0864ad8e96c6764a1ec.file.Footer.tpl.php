<?php /* Smarty version Smarty-3.1.7, created on 2020-10-19 11:57:08
         compiled from "/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1915598515f8d7f14f3f626-94237722%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89f02b57a0f7fbf479ebc0864ad8e96c6764a1ec' => 
    array (
      0 => '/var/www/html/zcrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl',
      1 => 1587154354,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1915598515f8d7f14f3f626-94237722',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LANGUAGE_STRINGS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f8d7f150916d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f8d7f150916d')) {function content_5f8d7f150916d($_smarty_tpl) {?>

<footer class="app-footer">
	<p>
		Copyright © <?php echo date('Y');?>
&nbsp;&nbsp;
		<a href="//tcra-ccc.go.tzm" target="_blank">TCRA CCC | Users Experience Tracking System</a>&nbsp;
	</p>
</footer>
</div>
<div id='overlayPage'>
	<!-- arrow is added to point arrow to the clicked element (Ex:- TaskManagement), 
	any one can use this by adding "show" class to it -->
	<div class='arrow'></div>
	<div class='data'>
	</div>
</div>
<div id='helpPageOverlay'></div>
<div id="js_strings" class="hide noprint"><?php echo Zend_Json::encode($_smarty_tpl->tpl_vars['LANGUAGE_STRINGS']->value);?>
</div>
<div class="modal myModal fade"></div>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('JSResources.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>

</html><?php }} ?>