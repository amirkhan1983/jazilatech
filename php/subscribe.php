<?php
	if(isset($_POST))
	{
		$errorMSG = [];
		$data1 = [];
		$fullname="";
		
		function validate_data($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			// $data = mysqli_real_escape_string($data);
			return $data;    
		}
		
		/* EMAIL */
		if (empty(validate_data($_POST["subscribe_email"]))) {
			$errorMSG['subscribe_email'] = 'Email is required.';
		} else if(!filter_var(validate_data($_POST["subscribe_email"]), FILTER_VALIDATE_EMAIL)) {
			$errorMSG['subscribe_email'] = 'Invalid email format.';
		}else {
			$email = validate_data($_POST["subscribe_email"]);
		}
		
		
		if(empty($errorMSG))
		{
			
			// $to = 'india.xpansiontechnologies@gmail.com';
			$to = 'info@pentacare.com.au';
			
			$subject = "New User Subscribed";
			
			// Email body content 
			$htmlContent = '<html><body>';
			$htmlContent .= '<h2>A new user subscribed</h2>';
			$htmlContent .= '<table width="100%" border="1"><tr><td>Email</td><td>'.$email.'</td></tr>';
			$htmlContent .= '</table></body></html>';
			
			//standard mail headers
			$header = "From: <". $email .">". "\n";
			$header .= "Reply-To: ".$email;
			
			$semi_rand = md5(time());  
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
	 
			// Headers for attachment  
			$header .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";  
	 
			// Multipart boundary  
			$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
			"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 
			
			$returnpath = "-f" . $email; 
			

				
			
			$replySubject = "Pentacare: Subscribed successfully.";
			
			$replyHeader = "From: Pentacare" ."<". $to .">". "\r\n";
			$replyHeader .= "Reply-To: ". $to . "\r\n";
			$replyHeader .= "MIME-Version: 1.0\r\n";
			$replyHeader .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$replyMessage="<h2>Thankyou for your subscription</h2>";
			$replyMessage.="<p>You will receive latest news and services mail from now.</p>";					

			if(@mail($to, $subject, $message, $header, $returnpath))
			{
				@mail($email, $replySubject, $replyMessage, $replyHeader);
				
				$data1['success'] = true;
				$data1['message'] = '<p class="myp">Thankyou for your subscription.</p>';
			}
			else
			{
				$data1['success'] = false;
				$data1['errors'] = "<h2>Error in Sending Emails</h2>";
			}				
		
		}else{
			$data1['success'] = false;
			$data1['errors'] = $errorMSG;
		}
				
		echo json_encode($data1);
	}
?>