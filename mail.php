<?php 
ob_start();
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
if(isset($_POST['submit'])) {    
	$email = $_POST['email'];    
	$subject = $_POST['subject'];    
	$message = $_POST['message'];    
	$mail = new PHPMailer(true);  // Passing `true` enables exceptions    
	try {        
		//Server settings        
		$mail->Host       = "p3plcpnl0719.prod.phx3.secureserver.net";        
		$mail->Port       = 25;        
		$mail->SMTPDebug  = 0;        
		$mail->SMTPSecure = "none";        
		$mail->SMTPAuth   = false;        
		$mail->Username   = "";        
		$mail->Password   = "";        
		//Recipients        
		$mail->setFrom("contactform@jhouwebdev.com", 'Jhouwebdev');        
		$mail->addAddress('jhouston2882@gmail.com', 'Jarrell'); 
		// Name is optional        
		//Content       
		 $mail->isHTML(true); 
		 // Set email format to HTML        
		 $mail->Subject = $subject;        
		 $mail->Body = $email . "<br><br>" .$message;        
		 $mail->send();       
		 $_SESSION['email'] = "Message has been sent!";    
	 } catch (Exception $e) {        
		$_SESSION['email'] = "Message Failed";    
 }    
 
 header('location:/portfolio/#contact');}?>