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
require_once(__DIR__ . '/../../config.php');

$tab = optional_param('tab', null, PARAM_ALPHA);

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/blocks/achievement/details.php'));
$PAGE->set_pagelayout('standard');

$renderable = new \block_achievement\output\details_page($tab);
$renderer = $PAGE->get_renderer('block_achievement');
echo $renderer->header();
echo $renderer->render($renderable);
echo $renderer->footer();