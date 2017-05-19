<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
	die;
}

//$userid=required_param("users",PARAM_INT);
$id=required_param('id',PARAM_INT);
$unlisted_users=optional_param('unlisted_users',0,PARAM_INT);
$listed_users=optional_param('listed_users',0,PARAM_INT);
$add=optional_param('add_button',0,PARAM_BOOL);
$remove=optional_param('remove_button',0,PARAM_BOOL);

print_r($_POST);


if ($unlisted_users )
{
	
	foreach($unlisted_users as $uu)
	{
		
		if ($DB->execute("insert into {local_notify_listusers} (listid,userid) values(?,?)",array($id,$uu)))
		{
			//echo true;
		}
		else 
		{
			//echo false;
		}
	}
	
	echo true;
}
else if ($listed_users )
{
	foreach($listed_users as $lu)
	{
		if ($DB->execute("delete from {local_notify_listusers} where listid=? and userid=?",array($id,$lu)))
		{
			
		}
	}
	echo true;
}

//print_r($selected);