<?php /* Smarty version Smarty-3.1.7, created on 2020-10-29 06:26:39
         compiled from "/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Users/FPLogin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13483773255f9a609f9c0a16-71697483%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b5722127005bed56752e222b6e5cfb99fc92e64d' => 
    array (
      0 => '/opt/vhost/services.smackcoders.com/tcracrm/includes/runtime/../../layouts/v7/modules/Users/FPLogin.tpl',
      1 => 1572870387,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13483773255f9a609f9c0a16-71697483',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ERROR' => 0,
    'USERNAME' => 0,
    'PASSWORD' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f9a609f9cb13',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f9a609f9cb13')) {function content_5f9a609f9cb13($_smarty_tpl) {?>





<?php if ($_smarty_tpl->tpl_vars['ERROR']->value){?>
	Error, please retry setting the password!!
<?php }else{ ?>
	<h4>Loading .... </h4>
	<form class="form-horizontal" name="login" id="login" method="post" action="../../../index.php?module=Users&action=Login">
		<input type=hidden name="username" value="<?php echo $_smarty_tpl->tpl_vars['USERNAME']->value;?>
" >
		<input type=hidden name="password" value="<?php echo $_smarty_tpl->tpl_vars['PASSWORD']->value;?>
" >
	</form>
	<script type="text/javascript">
		function autoLogin () {
			var form = document.getElementById("login");
			form.submit();
		}
		window.onload = autoLogin;
	</script>
<?php }?><?php }} ?>