<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('sendlist_form.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$mode=optional_param('mode', 'add', PARAM_ALPHA);
$id=optional_param('id',0,PARAM_INT);
$confirm=optional_param('confirm', '', PARAM_ALPHANUM);

if ($id)
{
	$sendlist=$DB->get_record('local_notify_sendlist', array('id'=>$id));
}

$PAGE->set_pagelayout('admin');

$PAGE->navbar->add(get_string('pluginname','local_notify'));
$PAGE->navbar->add(get_string('templates','local_notify'),new moodle_url('/local/notify/templates.php'));

$custom = array('mode'=>$mode,'confirm'=>md5($mode));

$returnurl=new moodle_url('/local/notify/sendlists.php');

$mform=new notify_sendlist_form(null,$custom);

if ($mode=='add')
{
	if ($data=$mform->get_data())
	{
		if ($DB->insert_record('local_notify_sendlist', $data))
		{
			redirect($returnurl);
		}
	}
	else 
	{
		echo $OUTPUT->header();
		
		echo $OUTPUT->heading(get_string('add_sendlist','local_notify'));
		
		$mform->display();
		
		echo $OUTPUT->footer();
	}
}
else if ($mode=='edit' && confirm_sesskey())
{
	$mform->set_data($sendlist);
	
	if ($confirm!=md5($mode))
	{
		echo $OUTPUT->header();
		echo $OUTPUT->heading();
			
		$mform->display();
			
		echo $OUTPUT->footer();
		die();
	
	}
	else if($formdata=get_data())
	{
		if ($DB->update_record('local_notify_sendlist',$formdata))
		{
			redirect($returnurl,get_string('success'));
		}
	}
}
else if ($mode=='del' && confirm_sesskey())
{
	
}