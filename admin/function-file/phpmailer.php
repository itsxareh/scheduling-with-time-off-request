<?php
require "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start();
ini_set('display_errors', 1);

Class Actions {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
    function save_schedule(){
		extract($_POST);
		$title = mysqli_real_escape_string($this->db, $title);
		$description = mysqli_real_escape_string($this->db, $description);
		$id_no = implode(",", $id_no);
		$data = " id_no = '$id_no' ";
		$data .= ", title = '$title' ";
		$data .= ", schedule_type = '$schedule_type' ";
		$data .= ", description = '$description' ";
		$data .= ", station = '$station' ";
		if(isset($is_repeating)){
			$month_from_date = $month_from . "-01";
			$month_to_date = date("Y-m-t", strtotime($month_to . "-01"));
			$data .= ", is_repeating = '$is_repeating' ";
			$data .= ", schedule_date = '$month_from_date' ";
			$data .= ", schedule_end = '$month_to_date' ";
			$rdata = array('dow'=>implode(',', $dow),'start'=>$month_from_date,'end'=>$month_to_date);
			$data .= ", repeating_data = '".json_encode($rdata)."' ";
		}else{
			$data .= ", is_repeating = 0 ";
			$data .= ", schedule_date = '$schedule_date' ";
			$data .= ", schedule_end = '$schedule_end' ";
		}
		$data .= ", time_from = '$time_from' ";
		$data .= ", time_to = '$time_to' ";
		
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->SMTPAuth = true; 
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPSecure = 'tls';
		$mail->Username = 'schedulemcdomanager@gmail.com';
		$mail->Password = 'kayfeoowutyacepb';
		$mail->Port = 587;
		$mail->setFrom('schedulemcdomanager@gmail.com');
		$mail->Subject = "$schedule_type Schedule";
		$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no IN ($id_no)");
		$emails = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$emails[] = ['email' => $row['email'], 'name' => $row['name']];
		}
		foreach ($emails as $email) {
		  $mail->addAddress($email['email']);
		  $mail->Body = "<h2>Hello, ".$email['name']."!</h2> <p>Check out now your schedule!</p>";
		  $mail->AltBody = "Hello, ".$email['name']."! \n Check out now your schedule!";
		  try {
			  $mail->send();
			  echo "Message sent to: ({$email['email']}) {$mail->ErrorInfo}\n";
		  } catch (Exception $e) {
			  echo "Mailer Error ({$email['email']}) {$mail->ErrorInfo}\n";
		  }
		  $mail->clearAddresses();
		}
		$mail->smtpClose();

		if (empty($id)) {
			$query = "INSERT INTO `schedules` SET ".$data;
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				/* store first result set */
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		  } elseif (empty($id) && empty($id_no)) {
			$query = "INSERT INTO `schedules` SET ".$data;
			$query .= "; UPDATE staff SET notification = 'schedule' WHERE TRUE";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				/* store first result set */
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		  } elseif (empty($id_no)){
			$query = "UPDATE `schedules` SET ".$data." WHERE id=".$id;
			$query .= "; UPDATE staff SET notification = 'schedule' WHERE TRUE";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				/* store first result set */
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		  } else {
			$query = "UPDATE `schedules` SET ".$data." WHERE id=".$id;
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				/* store first result set */
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		  }
	  }
}