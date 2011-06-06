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
<?php

    $title = ($action == 'add') ? __('Add Role'): __('Edit Role');
    $action_url = ($action == 'add') ? 'plugin/roles_manager/add' : 'plugin/roles_manager/edit/'.$role->id;
    $admin_role = ((int)$role->id == 1);
    $hasNoPerm = (count($role_permissions) == 0);
    $i = 0;

?>

<h1><?php echo $title; ?></h1>

<div id="roles_manager">
    <form action="<?php echo get_url($action_url); ?>" method="post">
        <input id="csrf_token" type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />

        <div class="roles_manager_wrap">
            <fieldset class="roles_details">
                <legend><?php echo __("Role Details"); ?></legend>
                <ol class="inputfields">
                    <li class="fields hidden">
                        <label for="role_id"><?php echo __('ID'); ?></label>
                        <br />
                        <input id="role_id" name="role[id]" type="hidden" value="<?php echo $role->id; ?>" />
                    </li>
                    <li class="fields">
                        <label for="role_name"><?php echo __('Name'); ?></label>
                        <br />
                        <input type="text" id="role_name" class="textbox" maxlength="25" name="role[name]" size="24" value="<?php echo $role->name; ?>" />
                    </li>
                </ol>
            </fieldset>
        </div>

    	<div class="roles_manager_wrap">
            <fieldset class="roles_details">
                <legend><?php echo __("Role Permissions"); ?></legend>
                <table id="permissions_assigned" class="roles_permissions" cellspacing="0" cellpadding="0" border="0">
                    <caption><?php echo __('Permissions assigned'); ?></caption>
                    <colgroup>
                        <col class="permission_enabled<?php if($admin_role) echo ' admin_protect';?>" />
                        <col class="permission_name" />
                    </colgroup>
                    <thead>
                        <tr<?php if($hasNoPerm) echo ' class="hidden"';?>>
                            <th class="permission_enabled"><?php echo __('Allow'); ?></th>
                            <th class="permission_name"><?php echo __('Permission'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no_permissions<?php if(!$hasNoPerm) echo ' hidden';?>">
                            <td class="permission_name" colspan="3"><?php echo __('The <strong>:name</strong> role does not have any permissions assigned yet.', array(':name' => $role->name ) ); ?></td>
                        </tr>
<?php foreach($role_permissions as $index => $id): ?>
<?php $perm = Permission::findById($id); ?>
                        <tr class="permission <?php echo even_odd(); ?>">
                            <input type="hidden" name="role[role_permissions][<?php echo $i; ?>][permission_id]" value="<?php echo $perm->id; ?>" />
                            <input type="hidden" name="role[role_permissions][<?php echo $i; ?>][permission_name]" value="<?php echo $perm->name; ?>" />
                            <td class="permission_enabled">
                                <input id="role_permissions_<?php echo $perm->name; ?>" type="checkbox" class="checkbox toggle_role_permission" name="role[role_permissions][<?php echo $i; ?>][assigned]" checked="checked"<?php if( $admin_role ) echo ' readonly="readonly"';?>/>
                            </td>
                            <td class="permission_name"><label for="role_permissions_<?php echo $perm->name; ?>" class="toggle_role_permission" title="<?php echo __("Remove permission ':name'", array(':name' => $perm->name )); ?>"><?php echo Inflector::humanize($perm->name); ?></label></td>
                        </tr>
<?php $i++; ?>
<?php endforeach; ?>
                    </tbody>
                </table>
                <table id="permissions_available" class="roles_permissions" cellspacing="0" cellpadding="0" border="0">
                    <caption><?php echo __('Permissions available'); ?></caption>
                    <colgroup>
                        <col class="permission_enabled" />
                        <col class="permission_name" />
                    </colgroup>
                    <thead>
                        <tr<?php if(count($permissions_disabled) == 0) echo ' class="hidden"';?>>
                            <th class="permission_enabled"><?php echo __('Allow'); ?></th>
                            <th class="permission_name"><?php echo __('Permission'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no_permissions<?php if(count($permissions_disabled) != 0) echo ' hidden';?>">
                            <td class="permission_name" colspan="3"><?php echo __('The <strong>:name</strong> role has all the available permissions already assigned.', array(':name' => $role->name ) ); ?></td>
                        </tr>
<?php foreach($permissions_disabled as $index => $id): ?>
<?php $perm = Permission::findById($id); ?>
                        <tr class="permission <?php echo (($index+1)%2 == 0) ? 'even' : 'odd'; ?>">
                            <input type="hidden" name="role[role_permissions][<?php echo $i; ?>][permission_id]" value="<?php echo $perm->id; ?>" />
                            <input type="hidden" name="role[role_permissions][<?php echo $i; ?>][permission_name]" value="<?php echo $perm->name; ?>" />
                            <td class="permission_enabled">
                                <input id="role_permissions_<?php echo $perm->name; ?>" type="checkbox" class="checkbox toggle_role_permission" name="role[role_permissions][<?php echo $i; ?>][assigned]" />
                            </td>
                            <td class="permission_name"><label for="role_permissions_<?php echo $perm->name; ?>" class="toggle_role_permission" title="<?php echo __("Assign permission ':name'", array(':name' => $perm->name )); ?>"><?php echo Inflector::humanize($perm->name); ?></label></td>
                        </tr>
<?php $i++; ?>
<?php endforeach; ?>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <div class="roles_manager_wrap">
            <p class="buttons">
                <input class="button" type="submit" name="save" value="<?php echo __('Save'); ?>" /> or <a href="<?php echo get_url('plugin/roles_manager'); ?>"><?php echo __('Cancel'); ?></a>
            </p>
        </div>

    </form>
</div>
