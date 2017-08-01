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
 * achievement block rendrer
 *
 * @package    block_achievement
 * @copyright  2017 ANDIL {@link https://www.andil.fr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Arnaud Trouvé <arnaud.trouve@andil.fr>
 */
namespace block_achievement\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

/**
 * achievement block renderer
 *
 * @package    block_achievement
 */
class renderer extends plugin_renderer_base {
    /**
     * Return the content for the block achievement.
     *
     * @param block $block The block renderable
     * @return string HTML string
     */
    public function render_block(block $block) {
        return $this->render_from_template('block_achievement/block', $block->export_for_template($this));
    }
    /**
     * Return the content for the details page.
     *
     * @param details_page $page The details_page renderable
     * @return string HTML string
     */
    public function render_details_page(details_page $page) {
        return $this->render_from_template('block_achievement/details_page', $page->export_for_template($this));
    }
    /**
     * Return the content for the manage page.
     *
     * @param manage_page $page The manage_page renderable
     * @return string HTML string
     */
    public function render_manage_page(manage_page $page) {
        return $this->render_from_template('block_achievement/manage_page', $page->export_for_template($this));
    }
}
