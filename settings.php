<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for block "achievement"
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */

defined('MOODLE_INTERNAL') || die();

$category = new admin_category('block_achievement', get_string('pluginname', 'block_achievement'));
$customsettings = new admin_settingpage('settings', get_string('settings'));
// Enable/disable notifications.
$customsettings->add(new admin_setting_configcheckbox('block_achievement/notification',
    get_string('settings_enablenotification', 'block_achievement'),
    get_string('settings_enablenotification_desc', 'block_achievement'), 0));

// Number of achievement to display in block.
$customsettings->add(new admin_setting_configtext('block_achievement/nbachievementinblock',
    get_string('settings_nbachievementinblock', 'block_achievement'),
    get_string('settings_nbachievementinblock_desc', 'block_achievement'), 5, PARAM_INT));

$category->add(
    'block_achievement',
    new admin_externalpage(
        'manage_achievements',
        new lang_string('manage_achievements', 'block_achievement'),
        $CFG->wwwroot . '/blocks/achievement/manage.php'
    )
);
$category->add(
    'block_achievement',
    new admin_externalpage(
        'add_achievement',
        new lang_string('add_achievement', 'block_achievement'),
        $CFG->wwwroot . '/blocks/achievement/edit.php'
    )
);
$category->add('block_achievement', $customsettings);
$ADMIN->add('blocksettings', $category);