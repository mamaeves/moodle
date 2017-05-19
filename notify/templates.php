<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$action=optional_param('action','',PARAM_ALPHA);
$id=optional_param('id',0,PARAM_INT);

$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('templates','local_notify'),2);

if (!$id)
{
	$templates=$DB->get_records('local_notify_templates');
	
	$table=new html_table(); 
	$table->head=array('#',get_string('template_name','local_notify'),get_string('actions'));
	
	$c=1;
	foreach($templates as $t)
	{
		$buttons=array();
		$buttons[]=html_writer::link(new moodle_url('/local/notify/template_edit.php',array('id'=>$t->id,'mode'=>'edit','sesskey'=>sesskey())),get_string('edit'));
		$buttons[]=html_writer::link(new moodle_url('/local/notify/template_edit.php',array('id'=>$t->id,'mode'=>'del','sesskey'=>sesskey())),get_string('delete'));
		
		$table->data[]=array($c,'<a href="'.$CFG->wwwroot.'/local/notify/templates.php?action=view&&id='.$t->id.'">'.$t->template_name.'</a>',implode(' ',$buttons));
		$c++;
	}
	
	echo $OUTPUT->single_button(new moodle_url('/local/notify/template_edit.php',array('action'=>'add')),get_string('add_template','local_notify'));
	
	echo html_writer::table($table);

}
else if ($id && $action=='view') 
{
	$template=$DB->get_record('local_notify_templates',array('id'=>$id));
	
	echo $OUTPUT->heading($template->template_name,2);
	
	$content = file_rewrite_pluginfile_urls($template->template_text, 'pluginfile.php', $context->id, 'local_notify', 'template_text', $template->id);
	
	echo $content;
}

echo $OUTPUT->footer();