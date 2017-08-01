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
namespace block_achievement;
defined('MOODLE_INTERNAL') || die();

use core\persistent;
require_once($CFG->dirroot.'/blocks/achievement/lib.php');
class achievement extends persistent {

    /** Table name for the persistent. */
    const TABLE = 'block_achievement';

    /**
     * Return the definition of the properties of this model.
     * @return array
     */
    protected static function define_properties() {
        return [
            'identifier' => [
                'type' => PARAM_ALPHANUMEXT,
            ],
            'status' => [
                'type' => PARAM_INT,
                'default' => BLOCK_ACHIEVEMENT_STATUS_ENABLE,
                'choices' => [BLOCK_ACHIEVEMENT_STATUS_ENABLE, BLOCK_ACHIEVEMENT_STATUS_DISABLE]
            ],
            'query' => [
                'type' => PARAM_RAW,
            ],
            'threshold' => [
                'type' => PARAM_INT,
            ]
        ];
    }
}
