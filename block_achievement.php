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
class block_achievement extends block_base {

    function init() {
        $this->title = get_string('blockname', 'block_achievement');
        $this->version = 2012090410;
    }

    function has_config() {
        return true;
    }

    function get_content() {
        global $CFG, $USER;
        $nbloop = 0;
        require_once($CFG->dirroot.'/blocks/achievement/achievement.php');

        /*
         * Achievement init
         */
        if($USER->id==0) return NULL;
        $achievementprocessor = new achievement();
        $achievementprocessor->load_achievementlist();
        $achievementprocessor->compute_achievement_for_user($USER);
        $achievementlist = $achievementprocessor->get_achievementlist();
        $this->content = new stdClass;
        $this->content->footer = '<a href="'.$CFG->wwwroot.'/blocks/achievement/personal.php">'.
                                get_string('fulllistofachievements', 'block_achievement').'</a>...';

        if ((count($USER->achievementdone)>0) &&
                ($USER->achievementdone != false)) {
            $this->content->text = '<ul class="list">';

            foreach ($USER->achievementdone as $currentachdone) {
                $currentachievement = $achievementlist[$currentachdone->achievementid];

                if (++$nbloop <= $CFG->block_achievement_nbachievementinblock) {
                    $itemclass = '';
                    if ($achievementprocessor->has_update() &&
                        ($achievementprocessor->achievementdone_search($achievementprocessor->get_newachievementdonelist(),
                                                    $currentachievement)!==false)){
                        $itemclass = ' new';
                    }

                    $content = '
                    <div class="icon column c0">
                        <img
                        src="'.$CFG->wwwroot.'/blocks/achievement/pix/'.$achievementprocessor->get_achievement_pix($currentachievement).'"
                        class="icon '.$achievementprocessor->build_achievement_shortname($currentachievement).'"
                        alt="'.$achievementprocessor->get_achievement_string($currentachievement).'" />
                     </div>';

                    $content .= '<div class="column">'.
                        $achievementprocessor->get_achievement_string($currentachievement).
                                '</div>';
                    $this->content->text .= '
                    <li class="achievementitem'.$itemclass.'">'.$content.'</li>';
                }
            }
            $this->content->text .='</ul>';
        }
        else {
            $this->content->text = get_string('no_achievement', 'block_achievement');
        }
        return $this->content;
    }
}
