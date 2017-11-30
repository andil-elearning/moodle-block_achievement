<?php
// This file is part of the achievement plugin for Moodle
// This plugin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This plugin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this plugin.  If not, see <http://www.gnu.org/licenses/>.


/**
 *
 * @package achievement
 * @copyright 2012 Andil
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!isset($filtertype)) {
    $filtertype = '';
}
if (!isset($filterselect)) {
    $filterselect = '';
}

//make sure everything is cleaned properly
$filtertype = clean_param($filtertype, PARAM_ALPHA);
$filterselect = clean_param($filterselect, PARAM_INT);


$inactive = NULL;
$activetwo = NULL;
$toprow = array();

$toprow[] = new tabobject('personaltab', $CFG->wwwroot . '/blocks/achievement/personal.php',
    get_string('personaltab', 'block_achievement'));

$toprow[] = new tabobject('maintab', $CFG->wwwroot . '/blocks/achievement/global_progression.php',
    get_string('maintab', 'block_achievement'));



$tabs = array($toprow);
print_tabs($tabs, $currenttab, $inactive, $activetwo);
?>
