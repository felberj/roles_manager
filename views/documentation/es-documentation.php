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
<h1>Documentaci&oacute;n</h1>
<div id="roles_manager" class="documentation">
    <div class="roles_manager_wrap">
        <h3>Usuarios, Roles y Permisos</h3>
        <p>Los <strong>Usuarios</strong> en Wolf tendr&aacute;n una serie de privilegios que provienen del Rol o Roles que dichos usuarios tengan. Estos privilegios son conocidos como <strong>Permisos</strong>, los cuales son asignados a uno o varios <strong>Roles</strong>.</p>
        <p>El usar <strong>Roles</strong> te permite dar una serie privilegios a <strong>varios usarios</strong>, sin tener que editar la configuraci&oacute;n de cada uno de ellos. Basta con añadir o editar un rol y asignarle el o los permisos que desees.</p>
        <p>Un ejemplo de ello puede ser,
            <ul>
                <li>Permitir que puedan editar P&aacute;ginas, Plantillas o Fragmentos.</li>
                <li>Crear o borrar carpetas o ficheros en el directorio p&uacute;blico.</li>
                <li>Darles acceso a los Plugins.</li>
            </ul>
        </p>
        <p>En resumen, los <strong>Usuarios</strong> pueden tener uno o varios <strong>Roles</strong>, los cuales pueden poseer uno o varios <strong>Permisos</strong>.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Roles predefinidos</h3>
        <p>La instalaci&oacute;n de Wolf crea tres roles por defecto:</p>
            <ol>
                <li><span class="tooltip" title="Administrador">Administrator</span></li>
                <li><span class="tooltip" title="Desarrollador">Developer</span></li>
                <li>Editor</li>
            </ol>
        <p>El rol de <strong>Administrator</strong> le otorgan al usuario todos los permisos disponibles. Este plugin s&oacute;lo te permitar&aacute; modificar su nombre, no remover sus permisos.</p>
        <p>El rol de <strong>Developer</strong>, tiene un par de restricciones. Permiten al usuario administrar P&aacute;ginas, Plantillas y Fragmentos y usar el plugin <?php echo __('File Manager'); ?>.</p>
        <p>El rol de <strong>Editor</strong> tiene acceso limitado, permitiendo al usuario administrar <span class="tooltip" title="que no est&eacute;n protegidas">P&aacute;ginas</span> y usar el plugin <?php echo __('File Manager'); ?>.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Permisos generados por un Plugin</h3>
        <p>Algunos plugins podr&iacute;an generar nuevos permisos a tu sitio de Wolf. Si deseas que algunos usuarios puedan acceder a las nuevas funciones, tienes estas posibilidades:
            <ul>
                <li>Crear un rol espec&iacute;fico que permitiese acceder a las <span class="tooltip" title="habilitar&iacute;as los nuevos permisos generados.">funciones</span> del plugin y le asignas este rol a determinados usuarios.</li>
                <li>Editar uno de los roles existentes, el cual los usuarios tengan en com&uacute;n, y asignarle los nuevos permisos.</li>
            </ul>            
        </p>
    </div>
</div>