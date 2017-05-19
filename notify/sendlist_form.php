<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');

class notify_sendlist_form extends moodleform
{
	public function definition()
	{
		$mform = $this->_form;
		
		$mform->addElement('hidden', 'mode', $this->_customdata['mode']);
		$mform->addElement('hidden','confirm',$this->_customdata['confirm']);
		$mform->addElement('hidden', 'id', 0);
		$mform->setType('id', PARAM_INT);
		
		$mform->addElement('text','sendlistname',get_string('sendlistname','local_notify'));
		$mform->addRule('sendlistname',get_string('required'),'required',null,'client');
		
		$this->add_action_buttons(true);
	}
	
	public function validation($data, $files)
	{
		return true;
	}
}