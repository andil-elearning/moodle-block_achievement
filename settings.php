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
defined('MOODLE_INTERNAL') || die;
//enable/disable notification JS (splashscreen)
$settings->add(new admin_setting_configcheckbox('block_achievement_jsnotification', get_string('settings_enablejsnotification', 'block_achievement'),
    get_string('settings_enablejsnotification_desc', 'block_achievement'), 1));

//nb achievement to display in block
$settings->add(new admin_setting_configtext('block_achievement_nbachievementinblock', get_string('settings_nbachievementinblock', 'block_achievement'),
    get_string('settings_nbachievementinblock_desc', 'block_achievement'), 5, PARAM_INT));
?>