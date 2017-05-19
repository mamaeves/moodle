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

echo $OUTPUT->heading(get_string('sendlists','local_notify'),2);

$sendlists=$DB->get_records('local_notify_sendlist');

$table=new html_table();
$table->head=array('#',get_string('sendlistname','local_notify'),get_string('actions'));

$c=1;
foreach($sendlists as $s)
{
	$buttons=array();
	$buttons[]=html_writer::link(new moodle_url('/local/notify/sendlist_edit.php',array('mode'=>'edit','id'=>$s->id,'sesskey'=>sesskey())), get_string('edit'));
	$buttons[]=html_writer::link(new moodle_url('/local/notify/sendlist_edit.php',array('mode'=>'del','id'=>$s->id,'sesskey'=>sesskey())),get_string('delete'));
	$table->data[]=array($c,html_writer::link(new moodle_url('/local/notify/sendlist_users.php',array('id'=>$s->id)),$s->sendlistname),implode(' ',$buttons));
	$c++;
}

echo html_writer::table($table);

echo $OUTPUT->single_button(new moodle_url('/local/notify/sendlist_edit.php',array('action'=>'add')),get_string('add_sendlist','local_notify'));

echo $OUTPUT->footer();