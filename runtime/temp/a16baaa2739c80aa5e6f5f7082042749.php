<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"D:\WWW\fastadmin\public/../application/admin\view\index\login.html";i:1559200351;s:56:"D:\WWW\fastadmin\application\admin\view\common\meta.html";i:1557482263;s:58:"D:\WWW\fastadmin\application\admin\view\common\script.html";i:1559031184;}*/ ?>
<!DOCTYPE html>
<html lang="en">
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

        <style type="text/css">
            body {
                color:#999;
                background:url('<?php echo $background; ?>');
                background-size:cover;
            }
            a {
                color:#fff;
            }
            .login-panel{margin-top:150px;}
            .login-screen {
                max-width:400px;
                padding:0;
                margin:100px auto 0 auto;

            }
            .login-screen .well {
                border-radius: 3px;
                -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background: rgba(255,255,255, 0.2);
            }
            .login-screen .copyright {
                text-align: center;
            }
            @media(max-width:767px) {
                .login-screen {
                    padding:0 20px;
                }
            }
            .profile-img-card {
                width: 100px;
                height: 100px;
                margin: 10px auto;
                display: block;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
            }
            .profile-name-card {
                text-align: center;
            }

            #login-form {
                margin-top:20px;
            }
            #login-form .input-group {
                margin-bottom:15px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="login-wrapper">
                <div class="login-screen">
                    <div class="well">
                        <div class="login-form">
                            <img id="profile-img" class="profile-img-card" src="/assets/img/avatar.png" />
                            <p id="profile-name" class="profile-name-card"></p>

                            <form action="" method="post" id="login-form">
                                <div id="errtips" class="hide"></div>
                                <?php echo token(); ?>
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                                    <input type="text" class="form-control" id="pd-form-username" placeholder="<?php echo __('Username'); ?>" name="username" autocomplete="off" value="" data-rule="<?php echo __('Username'); ?>:required;username" />
                                </div>

                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                                    <input type="password" class="form-control" id="pd-form-password" placeholder="<?php echo __('Password'); ?>" name="password" autocomplete="off" value="" data-rule="<?php echo __('Password'); ?>:required;password" />
                                </div>
                                <?php if($config['fastadmin']['login_captcha']): ?>
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></div>
                                    <input type="text" name="captcha" class="form-control" placeholder="<?php echo __('Captcha'); ?>" data-rule="<?php echo __('Captcha'); ?>:required;length(4)" />
                                    <span class="input-group-addon" style="padding:0;border:none;cursor:pointer;">
                                        <img src="<?php echo rtrim('/', '/'); ?>/captcha" width="100" height="30" onclick="this.src = '<?php echo rtrim('/', '/'); ?>/captcha?r=' + Math.random();"/>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="inline" for="keeplogin">
                                        <input type="checkbox" name="keeplogin" id="keeplogin" value="1" />
                                        <?php echo __('Keep login'); ?>
                                    </label>
                                    <div class="pull-right"><a href="javascript:;" class="btn-forgot">忘记密码？</a></div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-lg btn-block"><?php echo __('Sign in'); ?></button>
                                </div>
                                <div class="form-group">
                                    <a href="register.html" class="btn btn-success btn-lg btn-block">注册</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- FastAdmin是开源程序，建议在您的网站底部保留一个FastAdmin的链接 -->
                    <p class="copyright"><a href="https://www.fastadmin.net">Powered By FastAdmin</a></p>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>

    </body>
</html>
<script type="text/html" id="resetpwdtpl">
    <form id="resetpwd-form" class="form-horizontal form-layer" method="POST" action="<?php echo url('api/user/resetpwd'); ?>">
        <div class="form-body">
            <input type="hidden" name="action" value="resetpwd" />
            <!--<div class="form-group">-->
                <!--<label for="" class="control-label col-xs-12 col-sm-3"><?php echo __('Type'); ?>:</label>-->
                <!--<div class="col-xs-12 col-sm-8">-->
                    <!--<div class="radio">-->
                        <!--<label for="type-mobile"><input id="type-mobile" name="type" checked type="radio" data-send-url="<?php echo url('api/sms/send'); ?>" data-check-url="<?php echo url('api/validate/check_sms_correct'); ?>" value="mobile">通过手机号重置密码</label>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <div class="form-group" data-type="mobile">
                <label for="mobile" class="control-label col-xs-12 col-sm-3">手机号:</label>
                <div class="col-xs-12 col-sm-8">
                    <input type="text" class="form-control" id="mobile" name="mobile" value="" data-rule="required;mobile;remote(<?php echo url('api/validate/check_mobile_exist'); ?>, event=resetpwd)" placeholder="">
                    <span class="msg-box"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="captcha" class="control-label col-xs-12 col-sm-3">验证码:</label>
                <div class="col-xs-12 col-sm-8">
                    <div class="input-group">
                        <input type="text" name="captcha" class="form-control" data-rule="required;length(4);integer[+];remote(<?php echo url('api/validate/check_sms_correct'); ?>, event=resetpwd, mobile:#mobile)" />
                        <span class="input-group-btn" style="padding:0;border:none;">
                            <a href="javascript:;" class="btn btn-info btn-captcha" data-url="<?php echo url('api/sms/send'); ?>" data-type="mobile" data-event="resetpwd">发送验证码</a>
                        </span>
                    </div>
                    <span class="msg-box"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="newpassword" class="control-label col-xs-12 col-sm-3">新密码:</label>
                <div class="col-xs-12 col-sm-8">
                    <input type="password" class="form-control" id="newpassword" name="newpassword" value="" data-rule="required;password" placeholder="">
                    <span class="msg-box"></span>
                </div>
            </div>
        </div>
        <div class="form-group form-footer">
            <label class="control-label col-xs-12 col-sm-3"></label>
            <div class="col-xs-12 col-sm-8">
                <button type="submit" class="btn btn-md btn-info"><?php echo __('Ok'); ?></button>
            </div>
        </div>
    </form>
</script>