<?php
	require 'vendor/autoload.php';

	$transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
	  ->setUsername('d207d6955b2b9f')
	  ->setPassword('80224a2b4df73d')
	;

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	// Create a message
	$message = (new Swift_Message('Wonderful Subject'))
	  ->setSubject($subject)
	  ->setFrom([$from_email => $from_name])
	  ->setTo([$to_email => $to_name])
	  ->setBody($comment)
	  ;

	// Send the message
	$result = $mailer->send($message);


// TransportFactory::setConfig('mailtrap', [
//   'host' => 'smtp.mailtrap.io',
//   'port' => 2525,
//   'username' => 'd207d6955b2b9f',
//   'password' => '80224a2b4df73d',
//   'className' => 'Smtp'
// ]);

// $to = "zhuyange2018@gmail.com";
// $subject = "Test mail";
// $message = "Hello! This is a simple email message.";
// $from = "70858315@qq.com";
// $headers = "From: $from";
// mail($to,$subject,$message,$headers);
// echo "Mail Sent.";
?>