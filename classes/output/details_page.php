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

use renderable;
use renderer_base;
use templatable;
use block_achievement\achievement;
require_once($CFG->dirroot.'/blocks/achievement/lib.php');
require_once($CFG->dirroot.'/blocks/achievement/locallib.php');
/**
 * Class containing data for achievement block.
 *
 */
class details_page implements renderable, templatable {
    /**
     * @var string The tab to display.
     */
    public $tab;

    /**
     * Constructor.
     *
     * @param string $tab The tab to display.
     */
    public function __construct($tab) {
        $this->tab = $tab;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        global $USER;
        $achievementmanager = new \block_achievement_manager();
        $availableachievements = achievement::get_records(['status' => BLOCK_ACHIEVEMENT_STATUS_ENABLE]);
        $lockedachievements = [];
        $unlockedachievements = [];
        foreach ($availableachievements as $achievement) {
            if ($achievementmanager->has_unlocked_achievement($achievement, $USER)) {
                $unlockedachievements[] = $achievement->to_record();
            } else {
                $lockedachievements[] = $achievement->to_record();
            }
        }
        return [
            'availableachievements' => $availableachievements,
            'nbavailableachievements' => count($availableachievements),
            'lockedachievements' => $lockedachievements,
            'nblockedachievements' => count($lockedachievements),
            'unlockedachievements' => $unlockedachievements,
            'nbunlockedachievements' => count($unlockedachievements),
            'viewingmyachievements' => empty($this->tab) || $this->tab === BLOCK_ACHIEVEMENT_MY_VIEW,
            'viewingglobalprogression' => $this->tab === BLOCK_ACHIEVEMENT_GLOBAL_VIEW,
        ];
    }
}
