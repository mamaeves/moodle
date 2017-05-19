<?php
namespace local_notify\task;



class mailing_by_date extends \core\task\scheduled_task
{
	public function get_name()
	{
		return get_string('mailing_by_date','local_notify');
	}
	
	public function execute()
	{
		global $DB, $CFG;
		require_once($CFG->libdir.'/filelib.php');
		$context = \context_system::instance();
		
		$now=time();
		mtrace('local_notify: get unmailed tasks');
		$tasks=$DB->get_records_sql('select * from {local_notify_timetable} where sendtime<=? and done=0',array($now));
		
		foreach($tasks as $t)
		{
			$template=$DB->get_record('local_notify_templates',array('id'=>$t->template_id));
			$users=$DB->get_records_sql('select u.id,u.lastname,u.firstname from {user} as u,{local_notify_listusers} as lu where lu.listid=? and lu.userid=u.id order by u.lastname asc',array($t->sendlistid));
			
			//Сформировать сообщение для каждого user по шаблону template
			foreach($users as $u)
			{
				$message = new \core\message\message();
				
				$content=file_rewrite_pluginfile_urls($template->template_text, 'pluginfile.php', 1, 'local_notify', 'template_text', $template->id);
				
				$content=str_replace('[[lastname]]',$u->lastname,$content);
				$content=str_replace('[[firstname]]',$u->firstname,$content);
				
				$message->subject = $template->template_theme;
				$message->fullmessage=$content;
				$message->fullmessagehtml=$content;
				$message->smallmessage=$content;
				$message->userfrom=2;
				$message->userto=$u;
				$message->component = 'moodle';
				$message->name = 'instantmessage';
				$message->notification = '0';
				
				
				message_send($message);
				
				mtrace('local_notify: message sent to '.$u->lastname.' '.$u->firstname);
			}
			
			$DB->execute('update {local_notify_timetable} set done=1, timemailed=? where id=?',array($now,$t->id));
			mtrace('local_notify: task '.$t->id.' completed');
			
			$event=\local_notify\event\mailing_sent::create(array('context'=>$context,'objectid'=>$t->id));
			$event->trigger();
		}
		
		
		return true;
	}
}