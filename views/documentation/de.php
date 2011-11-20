<?php

/**
  *  Roles Manager plugin for Wolf CMS
  * 
  *  Manage Roles and assign/remove permissions.
  *
  *  @author Dirk Digweed <dirkdigweed@gmx.net>
  *  @package Plugins
  *  @subpackage roles_manager
  *  @version 0.1.4
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }
?>
<h1><?php echo __('Documentation'); ?></h1>
<div id="roles_manager" class="documentation">
    <div class="roles_manager_wrap">
        <h3>Benutzer, Rollen und Berechtigungen</h3>
        <p>Der Benutzer des CMS hat eine Reihe von <strong>Privilegien</strong>, die dann durch die <strong>Rollen</strong> diese <strong>Benutzer</strong> vererbt werden. Diese <strong>Privilegien</strong> sind wie <strong>Berechtigungen</strong> und werden bestimmten <strong>Rollen</strong> zugewiesen.</p>
        <p>Mithilfe von <strong>Rollen</strong> k&ouml;nnen Sie mehrere <strong>Benutzer</strong> einen <strong>Satz von Berechtigungen </strong>gew&auml;hren, ohne die einzelnen Benutzer-Einstellungen zu bearbeiten. Sie m&uuml;ssen nur eine <strong>Rolle</strong> hinzuf&uuml;gen oder bearbeiten und dieser die <strong>Berechtigungen</strong> geben, die Sie wollen.</p>
        <p>Beispiele k&ouml;nnen sein:
            <ul>
                <li>Einem Benutzer erlauben die Inhalsbausteine, Seiten oder Layout zu ver&auml;ndern.</li>
                <li>Ordner im <em>/public</em>-Verzeichnis zu erstellen oder l&ouml;schen.</li>
                <li>Einem Benutzer die Berechtigung geben, Plugins zu installieren.</li>
            </ul>
        </p>
        <p><strong>Benutzer</strong> k&ouml;nnen eine oder mehrere <strong>Rollen</strong> und eine oder mehrere <strong>Berechtigungen</strong> haben.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Voreingestelle Rollen</h3>
        <p>Das CMS hat die folgenden Rollen voreingestellt:</p>
        <ol>
            <li>Administrator</li>
            <li><span class="tooltip" title="Entwickler">Developer</li>
            <li>Editor</li>
        </ol>
        <p>Der <strong>Administrator</strong> erh&auml;lt  alle zur Verf&uuml;gung stehenden Berechtigungen standardm&auml;ssig. Hier kann nur der Namen ver&auml;ndert werden.</p>
        <p>Die <strong>Developer</strong> Rolle, hat ein paar Einschr&auml;nkungen. Sie erlaubt dem Benutzer, Seiten, Layouts und Inhaltbausteine zu verwalten und das Verwenden des Datei-Manager-Plugins.</p>
        <p>Die <strong>Editor</strong> Rolle hat beschr&auml;nkten Zugang, so dass der Benutzer, Seiten bearbeiten, hinzuf&uuml;gen oder l&ouml;schen kann und das Datei-Manager-Plugin beschr&auml;nkt verwenden kann.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Berechtigungen, welche durch ein Plugin gegeben werden:</h3>
        <p>Einige Plugins k&ouml;nnen neue Berechtigungen im CMS installieren. Wenn Sie diese Funktion verwenden m&ouml;chten, dann;
            <ul>
                <li>erstellen Sie eine besondere <strong>Rolle</strong>, die Zugriff auf die Plugin-Funktionen gew&auml;hrt, und weisen Sie diese <strong>Rolle</strong>, um bestimmte <strong>Benutzer</strong> zu.</li>
                <li>bearbeiten Sie eine bestehende <strong>Rolle</strong>, welche die verschiedenen <strong>Benutzer</strong> <em>gemeinsam</em> haben und vergeben Sie anschliessend die neuen <strong>Berechtigungen</strong>.</li>
            </ul>
        </p>
    </div>
</div>