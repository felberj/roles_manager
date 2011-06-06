<?php

/**
  *  Roles Manager plugin pre-release for Wolf CMS
  *  Available on the forum http://bit.ly/wf_roles_manager
  * 
  *  Manage Roles and assign/remove permissions.
  *
  *  @author andrewmman <andrewmman@gmail.com>
  *  @package Plugins
  *  @subpackage roles_manager
  *  @version 0.0.1
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }

Plugin::setInfos(array(
	'id'	=> 'roles_manager',
	'title'	=> __('Roles Manager'),
	'description'	=> __('Manage Roles and their permissions.'),
	'version'	=> '0.1.1',
	'license'	=> 'GPL',
	'author'	=> 'andrewmman',
    'update_url'  => 'http://dev.molindeadela.es/plugins.xml',
    'website'     => 'http://www.wolfcms.org/forum/post9634.html',
    'require_wolf_version' => '0.7.5', 
    'type'	=> 'backend'
));

Plugin::addController('roles_manager','Roles Manager','admin_edit',true);