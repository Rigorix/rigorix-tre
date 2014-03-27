<?php
require("classes/mailer/class.phpmailer.php");

$mail = new PHPMailer();

//$mail->IsSMTP();                                      // set mailer to use SMTP
//$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
//$mail->SMTPAuth = true;     // turn on SMTP authentication
//$mail->Username = "jswan";  // SMTP username
//$mail->Password = "secret"; // SMTP password

$mail->From = "paolo@cronycle.com";
$mail->FromName = "Paolo test";
$mail->AddAddress("littlebrown@gmail.com", "Paolo Moretti");
$mail->AddAddress("mrdogduo@hotmail.com", "Paolo Moretti");
$mail->AddReplyTo("littlebrown@gmail.com", "Paolo Moretti");
//
$mail->WordWrap = 50;                                 // set word wrap to 50 characters
////$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
////$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML
//
$mail->Subject = "Test Cronycle welcome mail";
$mail->Body    = file_get_contents("cronycle/welcome3_template.html");
$mail->AltBody = "This is the body in plain textfile_get_contents("cronycle/welcome3_template.html") for non-HTML mail clients";

var_dump(file_get_contents("./cronycle/mail.html"));

if(!$mail->Send())
{
 echo "Message could not be sent. <p>";
 echo "Mailer Error: " . $mail->ErrorInfo;
 exit;
}

echo "Message has been sent";
