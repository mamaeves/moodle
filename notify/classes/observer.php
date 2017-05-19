<?php

defined('MOODLE_INTERNAL') || die();

class local_notify_observer
{
	public static function course_completed(\core\event\course_completed $event)
	{
		global $DB;
		
		$message = new \core\message\message();
		
		$userfrom=$DB->get_record('user',array('id'=>2));
		
		//$content=file_rewrite_pluginfile_urls($template->template_text, 'pluginfile.php', 1, 'local_notify', 'template_text', $template->id);
		//$content=$template->template_text;
		//mtrace($content);
		
		
		$userto=$DB->get_record('user',array('id'=>$event->relateduserid));
		$course=$DB->get_record('course',array('id'=>$event->courseid));
		
		$content='Вы успешно закончили курс '.$course->fullname.'. Поздравляем! ';
		
		$message->subject = 'Поздравляем с окончанием курса '.$course->fullname;
		$message->fullmessage=$content;
		$message->fullmessagehtml=$content;
		$message->smallmessage=$content;
		$message->userfrom=$userfrom;
		$message->userto=$userto;
		$message->component = 'moodle';
		$message->name = 'instantmessage';
		$message->notification = '0';
		$message->fullmessageformat=FORMAT_HTML;
		
		$subject='Поздравляем с окончанием курса '.$course->fullname;
		//message_send($message);
		
		message_post_message($userfrom, $userto, $message->fullmessage, FORMAT_HTML);
		
		email_to_user($userto, $userfrom, $subject, $message->fullmessage);
	}
}