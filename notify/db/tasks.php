<?php
$tasks = array(
		array(
				'classname' => 'local_notify\task\mailing_by_date',
				'blocking' => 0,
				'minute' => '*/5',
				'hour' => '*',
				'day' => '*',
				'dayofweek' => '*',
				'month' => '*'
		),
		array(
				'classname'=>'local_notify\task\birthday_mail',
				'blocking'=>0,
				'minute'=>'5',
				'hour'=>'10',
				'day'=>'*',
				'dayofweek'=>'*',
				'month'=>'*'
		)
);