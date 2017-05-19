<?php
namespace local_notify\task;

class birthday_mail extends \core\task\scheduled_task
{
	public function get_name()
	{
		return get_string('birthday_mail','local_notify');
	}
	
	public function execute()
	{
		global $DB, $CFG;
		
		mtrace('local_notify: birthday_mail');
		
		//$today=time();
		$today=new \DateTime("now",\core_date::get_server_timezone_object());
		//$di=new \DateInterval('PT4H');
		$di=new \DateInterval('P1D');
		
		$today->sub($di);
		
		$day=$today->format('j');
		$month=$today->format('n');
		
		mtrace('day='.$day.', month='.$month);
		
		$users=$DB->get_records_sql('select u.id,u.lastname,u.firstname,d.data as data from {user} as u, {user_info_field} as f, {user_info_data} as d where f.id=d.fieldid and f.shortname=? and d.userid=u.id and day(from_unixtime(d.data))=? and month(from_unixtime(d.data))=?',array('birthdate',$day,$month));
		
		$userfrom=$DB->get_record('user',array('id'=>2));
		$subject='Поздравляем с днем рождения!';
		
		foreach($users as $u)
		{
			mtrace($u->lastname.' '.$u->firstname.' ');
			$content='Здравствуйте, '.$u->firstname.'!';
			$content.='Поздравляем Вас с днем рождения!';
			
			//message_post_message($userfrom, $u, $content, FORMAT_HTML);
			
			if (email_to_user($u, $userfrom, $subject, $content))
			{
				mtrace('emailed');
			}
			else 
			{
				mtrace('not emailed');
			}
		}
		
		return true;
	}
}