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
?>

<div class="box">
    <p class="button">
        <a href="<?php echo get_url('plugin/roles_manager/add'); ?>">
            <img src="<?php echo ICONS_URI; ?>add-32.png" align="middle" title="<?php echo __('Create new Role'); ?>" alt="<?php echo __('Create new Role'); ?>" />
            <?php echo __('Create new Role'); ?>
        </a>
    </p>
    <p class="button">
        <a href="<?php echo get_url('plugin/roles_manager'); ?>">
            <img src="<?php echo ICONS_URI; ?>cloud-32.png" align="middle" alt="dir icon" />
            <?php echo __('Manage Roles'); ?>
        </a>
    </p>
    <p class="button">
        <a href="<?php echo get_url('plugin/roles_manager/documentation'); ?>">
            <img src="<?php echo ICONS_URI; ?>file-unknown-32.png" align="middle" alt="dir icon" />
            <?php echo __('Documentation'); ?>
        </a>
    </p>
</div>