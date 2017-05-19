<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');


class notify_template_form extends moodleform
{
	public function definition()
	{
		$mform = $this->_form;
		
		$mform->addElement('hidden', 'mode', $this->_customdata['mode']);
		$mform->addElement('hidden','confirm',$this->_customdata['confirm']);
		$mform->addElement('hidden', 'id', 0);
		$mform->setType('id', PARAM_INT);
		
		$editoroptions = $this->_customdata['editoroptions'];
		
		
		$mform->addElement('text','template_name',get_string('template_name','local_notify'));
		$mform->addRule('template_name',get_string('required'),'required',null,'client');
		
		$mform->addElement('text','template_theme',get_string('template_theme','local_notify'));
		$mform->addRule('template_theme',get_string('required'),'required',null,'client');
		
		$mform->addElement('editor','template_text_editor',get_string('template_text','local_notify'),null,$editoroptions);
		$mform->addRule('template_text_editor', get_string('required'), 'required', null, 'client');
		
		$this->add_action_buttons(true);
		
		
		
	}
	
	public function validation($data, $files)
	{
		return true;
	}
}