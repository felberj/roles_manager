<?php

/**
  *  Roles Manager plugin pre-release for Wolf CMS
  *  Available on the forum
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
?>
<h1><?php echo __('Documentation'); ?></h1>
<div id="roles_manager" class="documentation">
    <div class="roles_manager_wrap">
        <h3>Users, Roles and Permissions</h3>
        <p><strong>Users</strong> in Wolf will have a set of privileges that are inherited by the <strong>Roles</strong> those users have. Those privileges are known as <strong>Permissions</strong> and they are assigned to <strong>Roles</strong>.</p>
        <p>Using <strong>Roles</strong> allows you to grant <strong>multiple users</strong> a set of permissions, without having to edit each user settings. You just need to add or edit a role and grant them the permissions you want.</p>
        <p>Examples of these can be,
            <ul>
                <li>Allowing them to edit Pages, Layouts or Snippets.</li>
                <li>Create or delete folders and files in your public directory.</li>
                <li>Give them access to Plugins.</li>
            </ul>
        </p>
        <p>
            <strong>Users</strong> can have one or more <strong>Roles</strong>, which have one or more <strong>Permissions</strong>.
        </p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Default Roles</h3>
        <p>Wolf's default installation comes with three roles:</p>
        <ol>
            <li>Administrator</li>
            <li>Developer</li>
            <li>Editor</li>
        </ol>
        <p>The <strong>Administrator</strong> role grants the user all the available permissions, by default. This plugin will only let you change it's name, not remove it's permissions.</p>
        <p>The <strong>Developer</strong> role, has a few restrictions. Allows the user to manage Pages, Layouts and Snippets and use the File Manager plugin.</p>
        <p>The <strong>Editor</strong> role has limited access, allowing the user to add, edit or delete <span class="tooltip" title="that are not protected">Pages</span> and use the File Manager plugin.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Permissions introduced by a Plugin</h3>
        <p>Some plugins may introduce new permissions to your Wolf site. If you want certain users to access these new features, you would either:
            <ul>
                <li>Create a specific role that granted access to the plugin <span class="tooltip" title="by enabling the new permission(s) introduced.">features</span> and assign this role to certain users.</li>
                <li>Edit one of the existing role those users have in common and assing the new permissions.</li>
            </ul>
        </p>
    </div>
</div>