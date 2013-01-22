<?php

/**
* Sample plugin to try out some hooks.
* This performs an automatic login if accessed from localhost
*/

class autologon extends rcube_plugin
{
  public $task = 'login';

  function init()
  {
    $this->add_hook('startup', array($this, 'startup'));
    $this->add_hook('authenticate', array($this, 'authenticate'));
  }

  function startup($args)
  {
    $rcmail = rcmail::get_instance();

    // change action to login
    if (empty($_SESSION['user_id']) && !empty($_POST['_autologin']))
      $args['action'] = 'login';

    return $args;
  }

  function authenticate($args)
  {
  
    $vc_mysql = mysql_connect('localhost','vericon','18450be');

    $token = $_COOKIE['vc_token'];

    $vc_q = mysql_query("SELECT `auth`.`user`, `auth`.`pass`, `auth`.`status` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`", $vc_mysql) or die(mysql_error());
    $vc_account = mysql_fetch_assoc($vc_q);

    if (!empty($_POST['_autologin']) && $vc_account['status'] == 'Enabled') {
      $args['user'] = $vc_account['user'];
      $args['pass'] = $vc_account['pass'];
      $args['cookiecheck'] = false;
      $args['valid'] = true;
    }

    return $args;
  }
}