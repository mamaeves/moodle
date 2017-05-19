<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$id=required_param('id',PARAM_INT);

$sendlist=$DB->get_record('local_notify_sendlist',array('id'=>$id));

$PAGE->navbar->add(get_string('pluginname','local_notify'));
$PAGE->navbar->add(get_string('mailing','local_notify'));
$PAGE->navbar->add(get_string('sendlists','local_notify'),new moodle_url('/local/notify/sendlists.php'));
$PAGE->navbar->add($sendlist->sendlistname);

$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('sendlist_users','local_notify').' "'.$sendlist->sendlistname.'"',2);

echo $OUTPUT->single_button(new moodle_url('/local/notify/sendlist_users_edit.php',array('id'=>$sendlist->id)),get_string('sendlist_users_edit','local_notify'));

$listusers=$DB->get_records_sql('select u.id,u.lastname,u.firstname from {user} as u,{local_notify_listusers} as lu where  lu.listid=? and lu.userid=u.id order by u.lastname asc',array($id));

$table=new html_table();
$table->head=array("#",get_string('user'));
$c=1;
foreach($listusers as $lu)
{
	$table->data[]=array($c,$lu->lastname.' '.$lu->firstname);
	$c++;
}

echo html_writer::table($table);

echo $OUTPUT->footer();