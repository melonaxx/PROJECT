<?php
/* Smarty version 3.1.29, created on 2016-06-02 17:15:35
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/error.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_574ff937387eb0_15013627',
  'file_dependency' => 
  array (
    'd681523f34ee5d9c4ccae7708f9659b215da7ea0' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/error.html',
      1 => 1464858136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_574ff937387eb0_15013627 ($_smarty_tpl) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>ERROR PAGE</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <div><?php echo $_smarty_tpl->tpl_vars['errormsg']->value;?>
</div>
        <pre>
        <?php echo $_smarty_tpl->tpl_vars['exception']->value;?>

        </pre>
    </body>
</html>

<?php }
}
