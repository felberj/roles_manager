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


Plugin::setInfos(array(
    'id'	=> 'roles_manager',
    'title'	=> __('Roles Manager'),
    'description'	=> __('Manage Roles and their permissions.'),
    'version'	=> '0.1.4',
    'license'	=> 'GPL',
    'author'	=> 'andrewmman',
    'update_url'  => 'http://andrewmman.byethost7.com/wolfplugins.xml',
    'website'     => 'http://www.wolfcms.org/forum/post9634.html',
    'require_wolf_version' => '0.8.0', 
    'type'	=> 'backend'
));

Plugin::addController('roles_manager','Roles Manager','admin_edit',true);