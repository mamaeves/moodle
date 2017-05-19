<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('template_form.php');

global $DB;

$context = context_system::instance();

if (!has_capability('moodle/site:config', $context))
{
	print_error(get_string('nopermissiontoshow'));
}

$mode=optional_param('mode', 'add', PARAM_ALPHA);
$id=optional_param('id',0,PARAM_INT);
$confirm=optional_param('confirm', '', PARAM_ALPHANUM);

$maxfiles=10;
$maxbytes=2097152;
$editoroptions = array('noclean' => true, 'subdirs' => true, 'maxfiles' => $maxfiles, 'maxbytes' => $maxbytes, 'context' => $context);

$template=null;
if ($id)
{
	$template=$DB->get_record('local_notify_templates',array('id'=>$id),'*',MUST_EXIST);
	$formdata=clone($template);
	$formdata=file_prepare_standard_editor($formdata, 'template_text', $editoroptions,$context,'local_notify','template_text',$formdata->id);
}
else 
{
	$formdata=new stdClass();
}

$PAGE->set_pagelayout('admin');

$PAGE->navbar->add(get_string('pluginname','local_notify'));
$PAGE->navbar->add(get_string('templates','local_notify'),new moodle_url('/local/notify/templates.php'));

if ($template)
{
	$PAGE->navbar->add($template->template_name);
}


$custom = array('editoroptions' => $editoroptions, 'mode'=>$mode,'confirm'=>md5($mode));

$mform=new notify_template_form(null,$custom);

if ($mode=='add')
{
	
	if ($data=$mform->get_data())
	{
		$ins=(object)array(
			'template_name'=>$data->template_name,
			'template_text'=>'',
			'usercreated'=>$USER->id,
			'timecreated'=>time(),
			'timemodified'=>time()
		);
		$templateid=$DB->insert_record('local_notify_templates', $ins,true);
		
		$data->id=$templateid;
		$data=file_postupdate_standard_editor($data, 'template_text', $editoroptions, $context,'local_notify','template_text',$templateid);
		
		$DB->update_record('local_notify_templates', $data);
		
		redirect(new moodle_url('/local/notify/templates.php'));
	}
	else 
	{
		
	}
	
	echo $OUTPUT->header();
	
	echo $OUTPUT->heading(get_string('add_template','local_notify'));
	
	$mform->display();
	
	echo $OUTPUT->footer();
}
else if ($mode=='edit' && confirm_sesskey())
{
	
	$mform->set_data($formdata);
	
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
		
		$formdata->timemodified=time();
		if ($DB->update_record('local_notify_templates', $formdata))
		{
			$formdata=file_postupdate_standard_editor($formdata, 'template_text', $editoroptions, $context,'local_notify','template_text',$formdata->id);
			
			$DB->update_record('local_notify_templates', $formdata);
			
			redirect(new moodle_url('/local/notify/templates.php'),get_string('success'));
		}
		
		/*
		if ($DB->execute('update {local_notify_templates} set template_name=?,template_text=?,timemodified=? where id=?',array($formdata->template_name,$formdata->template_text_editor['text'],time(),$formdata->id)))
		{
			
			
			
		}
		*/
		die();
	}
}
else if ($mode=='del' && confirm_sesskey())
{
	$returnurl=new moodle_url('/local/notify/templates.php');
	if ($confirm!=md5($mode))
	{
		echo $OUTPUT->header();
		echo $OUTPUT->heading();
		
		$optionsyes = array('mode'=>$mode, 'confirm'=>md5($mode), 'sesskey'=>sesskey(),'userid'=>$USER->id,'id'=>$id);
		$confirmurl=new moodle_url('/local/notify/template_edit.php',$optionsyes);
		$deletebutton=new single_button($confirmurl, get_string('delete_template','local_notify'),'post');
		
		
		echo $OUTPUT->confirm(get_string('delete_template_text','local_notify',array('title'=>$template->template_name)),$deletebutton,$returnurl);
		
		echo $OUTPUT->footer();
			
		die();
		
		
	}
	else if (data_submitted())
	{
		//Удалить файлы, связанные с этим шаблоном
		$fs=get_file_storage();
		
		$fs->delete_area_files($context->id,'local_notify','template_text',$id);
		
		if ($DB->delete_records('local_notify_templates',array('id'=>$id)))
		{
			redirect($returnurl);
		}
	}
}




