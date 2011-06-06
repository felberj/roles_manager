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

<h1><?php echo __("Manage Roles"); ?></h1>

<div id="roles_manager">
    <div class="roles_manager_wrap">
        <table id="roles_manager_roles" cellspacing="0" cellpadding="0" border="0">
            <colgroup>
                <col class="roles_id" />
                <col class="roles_name" />
                <col class="roles_action" />
            </colgroup>
            <thead>
                <tr>
                    <th class="roles_id"><?php echo __('ID'); ?></th>
                    <th class="roles_name"><?php echo __('Name'); ?></th>
                    <th class="roles_action"><?php echo __('Modify'); ?></th>
                </tr>
            </thead>
            <tbody>
<?php foreach($roles as $i => $role): ?>
<?php $admin_protect = ($role->id == 1); ?>
                <tr class="role <?php echo even_odd(); ?>">
                    <td class="roles_id"><?php echo $role->id; ?></td>
                    <td class="roles_name">
                        <a href="<?php echo get_url('plugin/roles_manager/edit/' . $role->id); ?>" title="<?php echo __("Edit the ':name' role", array( ':name' => $role->name )); ?>"><?php echo $role->name; ?></a>
                    </td>
                    <td class="roles_action">
                        <a href="<?php echo get_url('plugin/roles_manager/edit/' . $role->id); ?>">
                            <img src="<?php echo ICONS_URI; ?>rename-16.png" class="inline_icon" title="<?php echo __("Edit the ':name' role", array( ':name' => $role->name )); ?>" alt="<?php echo __("Edit the ':name' role", array( ':name' => $role->name )); ?>"/>
                        </a>
<?php if($admin_protect): ?>
                        <img src="<?php echo ICONS_URI; ?>delete-disabled-16.png" class="inline_icon" title="<?php echo __("Deleting the ':name' role is disabled", array( ':name' => $role->name )); ?>" alt="<?php echo __("Deleting the ':name' role is disabled", array( ':name' => $role->name )); ?>" />
<?php else: ?>
                        <a href="<?php echo get_url('plugin/roles_manager/delete/' . $role->id); ?>" onclick="return confirm('<?php echo __('Are you sure you want to delete this role?'); ?>') ">
                            <img src="<?php echo ICONS_URI; ?>delete-16.png" class="inline_icon" title="<?php echo __("Delete the ':name' role", array( ':name' => $role->name )); ?>" alt="<?php echo __("Delete the ':name' role", array( ':name' => $role->name )); ?>" />
                        </a>
<?php endif; ?>
                    </td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>