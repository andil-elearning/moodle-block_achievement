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
 * Code to be executed after the plugin's database scheme has been installed
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/blocks/achievement/lib.php');
function xmldb_block_achievement_install() {

    $achievementrecords = [
        [
            'identifier'    => 'core_login_1',
            'status'        => BLOCK_ACHIEVEMENT_STATUS_DISABLE,
            'query'         => "SELECT COUNT(id)
 FROM {logstore_standard_log}
 WHERE `eventname`='\\\\core\\\\event\\\\user_loggedin' AND `userid`=:userid",
            'threshold'     => 1
        ],
        [
            'identifier'    => 'core_forum_viewed_1',
            'status'        => BLOCK_ACHIEVEMENT_STATUS_DISABLE,
            'query'         => "SELECT COUNT(id)
 FROM {logstore_standard_log}
 WHERE `eventname`='\\\\mod_forum\\\\event\\\\course_module_viewed' AND `userid`=:userid",
            'threshold'     => 1
        ],
    ];

    foreach ($achievementrecords as $record) {
        $achievement = new \block_achievement\achievement(0, (object)$record);
        $achievement->create();
    }
    return true;
}
