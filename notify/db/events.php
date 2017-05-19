<?php

$observers=array(
		
		array(
				'eventname'   => '\core\event\course_completed',
				'callback'    => 'local_notify_observer::course_completed',
				'includefile'=>'/local/notify/classes/observer.php'
		)
);