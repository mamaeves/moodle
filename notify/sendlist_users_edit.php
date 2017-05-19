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

$PAGE->requires->js('/local/notify/library/js/sendlist_users_edit.js');

$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading($sendlist->sendlistname.' '.get_string('sendlist_users_edit','local_notify'),2);

$listed_users=$DB->get_records_sql("select u.id,u.lastname,u.firstname from {user} as u,{local_notify_listusers} as lu where lu.listid=? and lu.userid=u.id order by u.lastname asc",array($id));

$unlisted_users=$DB->get_records_sql("select u.id,u.lastname,u.firstname from {user} as u where u.id not in(select u.id from {user} as u,{local_notify_listusers} as lu where lu.listid=? and lu.userid=u.id) order by u.lastname asc",array($id));

include_once('sendlist_users_form.php');

echo $OUTPUT->footer();