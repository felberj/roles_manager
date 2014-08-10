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
  *  @version 0.1.4
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }

?>
<?php $form_action = get_url('plugin/roles_manager/users/'.$role->id); ?>

<h1><?php echo __(":role Users", array( ':role' => Inflector::humanize($role->name) )); ?></h1>
<div id="roles_manager">
    <form action="<?php echo $form_action; ?>" method="post">
        <input id="csrf_token" type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
        <input id="role_id" type="hidden" name="role[id]" value="<?php echo $role->id; ?>" />
        <input id="role_name" type="hidden" name="role[name]" value="<?php echo $role->name; ?>" />
        <div class="roles_manager_wrap">
            <table id="role_users" class="tablesorter" cellspacing="0" cellpadding="0" border="0">
                <caption><?php echo __("Users with the <strong>:role</strong> role.", array(':role' => $role->name)); ?></caption>
                <colgroup>
                    <col class="roles_user_id" />
                    <col class="roles_user_name" />
                    <col class="roles_user_username">
                    <col class="roles_user_email" />
                    <col class="roles_user_action" />
                </colgroup>
                <thead>
                    <tr>
                        <th class="roles_user_id"><?php echo __('ID'); ?></th>
                        <th class="roles_user_name"><?php echo __('Name'); ?></th>
                        <th class="roles_user_username"><?php echo __('Username'); ?></th>
                        <th class="roles_user_email"><?php echo __('Email'); ?></th>
                        <th class="roles_user_action"><?php echo __('Modify'); ?></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach($users as $i => $user): ?>
                    <tr class="role_user">
                        <td class="roles_user_id">                            
                            <input type="hidden" name="role[users][<?php echo $i; ?>][user_id]" value="<?php echo $user->id; ?>" />
                            <?php echo $user->id; ?>
                        </td>
                        <td class="roles_user_name"><?php echo $user->name; ?></td>
                        <td class="roles_user_username">
                            <input type="hidden" name="role[users][<?php echo $i; ?>][user_username]" value="<?php echo $user->username; ?>" />
                            <?php echo $user->username; ?>
                        </td>
                        <td class="roles_user_email"><?php echo (empty($user->email)) ? '-': $user->email; ?></td>
                        <td class="roles_user_action">
                            <input class="roles_user_<?php echo $role->name; ?> checkbox toggle_user_role" type="checkbox" name="role[users][<?php echo $i; ?>][keep_role]" value="1" checked="checked" />
                        </td>
                    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="roles_manager_wrap">
            <p class="buttons">
                <input class="button" type="submit" name="save" value="<?php echo __('Save'); ?>" /> or <a href="<?php echo get_url('plugin/roles_manager'); ?>"><?php echo __('Cancel'); ?></a>
            </p>
        </div>
    </form>
</div>

<script type="text/javascript">
    // <![CDATA[
        $(document).ready(function() {
            $("#role_users").tablesorter({
                headers: { 0: { sorter: false }, 4: { sorter: false } }
            });
        });
    // ]]>
</script>