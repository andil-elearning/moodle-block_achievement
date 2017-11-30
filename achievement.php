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
class achievement
{
    protected $achievementlist = null;
    protected $newachievementdonelist = array();
    protected $hasupdate = false;

    function load_achievementlist() {
        global $DB;
        $this->achievementlist = $DB->get_records('block_achievement', array('enable' => true), 'id ASC');
    }

    function get_achievementlist(){
        return $this->achievementlist;
    }
    function compute_achievement_for_user(&$user) {
        global $DB;
        if (!isset($user->achievementdone)) {
            //load achievement already done by $user
            $this->load_achievement_for_user($user);
        }
        //search for new achievement
        foreach ($this->achievementlist as $achievement) {
            if ($this->achievementdone_search($user->achievementdone, $achievement) === false) {
                if ($this->check_achievement_for_user($achievement, $user)) {
                    $newachievementdone = new stdClass();
                    $newachievementdone->achievementid = $achievement->id;
                    $newachievementdone->userid = $user->id;
                    $newachievementdone->timecreated = time();
                    $DB->insert_record('block_achievement_user', $newachievementdone);
                    $this->hasupdate = true;
                    $this->newachievementdonelist[] = $newachievementdone;
                }
            }
        }
        //reload achievement
        if ($this->hasupdate) $this->load_achievement_for_user($user);
    }

    function has_update() {
        return $this->hasupdate;
    }

    function load_achievement_for_user(&$user) {
        global $DB;
        $user->achievementdone = $DB->get_records_sql("
            SELECT ach_user.*
            FROM {block_achievement} AS ach, {block_achievement_user} AS ach_user
            WHERE
            (ach.id=ach_user.achievementid)
            AND (ach.enable=1)
            AND (ach_user.userid={$user->id})
            ORDER BY ach_user.timecreated DESC");
    }

    function check_achievement_for_user(&$achievement, &$user) {
        return $this->get_progress_achievement_for_user($achievement, $user) >= 1;
    }

    function get_progress_achievement_for_user(&$achievement, &$user) {
        if (method_exists($this->get_called_class(), $achievement->compute_function)) {
            $params = array();
            $params[] = $user;
            $nbrequire = 1;
            if (isset($achievement->compute_function_parameters)
            && !empty($achievement->compute_function_parameters)
        ) {
                $otherparamstmp = preg_split('/\|/', $achievement->compute_function_parameters);
                $nbrequire = $otherparamstmp[0];

                if(!empty($otherparamstmp)){
                    $otherparams = array();
                    for($i=1; $i < count($otherparamstmp); $i++){
                        $otherparams[] = $otherparamstmp[$i];
                  }
                }
                $params = array_merge($params, (!empty($otherparamstmp))?
                                        $otherparams : array($achievement->compute_function_parameters));
            }

            return (call_user_func_array(array($this, $achievement->compute_function), $params)/$nbrequire);
        }
        return false;
    }
    function get_donetime_achievement_for_user(&$achievement, &$user) {
        if (method_exists($this->get_called_class(), $achievement->compute_function)) {
            $params = array();
            $params[] = $user;
            if (isset($achievement->compute_function_parameters)
            && !empty($achievement->compute_function_parameters)
        ) {
                $otherparamstmp = preg_split('/\|/', $achievement->compute_function_parameters);

                if(!empty($otherparamstmp)){
                    $otherparams = array();
                    for($i=1; $i < count($otherparamstmp); $i++){
                        $otherparams[] = $otherparamstmp[$i];
                  }
                }
                $params = array_merge($params, (!empty($otherparamstmp))?
                                        $otherparams : array($achievement->compute_function_parameters));
            }

            return call_user_func_array(array($this, $achievement->compute_function), $params);
        }
        return false;
    }

    function achievement_search(&$parents, &$searched) {
        if (empty($searched) || empty($parents)) {
            return false;
        }
        foreach ($parents as $key => $value) {
            $exists = true;
            foreach ($searched as $skey => $svalue) {
                $exists = ($exists && isset($value->$skey) && ($value->$skey == $svalue));
            }
            if ($exists) {
                return $key;
            }
        }
        return false;
    }

    /*
    * search an achievement among an achievemnt_user list
    * parent => achievement_user table
    * $achievement => achievement
    */
    function achievementdone_search(&$parents, &$achievement) {
        if (empty($achievement) || empty($parents)) {
            return false;
        }
        foreach ($parents as $key => $value) {
            if ($value->achievementid == $achievement->id) return $key;
        }
        return false;
    }


    function get_achievementdone(&$achievement, &$user) {
        global $DB;
        return $DB->get_record_sql("
            SELECT *
            FROM {block_achievement_user} AS ach_user
            WHERE
            (ach_user.userid={$user->id})
            AND (ach_user.achievementid={$achievement->id})");
    }

    function get_newachievementdonelist() {
        return $this->newachievementdonelist;
    }

    function get_called_class() {
        if (function_exists('get_called_class'))
            return get_called_class();

        $t = debug_backtrace();
        $t = $t[0];

        if (isset($t['object']) && $t['object'] instanceof $t['class'])
            return get_class($t['object']);
        return false;
    }

    function build_achievement_shortname($achievement) {
        return $achievement->compute_function.'_'.preg_replace('/\|/', '_', $achievement->compute_function_parameters);
    }

    function build_achievement_generic_shortname($achievement) {
        return $achievement->compute_function;
    }

    function get_achievement_string($achievement, $suffix='') {
        //Search for specific string for this achievement
        $default_specific = '[['.$this->build_achievement_shortname($achievement).$suffix.']]';
        $a = preg_split('/\|/', $achievement->compute_function_parameters, 1);
        $res = get_string($this->build_achievement_shortname($achievement).$suffix, 'block_achievement', $a[0]);

        if($res != $default_specific)
            return $res;

        //Try to search for generic string for this achievement type
        $default_generic = '[['.$this->build_achievement_generic_shortname($achievement).$suffix.']]';
        $res = get_string($this->build_achievement_generic_shortname($achievement).$suffix, 'block_achievement', $a[0]);

        return ($res != $default_generic)?$res:$default_specific;
    }
    function get_achievement_pix($achievement) {
        global $CFG;
        $imgextension = '.png';
        $imgfilename = $this->build_achievement_shortname($achievement);
        if(!file_exists($CFG->dirroot.'/blocks/achievement/pix/'.$imgfilename.$imgextension))
            $imgfilename = $this->build_achievement_generic_shortname($achievement);
        return $imgfilename.$imgextension;
    }
    function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }
   /*
    * compute functions
    */
    function login($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='user'
              AND `action`='login'
              AND `userid`={$user->id}");
    }


    //Profile
    function update_avatar($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} as log
            WHERE
              `module`='upload'
              AND `action`='upload'
              AND time IN (
                SELECT time
                  FROM {log}
                  WHERE `userid`={$user->id}
                    AND `module`='user'
                    AND `action`='update')");
    }

