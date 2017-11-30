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

$user = $USER; //TODO  handle $_GET['id'] to display other user achievement
$navlinks[] = array('name' => get_string('blockname', 'block_achievement'),
    'link' => '',
    'type' => 'title');

$navlinks[] = array('name' => fullname($user),
    'link' => '',
    'type' => 'title');

$navigation = build_navigation($navlinks);

print_header_simple(get_string('modulename', 'block_achievement'), '', $navigation);

$tableheadtitle = get_string('tablehead_title', 'block_achievement');
$tableheaddesc = get_string('tablehead_desc', 'block_achievement');
$tableheaddate = get_string('tablehead_date', 'block_achievement');
$tableheadpercent = get_string('tablehead_accomplishmentpercent', 'block_achievement');

$currenttab = 'personaltab';
require('tabs.php');
?>
<script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/blocks/achievement/js/detail.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot; ?>/blocks/achievement/css/achievement.css" />
<?php
$nbuser = count_records('user', 'deleted', false);
$achievementprocessor = new achievement();
$achievementprocessor->load_achievementlist();
$achievementlist = $achievementprocessor->get_achievementlist();
$orderachievementlist = array('done' => array(), 'todo' => array());

foreach ($achievementlist as $currentachievement) {
    $tmp = array('achievement' => $currentachievement->id);
    $params = preg_split('/\|/', $currentachievement->compute_function_parameters);

    if (($tmp['hassub'] = ($params[0] > 1)) === true) {
        //compute progression
        $tmp['progress'] = $achievementprocessor->achievementdone_search($user->achievementdone, $currentachievement) ?
            1 : $achievementprocessor->get_progress_achievement_for_user($currentachievement, $user);
    }
    else {
        $tmp['progress'] = $achievementprocessor->achievementdone_search($user->achievementdone, $currentachievement) ?
            1 : 0;
    }

    if (($tmp['hassub'] && ($tmp['progress'] >= 1)) ||
        ($achievementprocessor->achievementdone_search($user->achievementdone, $currentachievement))
    ) {
        $achuserdata = $achievementprocessor->get_achievementdone($currentachievement, $user);
        $tmp['date'] = userdate($achuserdata->timecreated);
        $orderachievementlist['done'][] = $tmp;
    }
    else {
        $orderachievementlist['todo'][] = $tmp;
    }
}
$achievementprocessor->aasort($orderachievementlist['todo'], 'progress');
$orderachievementlist['todo'] = array_reverse($orderachievementlist['todo']);

foreach ($orderachievementlist as $key => $value) {
    print_heading_block(get_string($key, 'block_achievement'));
    $attr = array('class' => ($key == 'done') ? 'achievementDone' : 'achievementTodo');

    foreach ($value as $data) {
        $currentachievement = $achievementlist[$data['achievement']];
        ?>
    <div id="<?php echo $achievementprocessor->build_achievement_shortname($currentachievement); ?>" class="achievementEntry <?php echo $attr['class'];?>">

        <div class="achieveTxtHolder">
        <div class="achieveImgHolder">
            <div class="pix">
                <img
                    src="<?php echo $CFG->wwwroot . '/blocks/achievement/pix/' .$achievementprocessor->get_achievement_pix($currentachievement); ?>"
                    alt="<?php echo $achievementprocessor->get_achievement_string($currentachievement); ?>"/></div>
        </div>
            <div class="achieveTxt">
                <h3><?php echo $achievementprocessor->get_achievement_string($currentachievement); ?></h3>
                <h5><?php echo $achievementprocessor->get_achievement_string($currentachievement, '_desc'); ?></h5>
                <?php
        if(isset($data['hassub']) && $data['hassub']) {
            ?>
            <img
                src="<?php echo $CFG->wwwroot.'/blocks/achievement/pix/expand.png';?>"
                alt="<?php echo get_string('seeprogression', 'block_achievement') ;?>"
                title="<?php echo get_string('seeprogression', 'block_achievement') ;?>"
                class="expand_icon">
            <div class="progressbar">
                <div class="bar" style="width:<?php echo $data['progress'] * 100-0.2;?>%; display:block;"></div>
                <div class="progressbar-right"></div>
                <div class="status"><?php
                        $nb = $achievementprocessor->get_donetime_achievement_for_user($currentachievement, $user);
                        $params = preg_split('/\|/', $currentachievement->compute_function_parameters);
                        if($nb > $params[0]) $nb = $params[0];

                        echo $nb.' / '.$params[0].' ('. ($data['progress'] * 100) . '%)';
                        ?></div>
            </div>
            <?php
        }
        ?>
            </div>
           <?php
            if($key == 'done'){
                ?>
                <div class="achieveDate">
                    <?php echo $data['date'];?>
                </div>
                <?php
            }
            ?>
        </div>
        
    </div>
    <?php
    }
}
print_footer();
?>