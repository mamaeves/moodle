<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('mailingbydate_form.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$id=optional_param('id',0,PARAM_INT);
$mode=optional_param('mode','add',PARAM_ALPHA);
$confirm=optional_param('confirm', '', PARAM_ALPHANUM);

$PAGE->set_pagelayout('admin');

if ($id)
{
	$timetable=$DB->get_record('local_notify_timetable',array('id'=>$id));
}

$templates=$DB->get_records('local_notify_templates');
$templates_ar=array();
foreach($templates as $t)
{
	$templates_ar[$t->id]=$t->template_name;
}

$sendlists=$DB->get_records('local_notify_sendlist');
$sendlists_ar=array();
foreach($sendlists as $s)
{
	$sendlists_ar[$s->id]=$s->sendlistname;
}

$returnurl=new moodle_url('/local/notify/mailingbydate.php');

$mform=new notify_mailingbydate_form(null,array('templates_ar'=>$templates_ar,'sendlists_ar'=>$sendlists_ar,'id'=>$id,'mode'=>$mode,'confirm'=>md5($mode)));

if ($mode=='add')
{
	if ($data=$mform->get_data())
	{
		$ins=(object)array(
				'template_id'=>$data->template_id,
				'sendtime'=>$data->sendtime,
				'sendlistid'=>$data->sendlistid
		);
		
		$DB->insert_record('local_notify_timetable', $ins);
		
		redirect($returnurl,get_string('success'));
	}
}
else if ($mode=='edit' && confirm_sesskey())
{
	$mform->set_data($timetable);
	
	if ($confirm!=md5($mode))
	{
		echo $OUTPUT->header();
		echo $OUTPUT->heading();
		
		$mform->display();
			
		echo $OUTPUT->footer();
		die();
	}
	else if ($formdata=$mform->get_data())
	{
		if ($formdata->sendtime>$timetable->timemailed)
		{
			$formdata->timemailed=0;
			$formdata->done=0;
		}
		if ($DB->update_record('local_notify_timetable', $formdata))
		{
			redirect($returnurl,get_string('success'));
		}
	}
	
}
else if ($mode=='del' && confirm_sesskey())
{
	
}

/*
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('mailingbydate','local_notify'),2);



$mform->display();



echo $OUTPUT->footer();
*/