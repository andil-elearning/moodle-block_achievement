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
 * Achievement edition
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('add_achievement');

// Retrieve all events in a list.
$completelist = report_eventlist_list_generator::get_all_events_list();

$tabledata = array();
$components = array('0' => get_string('all', 'report_eventlist'));
$edulevel = array('0' => get_string('all', 'report_eventlist'));
$crud = array('0' => get_string('all', 'report_eventlist'));
foreach ($completelist as $value) {
    $components[] = $value['component'];
    $edulevel[] = $value['edulevel'];
    $crud[] = $value['crud'];
    $tabledata[] = (object)$value;
}
$components = array_unique($components);
$edulevel = array_unique($edulevel);
$crud = array_unique($crud);

// Create the filter form for the table.
$filtersection = new report_eventlist_filter_form(null, array('components' => $components, 'edulevel' => $edulevel,
        'crud' => $crud));

// Output.
$renderer = $PAGE->get_renderer('report_eventlist');
echo $renderer->render_event_list($filtersection, $tabledata);
