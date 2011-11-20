<?php

/**
  *  Roles Manager plugin for Wolf CMS
  * 
  *  Manage Roles and assign/remove permissions.
  *
  *  @author andrewmman <andrewmman@gmail.com>
  *  @package Plugins
  *  @subpackage roles_manager
  *  @version 0.1.4
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }
 
AuthUser::load();
if (!AuthUser::isLoggedIn()) {
    redirect(get_url('login'));
}
else if (!AuthUser::hasPermission('admin_edit')) {
    Flash::set('error', __('You do not have permission to activate or use this plugin!'));
    Plugin::deactivate('roles_manager');
    redirect(get_url());
}