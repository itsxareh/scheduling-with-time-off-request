<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
ini_set('display_errors', 1);

Class Action {
	private $db;

	public function __construct() {
		ob_start();
		include 'db_connect.php';
		$this->includes();
		$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
	private function includes(){
        require_once '../../vendor/phpmailer/phpmailer/src/Exception.php';
        require_once '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require_once '../../vendor/phpmailer/phpmailer/src/SMTP.php';
	}
	function login(){
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_type'] != 1 & 2){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 0;
				exit;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function login_staff(){
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id_no = '".$id_no."' ");
		if($qry->num_rows > 0){
			$mail = new PHPMailer(true);
			try {
				$rand = mt_rand(1000000,9999999);
				$rand = sprintf("%'07d",$rand);
				$data = " authcode = '$rand'";
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'schedulemcdomanager@gmail.com';
				$mail->Password = 'kayfeoowutyacepb';
				$mail->Port = 587;
				$mail->setFrom('schedulemcdomanager@gmail.com');
				$mail->Subject = "One-Time Password (OTP)";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no = '".$id_no."' and email LIKE '%@gmail.com'");
				if(mysqli_num_rows($result) > 0) {
					$emails = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$emails[] = ['email' => $row['email'], 'name' => $row['name']];
					}
					foreach ($emails as $email) {
					$mail->addAddress($email['email']);
					$mail->IsHTML(true);
					$mail->Body =
					"<html>
					<body>
						<div class='container' style='width: 100%'>
							<table align='center' style='width: 500px'>
								<tbody>
									<hr>
										<tbody align='center'>
										<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
											<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
										</tr>
										</tbody>
										<tr style='text-align:center;'>
											<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
										</tr>
										<tr style='text-align:center;'>
											<td>Use this code to log in your account.</td><br/>
										</tr>
											<br/>
										<tr style='text-align:center;'>
											<td><h2>Your Verification Code is ".$rand.".</h2></td>
										</tr>
										<tr style='text-align:center;'>
										<td style='color: gray'>You received this email because you requested to log in. If you didn't request to log in, you can safely ignore this email.</td>
									</tr>
									<hr>
								</tbody>
							</table>
						</div>
					</body>
					</html>";
					$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo', '');
					$mail->send();
					$mail->clearAddresses();
					}
					$mail->smtpClose();
				  $auth = $this->db->query("UPDATE staff set $data where id_no = ".$id_no);
				  if(mysqli_affected_rows($this->db) > 0) {
					return 1;
				  } else {
					return 3;
				  }
				} else {
				  return 2;
				}
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
			
		}else{
			return 3;
		}
		return 0;
	}
	function auth_staff(){
		extract($_POST);    
		$authcode = $_POST['authcode'];
		$qry2 = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id_no = '".$id_no."' ");
		if($qry2->num_rows > 0){
			$qry = $this->db->query("SELECT authcode,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id_no = '".$id_no."' ");
			if($qry->num_rows > 0){
				$row1 = mysqli_fetch_assoc($qry);
				$auth = $row1['authcode'];
				if ($authcode == $auth){
					foreach ($qry2->fetch_array() as $key => $value) {
						if($key != 'password' && !is_numeric($key))
							$_SESSION['login_'.$key] = $value;
					}
					return 1;
				}else{
					return 2;
				}
			}else{
				return 3;
			}	
		}else{
			return 3;
		}
	}
	function login2(){
		extract($_POST);
		if(isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
		if($_SESSION['login_alumnus_id'] > 0){
			$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
			if($bio->num_rows > 0){
				foreach ($bio->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['bio'][$key] = $value;
				}
			}
		}
		if($_SESSION['bio']['status'] != 1){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2 ;
				exit;
			}
			return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../login.php");
	}
	function logout2(){
		date_default_timezone_set('Asia/Manila');
    	$time=date('Y-m-d G:i:s ', strtotime("now"));
		$id_no = $_SESSION['login_id_no'];
		$data = " last_login = '$time'";
		$save = $this->db->query("UPDATE staff set ".$data." where id_no = ".$id_no);
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../../user-panel/index.php");
	}
	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";
		}
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}
			return 1;
		}
	}
	function save_staff(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k=> $v){
			if(!empty($v)){
				if($k !='id'){
					if(empty($data))
					$data .= " $k='{$v}' ";
					else
					$data .= ", $k='{$v}' ";
				}
			}
		}
		if(empty($id_no)){
			$i = 1;
			while($i == 1){
				$rand = mt_rand(1,99999999);
				$rand =sprintf("%'08d",$rand);
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$rand' ")->num_rows;
				if($chk <= 0){
					$data .= ", id_no='$rand' ";
					$i = 0;
				}
			}
		}
		if(empty($id)){
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$id_no' ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO staff set $data ");
		}else{
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$id_no' and id != $id ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("UPDATE staff set $data where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_staff(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM staff where id = ".$id);
		if($delete){
			return 1;
		}
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

		if (empty($id) && $id_no == 0) {
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'schedulemcdomanager@gmail.com';
				$mail->Password = 'kayfeoowutyacepb';
				$mail->Port = 587;
				$mail->setFrom('schedulemcdomanager@gmail.com');
				$mail->Subject = "For $schedule_type Schedule";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE email LIKE '%@gmail.com'");
				$emails = [];
				while ($row = mysqli_fetch_assoc($result)) {
					$emails[] = ['email' => $row['email'], 'name' => $row['name']];
				}
				foreach ($emails as $email) {
				$mail->addAddress($email['email']);
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
									</tr>
									</tbody>
									<tr style='text-align:center;'>
										<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Manager added a new schedule for everyone.</td><br/>
									</tr>
										<br/>
									<tr style='text-align:center;'>
										<td><h2>Check now the schedule for ". ($schedule_date ? $schedule_date : "" ) .".</h2></td>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Don't forget to stamp your attendance!</td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo');
				$mail->send();
				$mail->clearAddresses();
				}
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
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
	  	}elseif (empty($id)) {
			$mail = new PHPMailer(true);
			try{
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'schedulemcdomanager@gmail.com';
				$mail->Password = 'kayfeoowutyacepb';
				$mail->Port = 587;
				$mail->setFrom('schedulemcdomanager@gmail.com');
				$mail->Subject = "$schedule_type Schedule";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no in ($id_no) and email LIKE '%@gmail.com'");
				$emails = [];
				while ($row = mysqli_fetch_assoc($result)) {
					$emails[] = ['email' => $row['email'], 'name' => $row['name']];
				}
				foreach ($emails as $email) {
				$mail->addAddress($email['email']);
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
									</tr>
									</tbody>
									<tr style='text-align:center;'>
										<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Manager added a new schedule for you.</td><br/>
									</tr>
										<br/>
									<tr style='text-align:center;'>
										<td><h2>Check now your schedule for ". ($schedule_date == "" ? $month_from_date : $schedule_date) .".</h2></td>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Don't forget to stamp your attendance!</td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo');
				$mail->send();
				$mail->clearAddresses();
				}
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
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
	function delete_schedule(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM schedules where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_schedule(){
		extract($_POST);
		$data = array();
		if ($_POST['id_no'] === 'all'){
			$qry = $this->db->query("SELECT * FROM schedules");
		} else 
		$qry = $this->db->query("SELECT * FROM schedules WHERE id_no = 0 OR id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0");
		while($row=$qry->fetch_assoc()){
			if($row['is_repeating'] == 1){
				$rdata = json_decode($row['repeating_data']);
				foreach($rdata as $k =>$v){
					$row[$k] = $v;
				}
			}
			$data[] = $row;
		}
			return json_encode($data);
	}
	function save_time_off(){
		extract($_POST);
		$id_no = "{$_SESSION['login_id_no']}";
		$data = " id_no = '$id_no'";
		$description = mysqli_real_escape_string($this->db, $description);
		$data .= ", leave_type = '$leave_type' ";
		$data .= ", from_date = '$from_date' ";
		$data .= ", to_date = '$to_date' ";
		$data .= ", description = '$description' ";

		if (empty($id)){
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'schedulemcdomanager@gmail.com';
				$mail->Password = 'kayfeoowutyacepb';
				$mail->Port = 587;
				$result = $this->db->query("SELECT *, concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no = '$id_no'");
				$row = $result->fetch_assoc();
				$email = $row['email'];
				$mail->setFrom($email);
				$mail->Subject = "New Time-Off Request";
				$mail->addAddress('schedulemcdomanager@gmail.com');
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
									</tr>
									</tbody>
									<tr style='text-align:center;'>
										<td>Hi, <strong>Manager</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='font-size: 14px;'><p><b>".$row['name']."</b> requested a time-off from <b>".$from_date."</b> to <b>".$to_date."</b>.</p></td>
									</tr>
									<br/>
									<tr style='text-align:center;'>
										<td><h3>".$leave_type."</h3></td>
									</tr>
									<tr style='text-align:center;'>
										<td><h2>".$description."</h2></td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo');
				if (!$mail->send()) {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo 'Message has been sent';
				}
				$mail->clearAddresses();
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
			$save = $this->db->query("INSERT INTO `time-off-request` set ".$data);
		} else {
			$save = $this->db->query("UPDATE `time-off-request` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function decide_time_off(){
		date_default_timezone_set('Asia/Manila');
    	$time_remark=date('Y-m-d G:i:s ', strtotime("now"));
		extract($_POST);
		$admin_remark = mysqli_real_escape_string($this->db, $admin_remark);
		$data = " notifications = 'request1' ";
		$data .= ", admin_remark	 = '$admin_remark' ";
		$data .= ", stats = '$stats' ";	
		$data .= ", time_remark = '$time_remark' ";
		
		if (empty($id)){
			$save = $this->db->query("INSERT INTO `time-off-request` set ".$data);
		} else {
			$mail = new PHPMailer(true);
				try {
					$mail->isSMTP();
					$mail->SMTPAuth = true; 
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPSecure = 'tls';
					$mail->Username = 'schedulemcdomanager@gmail.com';
					$mail->Password = 'kayfeoowutyacepb';
					$mail->Port = 587;
					$mail->setFrom('schedulemcdomanager@gmail.com');
					$mail->Subject = "Time-Off Request";
					$result = $this->db->query("SELECT t.*, concat(lastname,', ',firstname,' ',middlename) as name, email FROM staff s INNER JOIN `time-off-request` t ON t.id_no = s.id_no WHERE t.id = $id and s.email LIKE '%@gmail.com' ");
					$emails = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$emails[] = ['email' => $row['email'], 'name' => $row['name'], 'stats' => $row['stats']];
					}
					foreach ($emails as $email) {
					$mail->addAddress($email['email']);
					$mail->IsHTML(true);
					$mail->Body =
					"<html>
					<body>
						<div class='container' style='width: 100%'>
							<table align='center' style='width: 500px'>
								<tbody>
									<hr>
										<tbody align='center'>
										<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
											<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
										</tr>
										</tbody>
										<tr style='text-align:center;'>
											<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
										</tr>
										<tr style='text-align:center;'>
											<td style='color: gray'>Your time-off request status has been ".$stats.".</td><br/>
										</tr>
											<br/>
										<tr style='text-align:center;'>
											<td><h2>".$admin_remark."</h2></td>
										</tr>
									<hr>
								</tbody>
							</table>
						</div>
					</body>
					</html>";
					$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo');
					$mail->send();
					$mail->clearAddresses();
					}
					$mail->smtpClose();
				} catch (Exception $e){
					echo "Mailer Error: {$mail->ErrorInfo}";
				}
			$save = $this->db->query("UPDATE `time-off-request` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_time_off(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM `time-off-request` where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_leave_type(){
		extract($_POST);
		$leave_type = mysqli_real_escape_string($this->db, $leave_type);
		$description = mysqli_real_escape_string($this->db, $description);
		$data = " leave_type = '$leave_type' ";
		$data .= ", description = '$description' ";
		if (empty($id)){
			$save = $this->db->query("INSERT INTO `leave_types` set ".$data);
		} else {
			$save = $this->db->query("UPDATE `leave_types` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_leave_type(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM `leave_types` where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_announcement() {
		date_default_timezone_set('Asia/Manila');
    	$date_created = date('Y-m-d G:i:s ', strtotime("now"));
		extract($_POST);
		$title = mysqli_real_escape_string($this->db, $title);
		$description = mysqli_real_escape_string($this->db, $description);
		$author = mysqli_real_escape_string($this->db, $author);
		$data = " title = '$title' ";
		$data .= ", description = '$description' ";
		$data .= ", author = '$author' ";
		$data .= ", date_created = '$date_created' ";
		if (empty($id)) {
		  $query = "INSERT INTO `announcement` SET ".$data;
		  $query .= "; UPDATE staff SET notifications = 'announcement' WHERE TRUE";
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
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'schedulemcdomanager@gmail.com';
				$mail->Password = 'kayfeoowutyacepb';
				$mail->Port = 587;
				$mail->setFrom('schedulemcdomanager@gmail.com');
				$mail->IsHTML(true);
				$mail->Subject = "New Announcement";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE email LIKE '%@gmail.com'");
				$emails = [];
				while ($row = mysqli_fetch_assoc($result)) {
					$emails[] = ['email' => $row['email'], 'name' => $row['name']];
				}
				foreach ($emails as $email) {
				$mail->addAddress($email['email']);
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; background-color: #b8b8b8; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Staff Scheduling System<span></td>
									</tr>
									</tbody>
									<tr style='text-align:center;'>
										<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Manager has an announcement for everyone.</td><br/>
									</tr>
										<br/>
									<tr style='text-align:center;'>
										<td><h2>".$title."</h2></td>
									</tr>
									<tr style='text-align:center;'>
										<td><h4>".$description."</h4></td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
			</html>";
				$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo');
				$mail->send();
				$mail->clearAddresses();
				}
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
		  $query = "UPDATE `announcement` SET ".$data." WHERE id=".$id;
		  $query .= "; UPDATE staff SET notifications = 'announcement' WHERE TRUE";
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
	function delete_forum(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";
		$user_id = !empty($_SESSION['login_id_no']) ? $_SESSION['login_id_no'] : $_SESSION['login_id'];
		if (empty($_SESSION['login_id_no'])){
			$qry = $this->db->query("UPDATE staff set notificationa = 'comment' WHERE true");
		}
		if(empty($id)){
			$data .= ", user_id = '$user_id' ";
			$data .= ", notifications = 'comment'";
			$save = $this->db->query("INSERT INTO comments set ".$data);
		}else{
			$save = $this->db->query("UPDATE comments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("UPDATE comments set delete_flag = 1");
		if($delete){
			return 1;
		}
	}
	function get_latest_comments(){
		$current_user_id = !empty($_SESSION['login_id_no']) ? $_SESSION['login_id_no'] : $_SESSION['login_id'];
		$comments = $this->db->query("SELECT c.*, u.name as user_name, s.firstname as staff_name FROM comments c LEFT JOIN users u ON c.user_id = u.id LEFT JOIN staff s ON c.user_id = s.id_no WHERE delete_flag = 0 order by date_created asc");
		
		$output = "";
		if ($comments->num_rows > 0) {
			foreach ($comments as $comment) {
				$class = $comment['user_id'] == $current_user_id ? 'outgoing' : 'incoming';
				$output .= '<div class="chat ' . $class . '">';
				$output .= '<div class="user">';
				$output .= '<i class="user">' . ($class == 'outgoing' ? 'Me' : ($comment['user_name'] ? $comment['user_name'] : $comment['staff_name'])) . ': ' . date("M d, Y h:i A", strtotime($comment['date_created'])) . '</i>';
				$output .= '</div>';
				$output .= '<div class="details">';
				$output .= '<p id="id" data-id='.$comment['id'].'>' . $comment['comment'] . '</p>';
				$output .= '</div>';
				$output .= '</div>';
			}
		} else {
			$output .= '<div class="text-center"><i>No comments</i></div>';
		}
	
		echo $output;
		exit;
	}
	function save_event(){
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '".htmlentities(str_replace("'","&#x2019;",$content))."' ";
		if($_FILES['banner']['tmp_name'] != ''){
						$_FILES['banner']['name'] = str_replace(array("(",")"," "), '', $_FILES['banner']['name']);
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['banner']['name'];
						$move = move_uploaded_file($_FILES['banner']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", banner = '$fname' ";

		}
		if(empty($id)){

			$save = $this->db->query("INSERT INTO events set ".$data);
		}else{
			$save = $this->db->query("UPDATE events set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_event(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function participate(){
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if($commit)
			return 1;

	}
}