    function update_profile($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
                  FROM {log} as log
                  WHERE `userid`={$user->id}
                    AND `module`='user'
                    AND `action`='update'");
    }

    //Forum
    function forum_view($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='forum'
              AND `action`='view forum'
              AND `userid`={$user->id}");
    }

    function forum_post($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='forum'
              AND `action`='add post'
              AND `userid`={$user->id}");
    }

    //PM
    function pm_sent($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='message'
              AND `action`='write'
              AND `userid`={$user->id}");
    }

    function pm_received($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='message'
              AND `action`='write'
              AND `info`={$user->id}");
    }

    //Blog
    function blog_view($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='blog'
              AND `action`='view'
              AND `userid`={$user->id}");
    }

    function blog_add($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='blog'
              AND `action`='add'
              AND `userid`={$user->id}");
    }

    //Chat
    function chat_talk($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='chat'
              AND `action`='talk'
              AND `userid`={$user->id}");
    }

    //BBB
    function bbb_join($user) {
        global $DB;
        return $DB->count_records_sql(
            "SELECT COUNT(log.id)
            FROM {log} AS log
            WHERE `module`='bigbluebutton'
              AND `action`='join'
              AND `userid`={$user->id}");
    }
    //Resource
    function resource_view($user, $distinct=false) {
        global $DB;
        $distinctstring = ($distinct !=false)?"DISTINCT":"";
        return $DB->count_records_sql(
            "SELECT COUNT($distinctstring log.cmid)
            FROM {log} AS log
            WHERE `module` = 'resource'
            AND `action` = 'view'
            AND `userid` = {$user->id}");
    }
    function resource_distinct_view($user) {
        return $this->resource_view($user, true);
    }
    //Assignment
    function assignment_view($user, $distinct=false) {
        global $DB;
        $distinctstring = ($distinct != false)?"DISTINCT":"";
        return $DB->count_records_sql(
            "SELECT COUNT($distinctstring log.cmid)
            FROM {log} AS log
            WHERE `module` = 'assignment'
            AND `action` = 'view'
            AND `userid` = {$user->id}");
    }
    function assignment_distinct_view($user) {
        return $this->assignment_view($user, true);
    }

    function assignment_done($user, $distinct=false) {
        global $DB;
        $distinctstring = ($distinct != false)?"DISTINCT":"";
        return $DB->count_records_sql(
            "SELECT COUNT($distinctstring log.cmid)
            FROM {log} AS log
            WHERE `module` = 'assignment'
            AND `action` = 'upload'
            AND `userid` = {$user->id}");
    }
}