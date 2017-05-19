<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');

class notify_mailingbydate_form extends moodleform
{
	public function definition()
	{
		$mform = $this->_form;
		
		$templates = $this->_customdata['templates_ar'];
		$sendlists=$this->_customdata['sendlists_ar'];
		
		$mform->addElement('hidden','id',$this->_customdata['id']);
		$mform->addElement('hidden','mode',$this->_customdata['mode']);
		$mform->addElement('hidden','confirm',$this->_customdata['confirm']);
		
		$mform->addElement('select','template_id',get_string('template_name','local_notify'),$templates);
		
		$mform->addElement('date_time_selector','sendtime',get_string('sendtime','local_notify'));
		
		$mform->addElement('select','sendlistid',get_string('sendlist','local_notify'),$sendlists);
		
		$this->add_action_buttons(true);
	}
	
	public function validation()
	{
		return true;
	}
}