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
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */

defined('MOODLE_INTERNAL') || die();

use \block_achievement\achievement;
class block_achievement_manager {
    /**
     * @var cache_application|cache_session|cache_store
     */
    private $achievementsstatistics;

    /**
     * Constructor for block_achievement_manager
     */
    public function __construct() {
        $this->achievementsstatistics = cache::make('block_achievement', 'achievementsstatistics');
    }
    /**
     * @param \stdClass $user
     * @return array
     */
    public function get_unlocked_achievements_for_user(\stdClass $user) {
        $results = [];
        $achievements = achievement::get_records(['status' => BLOCK_ACHIEVEMENT_STATUS_ENABLE]);
        foreach ($achievements as $achievement) {
            if ($this->has_unlocked_achievement($achievement, $user)) {
                $results[] = $achievement;
            }
        }
        return $results;
    }

    /**
     * @param achievement $achievement
     * @param \stdClass $user
     * @return bool
     * @throws \coding_exception
     * @throws \dml_missing_record_exception
     * @throws \dml_multiple_records_exception
     */
    public function has_unlocked_achievement(achievement $achievement, \stdClass $user) {
        global $DB;
        $result = $DB->count_records_sql($achievement->get('query'), ['userid' => $user->id]);
        if ($result) {
            return $result >= $achievement->get('threshold');
        }
        return false;
    }
}