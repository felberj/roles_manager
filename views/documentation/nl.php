<?php

/**
  *  Roles Manager plugin for Wolf CMS
  * 
  *  Manage Roles and assign/remove permissions.
  *
  *  @author Fortron <webmasterkubes@gmail.com>
  *  @package Plugins
  *  @subpackage roles_manager
  *  @version 0.1.4
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }
?>
<h1></h1>
<div id="roles_manager" class="documentation">
    <div class="roles_manager_wrap">
        <h3>Gebruikers, Rollen en Permissies</h3>
        <p><strong>Gebruikers</strong> in Wolf hebben een bepaalde rechten die geerfd worden van <strong>Rollen</strong> die gebruikers kunnen hebben. Die rechten zijn bekend als <strong>Permissies</strong> die worden toegewezen aan <strong>Rollen</strong>.</p>
        <p>Het gebruik van <strong>Rollen</strong> geeft u de mogelijkheid om <strong>meerdere gebruikers</strong> een set van permissies te geven, zonder elke individuele gebuiker te hoeven bewerken. Je hoeft enkel een rol te maken of een bestaande te bewerkenn om permissies toe te staan.</p>
        <p>Voorbeeld hiervan kunnen zijn,</p>
            <ul>
                <li>Permissie geven om Pagina's, Layouts of Snippers te bewerken.</li>
                <li>Permissie geven om mappen en bestanden te maken of te verwijderen in de public map.</li>
                <li>Permissie geven om toegang tot de Plugins te geven</li>
            </ul>
        <p><strong>Gebruikers</strong> kunnen meerdere <strong>Rollen</strong> hebben, die ieder kunnen bestaan uit meerdere <strong>Permissies</strong>.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Standaard Rollen</h3>
        <p>Wolf's standaard installatie bestaat uit drie rollen:</p>
        <ol>
            <li><span class="tooltip" title="Beheerder">Administrator</span></li>
            <li><span class="tooltip" title="Ontwikkelaar">Developer</span></li>
            <li><span class="tooltip" title="Redacteur">Editor</span></li>
        </ol>
        <p>De <strong>Administrator</strong> rol geeft de gebruiker standaard alle beschikbare permissies. Deze plugin maakt naamwijziging mogelijk, maar niet zijn permissies.</p>
        <p>De <strong>Developer</strong> rol, heeft een aantal restricties en staat toe om Pagina's, Layouts en Snippers te bewerken en gebruik van de Bestanden plugin.</p>
        <p>De <strong>Editor</strong> rol heeft beperkte restricties en staat het toevoegen, bewerken en verwijderen van <span class="tooltip" title="die niet zijn beveiligd">Pagina's</span> toe en het gebruik van de Bestanden plugin.</p>
    </div>

    <div class="roles_manager_wrap">
        <h3>Permissies ge&iuml;ntrocudeerd door de Plugin</h3>
        <p>Sommige plugins introduceren <em>mogelijk</em> nieuwe permissies aan je Wolf site. Als je bepaalde gebruikers toegang wilt geven tot die functionaliteit, dan kun je:</p>
            <ul>
                <li>Een specifieke rol maken die toegang geeft tot die plugins's <span class="tooltip" title="door de nieuw aangemaakte rol toe te wijzen">functionaliteit</span> en die rol een een gebruiker toe te wijzen.</li>
                <li>Een bestaande rol bewerken en die voorzien van nieuwe permissies.</li>
            </ul>            
    </div>
</div>