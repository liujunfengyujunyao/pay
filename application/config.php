<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Env;

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => Env::get('app.debug', true),
    // 应用Trace
    'app_trace'              => Env::get('app.trace', false),
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => true,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,
    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------
    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => true,
    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
        'tpl_cache'    => true,
    ],
    // 视图输出字符串内容替换,留空则会自动进行计算
    'view_replace_str'       => [
        '__PUBLIC__' => '',
        '__ROOT__'   => '',
        '__CDN__'    => '',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',
    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------
    // 异常页面的模板文件
    'exception_tmpl'         => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'think_exception.tpl',
    // 错误显示信息,非调试模式有效
    'error_message'          => '你所浏览的页面暂时无法访问',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',
    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------
    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],
    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],
    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],
    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------
    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],
    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],
    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    //验证码配置
    'captcha'                => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 18,
        // 是否画混淆曲线
        'useCurve' => false,
        //使用中文验证码
        'useZh'    => false,
        // 验证码图片高度
        'imageH'   => 40,
        // 验证码图片宽度
        'imageW'   => 130,
        // 验证码位数
        'length'   => 4,
        // 验证成功后是否重置
        'reset'    => true
    ],
    // +----------------------------------------------------------------------
    // | Token设置
    // +----------------------------------------------------------------------
    'token'                  => [
        // 驱动方式
        'type'     => 'Mysql',
        // 缓存前缀
        'key'      => 'i3d6o32wo8fvs1fvdpwens',
        // 加密方式
        'hashalgo' => 'ripemd160',
        // 缓存有效期 0表示永久缓存
        'expire'   => 0,
    ],
    //FastAdmin配置
    'fastadmin'              => [
        //是否开启前台会员中心
        'usercenter'          => true,
        //登录验证码
        'login_captcha'       => true,
        //登录失败超过10次则1天后重试
        'login_failure_retry' => true,
        //是否同一账号同一时间只能在一个地方登录
        'login_unique'        => false,
        //登录页默认背景图
        'login_background'    => "/assets/img/loginbg.jpg",
        //是否启用多级菜单导航
        'multiplenav'         => false,
        //自动检测更新
        'checkupdate'         => false,
        //版本号
        'version'             => '1.0.0.20190510_beta',
        //API接口地址
        'api_url'             => 'https://api.fastadmin.net',
    ],
   //易宝配置
    'yeepay'                  =>[
        //易宝公钥
        'yop_public_key'      => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6p0XWjscY+gsyqKRhw9MeLsEmhFdBRhT2emOck/F1Omw38ZWhJxh9kDfs5HzFJMrVozgU+SJFDONxs8UB0wMILKRmqfLcfClG9MyCNuJkkfm0HFQv1hRGdOvZPXj3Bckuwa7FrEXBRYUhK7vJ40afumspthmse6bs6mZxNn/mALZ2X07uznOrrc2rk41Y2HftduxZw6T4EmtWuN2x4CZ8gwSyPAW5ZzZJLQ6tZDojBK4GZTAGhnn3bg5bBsBlw2+FLkCQBuDsJVsFPiGh/b6K/+zGTvWyUcu+LUj2MejYQELDO3i2vQXVDk7lVi2/TcUYefvIcssnzsfCfjaorxsuwIDAQAB',
        //商户私钥
        'private_key'         => 'MIIEugIBADANBgkqhkiG9w0BAQEFAASCBKQwggSgAgEAAoIBAQDAVCOCDnslcJceuxavrLswc9WPU9b7yBTVadL8dPVD+Qqpd1xcFQm1FyxIZRbgEAV4MT8oSdhMYqV7bKSyt5PrT9oU5bzJytdJQwxe3eX7WYMldHNv9EHr1uJAQhgWPwqRndRoKHiCxcgy6ps10HGE8Qj0IsAyTL/Og6idcYekVlbVj9w0kotq0kPmRkda0wS8lYD6mH6qq9C36FnEWV3qVKdcO/hJ2AG9e5m75HuAU99BbfwYr0uStZcimpYLtOj0/Cn4v5B//Gthc/Cgf3LJ5FuiKmPKoxfnNoB4TB5ALRcDaovacT7SsMhXFwbfRkt2OfZVYqFtiiuyzUYefU+ZAgMBAAECgf90cn0NQbdN892Lvbr+opazv26OWTTRPVNf47LbJ/VYMnFCKgLBvfsiqeUl8A7pmsm0/BxBSHStywxmrmEJ1By7XJ2uCWtEwouW0AGtbqzQgmHlS5yZLEq9gF18iogK8CB2ChmQ9vAAPb/5FBLlgk85Lrc9Gc1EpzN61jxBF3wJAy/2AL0Q+NYpq6TOWXWoEYFnjQtStq7AaJOh4/K0RhmFvVapyXL4i7fWddWW2jZ//AzIOe5ok5VD7YdxPKXRSxCjlS5JTDVDAZ3KY72i4+oVpeqffF5XR3MdAai+66wHI3eH0QKf6Qz56wyH9yFwSzBEValeWV29SP+MOhjcqI0CgYEA8NxL2kzVh8Kdygkm9pJB3Gxd9ZUPw9oKEdWusZSKLvIs36KPY6qYB5xsF03lmZoe0HvtBLUL03J/D2BVDChHbv2pT5wxKkHU0vkw5ojRiEnMpWbvE6skndeZEA1DD6E4+RSL10siAjXoSKifHzaEu7s1Km30hWqsRBdzXir3gNMCgYEAzGrpqkFnSnQq4sepnL9v247ikjYJi80tly1tjMdkJww9exX1EOSgSMXtXgMof99GUTipFBHe8PRtX4I+yI9K/I4zxRtaYgP+gZ7BVgYe98E6ZNrGD/8LNbJfDbsBwrtYDE/Y23hRbLJOPN/+PocF5LA+uJMuIni1DDfh7MJgymMCgYB+cskHtCqt+UgpVyCzdhlJhULWuQjrwz5iGpJ5/AeHmfBg/9DTfC4QYNiGa4jMWRMwVL8cJ4gr3AJEqkg797F43YbTmqZdDu6SS+yWOuH18PiVJTMCWmkAzL04ph28yOFGMrkvr+wMyQxHiO7wzghlHmVM/yjOGjCSFtWkbF4/rQKBgDI+8VKdIvOFHGmD5GgYEjmopH6F89C+TT+EthHNjQugEZiorAVL/S4GILNkGVddHV6ni7/YKLGXky7Px/jqZ+cuWQFRGOVQ0AUybZlkhcYmY+EYeWjDKxE21/B7EBK6lAjqs4Y2y+To6xxBfrAF5mfw/mnGG6fzfaUUM19L5Bi7AoGAVI8iQ5NP0iZtCdSnQPkjKZMDifwVLwdfcaEjRYop7cfe9IYak+QPC/LQGkjKH5G8t2OAsbC9wExwM3Lhd9DKRBDlqcCPxaTD5Wxq1UDXDcARWarWOpDF7l3Gt7StAsGo9QRb8d0w9CRFLCDzxj1CKGwVz12XfrpL/OdVqtHe/EI=',
        //父商户商编
        'parentMerchantNo'    => '10026912451',
        //收单子账户商编
        'merchantNo'          => '10027419272',
        //子商户秘钥
        'merHmacKey'          => '877X5o8A1ku3rCG03N0N5587e8l64WA5506K127X08201y777MM04z3D10r1',

    ],
];
