<?php /* Smarty version Smarty-3.1.7, created on 2020-10-28 03:45:35
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5542130815f97e5a94cd158-28370451%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7df903e93dc7bc572a1222add6b11e7bb071ec99' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl',
      1 => 1603856732,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5542130815f97e5a94cd158-28370451',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f97e5a94d5d3',
  'variables' => 
  array (
    'LANGUAGE_STRINGS' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f97e5a94d5d3')) {function content_5f97e5a94d5d3($_smarty_tpl) {?>

<footer class="app-footer">
	<p style="margin-left: 33%;">
		<span style="position: fixed;margin: 2%;" > Copyright © <?php echo date('Y');?>
&nbsp;&nbsp;</span>
		<a href="//tcra-ccc.go.tzm" target="_blank" style="position: fixed;margin: 2%;padding-left: 9%;color: black;" >TCRA CCC | Users Experience Tracking System</a>&nbsp;
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

</html>
<?php }} ?>