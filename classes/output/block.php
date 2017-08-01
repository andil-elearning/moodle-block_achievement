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
 * Class containing data for Achievement block.
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */
namespace block_achievement\output;
defined('MOODLE_INTERNAL') || die();

use block_achievement\achievement;
use renderable;
use renderer_base;
use templatable;
require_once($CFG->dirroot.'/blocks/achievement/locallib.php');
/**
 * Class containing data for achievement block.
 *
 */
class block implements renderable, templatable {
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        global $USER;
        $achievementmanager = new \block_achievement_manager();
        $unlockedachievements = [];
        $achievements = $achievementmanager->get_unlocked_achievements_for_user($USER);
        foreach ($achievements as $achievement) {
            $unlockedachievements[] = $achievement->to_record();
        }
        $nbachievements = achievement::count_records(['status' => BLOCK_ACHIEVEMENT_STATUS_ENABLE]);
        return [
            'nbavailableachievements' => $nbachievements,
            'nbunlockedachievements' => count($unlockedachievements),
            'unlockedachievements' => $unlockedachievements
        ];
    }
}
