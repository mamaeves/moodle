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
 * @package    local-notify
 * @copyright  Mamaev Evgenii <mamaeves@mail.ru>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 function local_notify_extend_navigation(global_navigation $navigation)
 {
	 global $CFG, $COURSE,$PAGE,$SESSION,$SITE,$USER;
	 
	 $context = context_system::instance();
	 	 
	 $text=get_string('pluginname','local_notify');
	 
	 $url=new moodle_url('/local/notify/index.php');
	 
	 
	 $node=$navigation->add($text,$url);
	 
	 if (has_capability('moodle/site:config', $context))
	 {
	 	$node->add(get_string('settings'),new moodle_url('/local/notify/settings.php'));
	 	$node->add(get_string('templates','local_notify'),new moodle_url('/local/notify/templates.php'));
	 	$nodemailing=$node->add(get_string('mailing','local_notify'));
	 	$nodemailing->add(get_string('mailingbydate','local_notify'),new moodle_url('/local/notify/mailingbydate.php'));
	 	$nodemailing->add(get_string('sendlists','local_notify'),new moodle_url('/local/notify/sendlists.php'));
	 }
 }
 
 
 function local_notify_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array())
 {
 	/*
 	if ($filearea !== 'template_text') {
 		return false;
 	}
 	*/
 	
 	$itemid = array_shift($args); // The first item in the $args array.
 	
 	$filename = array_pop($args); // The last item in the $args array.
 	if (!$args) {
 		$filepath = '/'; // $args is empty => the path is '/'
 	} else {
 		$filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
 	}
 	
 	$fs = get_file_storage();
 	
 	$file = $fs->get_file($context->id, 'local_notify', $filearea, $itemid, $filepath, $filename);
 	
 	/*
 	if (!$file) {
 		return false; // The file does not exist.
 	}
 	*/
 	
 	send_stored_file($file);
 }
 