Achievement

DESCRIPTION
=============
A block based module which add achievement notion.
Currently achievements are triggered on user's action like login, talk on chat...

QUICK INSTALL
=============
This block follows standard Moodle Block install instructions
1) Copy achievement folder into your blocks folder

2) Connect as Administrator and visit admin page to activate it

POST INSTALL (optional)
=============
1) To compute achievements for all users Run http://mymoodle.com/blocks/achievement/cron.php

2) If you want to enable JS notification you need to paste this code in your html theme header(meta.php or header.html)
----------------
<?php
if ($CFG->block_achievement_jsnotification) {
?>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/blocks/achievement/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/blocks/achievement/js/colorbox/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="<?php echo $CFG->wwwroot; ?>/blocks/achievement/js/achievement_splashscreen.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot; ?>/blocks/achievement/js/colorbox/colorbox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot; ?>/blocks/achievement/css/achievement.css" />
<?php
}
?>
----------------

CONFIGURATION
=============
Plugin configuration can be done in "Site Administration block => Modules => Blocks => Achievement" page
URL : http://mymoodle.com/admin/settings.php?section=blocksettingachievement

BUG REPORT
==============
If you have bugs or suggestions regarding this bloc use :
http://bts.andil.fr

CONTACT
=============
If you have any questions regarding this bloc contact :
achievement-support@andil.fr
