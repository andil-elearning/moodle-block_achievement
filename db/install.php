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
function xmldb_block_achievement_install() {
    global $DB;
    
    //Insert basics achievements
    $DB->execute("INSERT INTO {block_achievement} (enable, compute_function, compute_function_parameters) VALUES (1, 'login', '1'),
     (1, 'login', '5'),  
     (1, 'login', '20'),  
     (1, 'login', '50'),  
     (1, 'login', '100'),  
     (1, 'login', '200'),  
     (1, 'login', '500'),  
     (1, 'update_avatar', '1'),  
     (1, 'update_profile', '1'),  
     (1, 'forum_view', '1'),  
     (1, 'forum_post', '1'),  
     (1, 'forum_post', '10'),  
     (1, 'pm_sent', '1'),  
     (1, 'pm_received', '1'),  
     (1, 'blog_view', '1'),  
     (1, 'blog_add', '1'),  
     (1, 'chat_talk', '1'),  
     (1, 'bbb_join', '1'),  
     (1, 'resource_view', '1'),  
     (1, 'resource_view', '5'),  
     (1, 'resource_view', '20'),  
     (1, 'resource_view', '50'),  
     (1, 'resource_distinct_view', '5'),  
     (1, 'resource_distinct_view', '20'),  
     (1, 'resource_distinct_view', '50'),  
     (1, 'assignment_view', '1'),  
     (1, 'assignment_view', '5'),  
     (1, 'assignment_view', '20'),  
     (1, 'assignment_view', '50'),  
     (1, 'assignment_distinct_view', '5'),  
     (1, 'assignment_distinct_view', '20'),  
     (1, 'assignment_distinct_view', '50')");
}

?>