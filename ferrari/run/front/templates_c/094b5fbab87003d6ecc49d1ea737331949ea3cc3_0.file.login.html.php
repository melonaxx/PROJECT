<?php
/* Smarty version 3.1.29, created on 2016-06-03 18:03:23
  from "/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/login.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_575155ebbc0998_37167167',
  'file_dependency' => 
  array (
    '094b5fbab87003d6ecc49d1ea737331949ea3cc3' => 
    array (
      0 => '/home/tianyaqiang/devspace/ferrari/src/web_inf/front/tpls/login.html',
      1 => 1464858136,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_575155ebbc0998_37167167 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <title>登录 - 北京福润一生ERP系统</title>

        <!-- Bootstrap -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/path.css" rel="stylesheet">
        <link href="/css/signin.css" rel="stylesheet">
    </head>
    <body>
        <nav>
        <div class="logo_conn">
            <h1><a title="首页" class="logo" href="/"></a></h1><span class="txt">北京福润一生科技有限公司</span>
        </div>
        </nav>

        <div class="container">
            <form class="form-signin form-horizontal">
                <h2 class="form-signin-heading">登录</h2>
                <div class="alert alert-danger" role="alert" <?php if (!$_smarty_tpl->tpl_vars['errtype']->value) {?> style="display:none" <?php }?>>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">错误提示：</span>
                    <span id="errmsg">
                    </span>
                </div>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" placeholder="输入用户名" maxlength="16">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword" placeholder="输入密码" autocomplete="off" maxlength="32">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputVerifycode" class="col-sm-2 control-label">验证码</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-verify-input" id="inputVerifycode" placeholder="输入验证码" autocomplete="off" maxlength="4">
                        <a href="javascript:void(0);"><img id="imgCaptcha" class="form-verify-code" src="/captcha.php?_=<?php echo '<?=';?>time()<?php echo '?>';?>" title="点击刷新"></a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="btnLogin" type="button" class="btn btn-primary">登录</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <?php echo '<script'; ?>
 src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"><?php echo '</script'; ?>
>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <?php echo '<script'; ?>
 src="/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 src="/js/util.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/md5.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="/js/login.js"><?php echo '</script'; ?>
>
    </body>
</html><?php }
}
