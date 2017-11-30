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
require(dirname(__FILE__).'/../../config.php');
require('achievement.php');
$PAGE->set_url('/blocks/achievement/detail.php');
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));

$user = $USER; //TODO  handle $_GET['id'] to display other user achievement
$navlinks[] = array('name' => get_string('blockname', 'block_achievement'),
    'link' => '',
    'type' => 'title');

$navlinks[] = array('name' => fullname($user),
        'link' => '',
        'type' => 'title');

$navigation = build_navigation($navlinks);

print_header_simple(get_string('modulename', 'block_achievement'), '', $navigation);

$tableheadtitle    = get_string('tablehead_title', 'block_achievement');
$tableheaddesc     = get_string('tablehead_desc', 'block_achievement');
$tableheaddate     = get_string('tablehead_date', 'block_achievement');
$tableheadpercent  = get_string('tablehead_accomplishmentpercent', 'block_achievement');

$currenttab = 'maintab';
require('tabs.php');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot; ?>/blocks/achievement/css/achievement.css" />
<h2 id="introduction" class="headingblock header">
    <?php echo get_string('global_progression_intro', 'block_achievement');?>
</h2>
<?php
$nbuser = $DB->count_records('user', array('deleted' => false));
$table->head = array($tableheadtitle, $tableheaddesc, $tableheaddate, $tableheadpercent);

$achievementprocessor = new achievement();
$achievementprocessor->load_achievementlist();
$achievementlist = $achievementprocessor->get_achievementlist();
$orderachievementlist = array();

foreach($achievementlist as $currentachievement){
    $orderachievementlist[] = array('achievement' => $currentachievement->id,
            'percent' => ($DB->count_records('block_achievement_user', array('achievementid' => $currentachievement->id))/$nbuser)*100);
}

$achievementprocessor->aasort($orderachievementlist, 'percent');
$orderachievementlist = array_reverse($orderachievementlist);

foreach($orderachievementlist as $data){
    $currentachievement = $achievementlist[$data['achievement']];
    $hasAchievement = $achievementprocessor->achievementdone_search($user->achievementdone, $currentachievement);
    $attr = array('class' => ($hasAchievement!==false)?'achievementDone':'achievementTodo');
?>
<div id="<?php echo $achievementprocessor->build_achievement_shortname($currentachievement); ?>" class="achievementEntry <?php echo $attr['class'];?>">

<div class="achieveTxtHolder">
    <div class="achieveImgHolder">
        <div class="pix">
        	<img
                src="<?php echo $CFG->wwwroot.'/blocks/achievement/pix/'.$achievementprocessor->get_achievement_pix($currentachievement);?>"
                alt="<?php echo $achievementprocessor->get_achievement_string($currentachievement); ?>" />
        </div>
    </div>
    <div class="achieveTxt">
        <h3><?php echo $achievementprocessor->get_achievement_string($currentachievement); ?></h3>
        <div class="progressbar" style="display:block">
                <div class="bar" style="width:<?php echo round($data['percent']);?>%; display:block;"></div>
                <div class="progressbar-right"></div>
                <div class="status"><?php echo round($data['percent']);?>%</div>
        </div>
    </div>
</div>
</div>
<?php
}
$OUTPUT->footer();
?>