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
		 
		/* NAME */
		if (empty(validate_data($_POST["name"]))) {
			$errorMSG['name'] = 'Your Name is required.';
		} else {
			$name = validate_data($_POST["name"]);
		}
		
		/* EMAIL */
		if (empty(validate_data($_POST["email"]))) {
			$errorMSG['email'] = 'Email is required.';
		} else if(!filter_var(validate_data($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
			$errorMSG['email'] = 'Invalid email format.';
		}else {
			$email = validate_data($_POST["email"]);
		}
		
		/* Phone */
		if (empty(validate_data($_POST["phone"]))) {
			$errorMSG['phone'] = 'Phone Field is required';
		} else {
			$phone = validate_data($_POST["phone"]);
		}
		
		/* Services */
		if (empty(validate_data($_POST["services"]))) {
			$errorMSG['services'] = 'Please Select Service';
		} else {
			$services = validate_data($_POST["services"]);
		}
			
		/* Appointment Date */
		if (empty(validate_data($_POST["appointment_date"]))) {
			$errorMSG['appointment_date'] = 'Please Appointment Date';
		} else {
			$appointment_date = validate_data($_POST["appointment_date"]);
		}
		
		/* Condition Check */
		if (!isset($_POST["condition"])) {
			$errorMSG['condition'] = 'Please dowload form and accept checkbox';
		} else {
			$condition = "Accpted";
		}
		
		
		
		$form_message=validate_data($_REQUEST['message']);
				
		
		if(empty($errorMSG))
		{
			
			// $to = 'india.xpansiontechnologies@gmail.com';
			$to = 'info@pentacare.com.au';
			
			$subject = "New Appointment Query";
			
			// Email body content 
			$htmlContent = '<html><body>';
			$htmlContent .= '<h2>New Query Received From Contact Page</h2>';
			$htmlContent .= '<table width="100%" border="1"><tr><td>Full Name</td><td>'.$name.'</td></tr>';
			$htmlContent .= '<tr><td>Email</td><td>'.$email.'</td></tr>';
			$htmlContent .= '<tr><td>Phone</td><td>'.$phone.'</td></tr>';
			$htmlContent .= '<tr><td>Services</td><td>'.$services.'</td></tr>';
			$htmlContent .= '<tr><td>Appointment Date</td><td>'.$appointment_date.'</td></tr>';
			$htmlContent .= '<tr><td>Message</td><td>'.$form_message.'</td></tr>';
			$htmlContent .= '</table></body></html>';
			
			//standard mail headers
			$header = "From: " .$name."<". $email .">". "\n";
			$header .= "Reply-To: ".$email;
			
			$semi_rand = md5(time());  
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
	 
			// Headers for attachment  
			$header .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";  
	 
			// Multipart boundary  
			$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
			"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 
			
			$returnpath = "-f" . $email; 
			

				
			
			$replySubject = "Pentacare: Your Appointment Query Received Successfully.";
			
			$replyHeader = "From: Pentacare" ."<". $to .">". "\r\n";
			$replyHeader .= "Reply-To: ". $to . "\r\n";
			$replyHeader .= "MIME-Version: 1.0\r\n";
			$replyHeader .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$replyMessage="<h2>Your Query Received Successfully</h2>";
			$replyMessage.="<p>One of our team member will contact you soon.</p>";					

			if(@mail($to, $subject, $message, $header, $returnpath))
			{
				@mail($email, $replySubject, $replyMessage, $replyHeader);
				
				$data1['success'] = true;
				$data1['message'] = '<h2>Enquiry Submitted Successfully.</h2><p class="myp">Our Co-ordinators will get in touch with you soon.</p>';
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