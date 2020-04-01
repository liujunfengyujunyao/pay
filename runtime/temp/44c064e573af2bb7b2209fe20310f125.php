<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:69:"D:\WWW\fastadmin\public/../application/admin\view\index\register.html";i:1560221019;s:56:"D:\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:58:"D:\WWW\fastadmin\application\admin\view\common\script.html";i:1559031184;}*/ ?>
<!doctype html>
<html >
<head>
    <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    <style>
        .header_top h3{ font-size: 24px; font-weight: bold; color: #000;}
        .header_top h3 span{ color: #fff; font-size: 16px; margin: 0 10px;}
        .header_top h3 a{ color:#000; font-size: 14px;}
        main.content {
            width: 100%;
            overflow: auto;
            padding: 15px;
            padding-top: 20px;
            min-height: calc(100vh - 125px);
        }
        .login-section {
            margin: 50px auto;
            overflow: hidden;
            width: 1000px;
        }
        .form-group label{float: left; width: 100px; height: 40px; line-height: 40px;}
        .controls{overflow: hidden;}
        @media (max-width: 767px) {
            .login-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" style="background: #666;">
    <div class="container header_top">
        <h3>哇卡支付<span>|</span><a href="">用户注册</a> </h3>
    </div>
</nav>
<main class="content">
    <div id="content-container" class="container">
        <div class="login-section">
            <form name="form1" id="register-form" class="form-vertical" method="POST" action="">
                <div class="form-group">
                    <label class="control-label required"><span style="color: #ff0000;">*</span>注册手机号<span class="text-success"></span></label>
                    <div class="controls">
                        <input type="text" id="mobile" name="mobile"  data-rule="required;mobile" class="form-control" placeholder="手机号" style="width: 60%; float: left; height: 40px; display: inline-block!important;">
                        <a href="javascript:;" class="captcha" style="display: inline-block; float: left; padding: 10px!important;color: #fff;background-color: #3498db;font-size: 11px;line-height: 1.5;border-radius: 2px;box-shadow: none;border: 1px solid ">发送验证码</a>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label required"><span style="color: #ff0000;">*</span>手机验证码<span class="text-success"></span></label>
                    <div class="controls">
                        <input type="text" name="captcha1" data-rule="required;length(4)" class="form-control" placeholder="手机验证码" style="width: 60%;" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label required"><span style="color: #ff0000;">*</span><?php echo __('Password'); ?><span class="text-success"></span></label>
                    <div class="controls">
                        <input type="password" id="password" name="password" style="width: 60%;"  data-rule="required;password" class="form-control" placeholder="密码6到16位之间" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label required"><span style="color: #ff0000;">*</span>确认密码<span class="text-success"></span></label>
                    <div class="controls">
                        <input type="password" id="password2" name="password2" style="width: 60%;"  data-rule="required;password;" class="form-control" placeholder="密码6到16位之间" >
                    </div>
                </div>
                <div class="form-group col-sm-offset-1 col-sm-11" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary btn-sm">注册</button>
                </div>
            </form>
        </div>

    </div>
</main>
<script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>


</body>
</html>