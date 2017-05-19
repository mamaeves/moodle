<?php 
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');


global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('mailingbydate','local_notify'),2);

echo $OUTPUT->single_button(new moodle_url('/local/notify/mailingbydate_edit.php',array('action'=>'add')),get_string('add_mailingbydate','local_notify'));

$plannedmailing=$DB->get_records_sql("select t.id,tmpl.template_name,tmpl.id as template_id, s.sendlistname,s.id as sendlist_id, t.sendtime,t.done,t.timemailed from {local_notify_timetable} as t, {local_notify_templates} as tmpl, {local_notify_sendlist} as s where tmpl.id=t.template_id and t.sendlistid=s.id");

$table=new html_table();

$table->head=array('№',get_string('date'),get_string('template_name','local_notify'),get_string('sendlist','local_notify'),get_string('status'),get_string('senttime','local_notify'),get_string('actions'));

$c=1;
foreach($plannedmailing as $p)
{
	if ($p->done==0)
	{
		$status=get_string('planned','local_notify');
	}
	else 
	{
		$status=get_string('sent','local_notify');
	}
	
	$buttons=array();
	$buttons[]=html_writer::link(new moodle_url('/local/notify/mailingbydate_edit.php',array('mode'=>'edit','id'=>$p->id,'sesskey'=>sesskey())), get_string('edit'));
	$buttons[]=html_writer::link(new moodle_url('/local/notify/mailingbydate_edit.php',array('mode'=>'del','id'=>$p->id,'sesskey'=>sesskey())),get_string('delete'));
	
	$template_name=html_writer::link(new moodle_url('/local/notify/template_edit.php',array('id'=>$p->template_id,'mode'=>'edit','sesskey'=>sesskey())), $p->template_name);
	$sendlistname=html_writer::link(new moodle_url('/local/notify/sendlist_users.php',array('id'=>$p->sendlist_id)),$p->sendlistname);
	
	if (!$p->timemailed)
	{
		$timemailed='-';
	}
	else 
	{
		$timemailed=date('d.m.Y H:i:s',$p->timemailed);
	}
	
	$table->data[]=array($c,date('d.m.Y H:i:s',$p->sendtime),$template_name,$sendlistname,$status,$timemailed,implode(' ',$buttons));
	$c++;
}

echo html_writer::table($table);

echo $OUTPUT->footer();