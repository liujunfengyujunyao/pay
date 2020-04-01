<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'app_init' => 
    array (
      0 => 'cms',
    ),
    'response_send' => 
    array (
      0 => 'cms',
    ),
    'user_sidenav_after' => 
    array (
      0 => 'cms',
    ),
    'action_begin' => 
    array (
      0 => 'geetest',
    ),
    'config_init' => 
    array (
      0 => 'geetest',
    ),
  ),
  'route' => 
  array (
    '/$' => 'cms/index/index',
    '/a/[:diyname]' => 'cms/archives/index',
    '/t/[:name]' => 'cms/tags/index',
    '/p/[:diyname]' => 'cms/page/index',
    '/s' => 'cms/search/index',
    '/c/[:diyname]' => 'cms/channel/index',
    '/d/[:diyname]' => 'cms/diyform/index',
    '/qrcode$' => 'qrcode/index/index',
    '/qrcode/build$' => 'qrcode/index/build',
  ),
);