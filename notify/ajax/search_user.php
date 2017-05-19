<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
	die;
}

$lastname=required_param('lastname',PARAM_TEXT);

//$users=$DB->get_records('user',array('lastname'=>$lastname));
$users=$DB->get_records_sql('select * from {user} where lastname like ?',array("%".$lastname."%"));

/*
foreach($users as $u)
{
	//print_r($u);
}
*/
//echo mb_detect_encoding($lastname,'UTF-8',true);
echo json_encode($users);