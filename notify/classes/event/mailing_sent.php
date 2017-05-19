<?php

namespace local_notify\event;

defined('MOODLE_INTERNAL') || die();

class mailing_sent extends \core\event\base
{
	protected function init()
	{
		$this->data['crud'] = 'c';
		$this->data['edulevel'] = self::LEVEL_PARTICIPATING;
		$this->data['objecttable'] = 'local_notify_timetable';
	}
	
	public function get_description()
	{
		return "";
	}
	
	public static function get_name()
	{
		return get_string("mailingbydate_sent","local_notify");
	}
	
	public function get_url()
	{
		return new \moodle_url("/local/notify/mailingbydate.php");
	}
	
	protected function get_legacy_logdata()
	{
		$logurl = substr($this->get_url()->out_as_local_url(), strlen('/local/notify/'));
		
		return array($this->courseid, 'local_notify', 'mailingbydate_sent', $logurl, $this->other['id'], $this->contextinstanceid);
	}
	
	protected function validate_data()
	{
		parent::validate_data();
	}
}