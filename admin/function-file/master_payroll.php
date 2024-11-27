<?php
require_once('config.php');	
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
ini_set('display_errors', 1);
Class Master {
	private $conn;

	public function __construct(){
		ob_start();
		include 'db_connect.php';
		$this->includes();
		$this->includes1();
		$this->conn = $conn;
	}
	public function __destruct(){
		$this->conn->close();
	    ob_end_flush();
	}
	private function includes(){
        require_once '../../vendor/phpmailer/phpmailer/src/Exception.php';
        require_once '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require_once '../../vendor/phpmailer/phpmailer/src/SMTP.php';
	}
	private function includes1(){
		require_once '../../vendor/autoload.php';
	}
	function set_flashdata($flash='',$value=''){
		if(!empty($flash) && !empty($value)){
			$_SESSION['flashdata'][$flash]= $value;
		return true;
		}
	}
	function chk_flashdata($flash = ''){
		if(isset($_SESSION['flashdata'][$flash])){
			return true;
		}else{
			return false;
		}
	}
function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
}    
function save_payroll(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($id_no)){
			$check = $this->conn->query("SELECT * FROM `payroll` where `code` = '{$code}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
			if($this->capture_err())
				return $this->capture_err();
			if($check > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = " Payroll Code already exists.";
				return json_encode($resp);
				exit;
			}
		}
		
		if(empty($id)){
			$sql = "INSERT INTO `payroll` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `payroll` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			$cid = empty($id) ? $this->conn->insert_id : $id;
			$resp['id'] = $cid ;
			if(empty($id))
				$resp['msg'] = " New Payroll successfully saved.";
			else
				$resp['msg'] = " Payroll successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if(isset($resp['msg']) && $resp['status'] == 'success'){
			$this->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
}
	function delete_payroll(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `payroll` set `delete_flag` = 1  where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->set_flashdata('success'," Payroll successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function sendEmail($id_no, $pdf){
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
			$mail->Subject = "Payslip";
			$result = $this->conn->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no = '$id_no' and email LIKE '%@gmail.com'");
			$emails = [];
			while ($row = mysqli_fetch_assoc($result)) {
				$emails[] = ['email' => $row['email'], 'name' => $row['name']];
			}
			foreach ($emails as $email) {
			$mail->addAddress($email['email']);
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
										<td style='color: gray'>Manager attached your payslip.</td><br/>
									</tr>
										<br/>
										<br/>
									<tr style='text-align:center;'>
										<td>You can now view and print your payslip.</td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
			</html>";
			$mail->addEmbeddedImage('../images/mcdologo-nobg.png', 'logo', '');
			$mail->addStringAttachment($pdf, "Payslip.pdf");
			$mail->send();
			$mail->clearAddresses();
			}
			$mail->smtpClose();
		} catch (Exception $e){
			echo "Mailer Error: {$mail->ErrorInfo}";
		}
	}
	function save_payslip(){
		extract($_POST);
		$result1 = $this->conn->query("SELECT *, concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no = '$id_no'");
		$row1 = $result1->fetch_assoc();
		$name = $row1['name'];
		$payslip = "<center><h1>Payslip</h1></center>";
		$payslip .= "<hr>";
		$payslip .= "ID No: ".$id_no."<br>";
		$payslip .= "Name: ".$name."<br>";
		$payslip .= "<hr>";
		$payslip .= "<br>";
		$payslip .= "Payroll ID: ".$payroll_id."<br>";
		$payslip .= "<hr>";
		$payslip .= "Rate: ₱".$rate."<br>";
		$payslip .= "Total Time of Present(minutes): ".$minutes_present."<br>";
		$payslip .= " Day/s of Present: ".$days_present."<br>";
		$payslip .= " Day/s of Absent: ".$days_absent."<br>";
		$payslip .= " Late/Undertime(minutes): ".$tardy_undertime."<br>";
		$payslip .= " Total Allowance: ₱".$total_allowance."<br>";
		$payslip .= " Total Deduction: ₱".$total_deduction."<br>";
		$payslip .= " Withholding Tax: ₱".$withholding_tax."<br>";
		$payslip .= "<hr>";
		$payslip .= " Net: ₱".$net."<br>";
		$payslip .= "<hr>";
		
		$data = " payroll_id = '$payroll_id'";
		$data .= ", id_no = '$id_no'";
		$data .= ", minutes_present = '$minutes_present'";
		$data .= ", days_present = '$days_present'";
		$data .= ", days_absent = '$days_absent'";
		$data .= ", tardy_undertime = '$tardy_undertime'";
		$data .= ", total_allowance = '$total_allowance'";
		$data .= ", total_deduction = '$total_deduction'";
		$data .= ", rate = '$rate'";
		$data .= ", withholding_tax = '$withholding_tax'";
		$data .= ", net = '$net'";

		if(isset($id_no)){
			$check = $this->conn->query("SELECT * FROM `payslip` where `id_no` = '{$id_no}' and `payroll_id` = '{$payroll_id}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
			if($this->capture_err())
				return $this->capture_err();
			if($check > 0){
				$resp['status'] = 'failed';
				$resp['msg'] = " Staff already have a Payslip for this Payroll.";
				return $resp['msg'];
				exit;
			}
		}
		
		if(empty($id)){
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($payslip);
			$pdf = $mpdf->output("", "S");
			$this->sendEmail($id_no, $pdf);
			$sql = "INSERT INTO `payslip` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($payslip);
			$pdf = $mpdf->output("", "S");
			$this->sendEmail($id_no, $pdf);
			$sql = "UPDATE `payslip` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			$pid = empty($id) ? $this->conn->insert_id : $id;
			$resp['id'] = $pid ;
			if(empty($id))
				$resp['msg'] = " New Payslip successfully saved.";
			else
				$resp['msg'] = " Payslip successfully updated.";

			$data = "";
			if(isset($allowance)){
				foreach($allowance as $k=>$v){
					$name = $this->conn->real_escape_string($v);
					$amount = $this->conn->real_escape_string($allowance_amount[$k]);
					if(!empty($data)) $data .= ", ";
					$data .= "('{$pid}','{$name}','{$amount}')";
				}
				if(!empty($data)){
					$this->conn->query("DELETE FROM `allowance_list` where payslip_id = '{$pid}'");
					$sql2 = "INSERT INTO `allowance_list` (`payslip_id`, `name`, `amount`) VALUES {$data}";
					if(!$this->conn->query($sql2)){
						$resp['status'] = 'failed';
						$resp['error'] = $this->conn->error;
						$resp['msg'] = "Data has failed to save.";
						if(empty($id)){
							$this->conn->query("DELETE FROM `payslip` where id = '{$pid}'");
						}
						return json_encode($resp);
					}
				}
			}
			$data = "";
			if(isset($deduction)){
			foreach($deduction as $k=>$v){
				$name = $this->conn->real_escape_string($v);
				$amount = $this->conn->real_escape_string($deduction_amount[$k]);
				if(!empty($data)) $data .= ", ";
				$data .= "('{$pid}','{$name}','{$amount}')";
			}
			if(!empty($data)){
				$this->conn->query("DELETE FROM `deduction_list` where payslip_id = '{$pid}'");
				$sql2 = "INSERT INTO `deduction_list` (`payslip_id`, `name`, `amount`) VALUES {$data}";
				if(!$this->conn->query($sql2)){
					$resp['status'] = 'failed';
					$resp['error'] = $this->conn->error;
					$resp['msg'] = "Data has failed to save.";
					if(empty($id)){
						$this->conn->query("DELETE FROM `payslip` where id = '{$pid}'");
					}
					return json_encode($resp);
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if(isset($resp['msg']) && $resp['status'] == 'success'){
			$this->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
}
	function delete_payslip(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `payslip` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->set_flashdata('success'," Payslip successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function get_payslip(){
		extract($_POST);
		$data = array();
		$qry = $this->db->query("SELECT * FROM payslip where id_no = 0 or id_no = $id_no");
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
	function generate_payslip($payslip_id, $save_file = false){
		ob_start();
		$qry = $this->conn->query("SELECT pp.*, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname from payslip pp inner join `staff` e on pp.id_no = e.id_no where pp.id = '{$payslip_id}' ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_assoc() as $k => $v){
				$$k=$v;
			}
			if(isset($payroll_id)){
				$payroll = $this->conn->query("SELECT * FROM payroll where id = '{$payroll_id}'");
				if($payroll->num_rows > 0){
					foreach($payroll->fetch_array() as $k=> $v){
						if(!is_numeric($k))
							$_payroll[$k] = $v;
					}
				}
			}
		}
		
		include 'generate_pdf_payslip.php';
		ob_end_flush();

	}

	function payroll_generate_payslips(){
		extract($_POST);
		$payslips = $this->conn->query("SELECT * FROM `payslip` where payroll_id = '{$id}' ");
		while($row = $payslips->fetch_assoc()){
			$genarated = $this->generate_payslip($row['id'],true);
		}
		$this->set_flashdata('success', "Payslips has been generated successfully.");
		return json_encode(['status'=>'success']);
	}
	function payroll_generate_payslips_single(){
		extract($_POST);
		$genarated = $this->generate_payslip($id,true);
		$this->set_flashdata('success', "Payslip has been generated successfully.");
		return json_encode(['status'=>'success']);
	}

	function send_email_pdf_payslip($payslip_id){
		$payslip = $this->conn->query("SELECT p.*, CONCAT(e.last_name, ', ' , e.first_name,' ', COALESCE(e.middle_name,'')) as fullname,e.email,pp.code, pp.start_date, pp.end_date from `payslip` p inner join staff e on p.id_no = e.id_no inner join payroll pp on p.payroll_id = pp.id where p.id = '{$payslip_id}' ");
		if($payslip->num_rows > 0 ){
			foreach($payslip->fetch_array() as $k => $v){
				if(!is_numeric($k))
				$$k = $v;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Unknown Payslip';
			return json_encode($resp);
		}
		$file = $file_path;

		$mailto = $email;
		$subject = 'Payslip - '.$code;
		$message = '<html>
			<p><b>Dear Mr/Ms/Mrs. '.$fullname.',</b> <br/><br/>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Good Day/Evening! Your Payslip for <b>'.$code.' Payroll</b> is attached on this email. The payroll starts from <b>'.(date("M d, Y", strtotime($start_date))).'</b> to <b>'.(date("M d, Y", strtotime($end_date))).'</b>. The attached file is encrypted using your company ID.
			<br/><br/>
			Thanks
			</p>
			<small><i>This email system generated. Please do not reply.</i></small>
		</html>';
	
		$content = file_get_contents($file);
		$content = chunk_split(base64_encode($content));
	
		// a random hash will be necessary to send mixed content
		$separator = md5(time());
	
		// carriage return type (RFC)
		$eol = "\r\n";
	
		// main header (multipart mandatory)
		$headers = "From: MCDONALDS Scheduling System - Auto Generated Payslip <raytos.r.bsinfotech@gmail.com>" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol;
	
		// message
		$body = "--" . $separator . $eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
		$body .= "Content-Transfer-Encoding: 8bit" . $eol;
		$body .= $message . $eol;
	
		// attachment
		$body .= "--" . $separator . $eol;
		$body .= "Content-Type: application/octet-stream; name=\"" . (str_replace('uploads/payslips/','',$file_path)) . "\"" . $eol;
		$body .= "Content-Transfer-Encoding: base64" . $eol;
		$body .= "Content-Disposition: attachment" . $eol;
		$body .= $content . $eol;
		$body .= "--" . $separator . "--";
	
		//SEND Mail
		if (mail($mailto, $subject, $body, $headers)) {
			$resp['status'] = 'success';
			$this->set_flashdata('success', ' Payslip Attachment/s has been sent through email');
		} else {
			echo "mail send ... ERROR!";
			$resp['status'] = 'success';
			$resp['error'] =  error_get_last();
			$resp['msg'] = 'Email sending failed';
		}
		return json_encode($resp);
	}

	function send_payslip(){
		extract($_POST);
		$payslips = $this->conn->query("SELECT * FROM `payslip` where payroll_id = '{$id}' ");
		while($row = $payslips->fetch_assoc()){
			$send_mail = json_decode($this->send_email_pdf_payslip($row['id']));
			if($send_mail->status != 'success')
			break;
		}
		return json_encode($send_mail);
	}

	function send_payslip_single(){
		extract($_POST);
		$send_mail = json_decode($this->send_email_pdf_payslip($id));
		return json_encode($send_mail);
	}
}
ob_start();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$Master = new Master(); 


switch ($action) {
	case 'save_payroll':
		echo $Master->save_payroll();
	break;
	case 'delete_payroll':
		echo $Master->delete_payroll();
	break;
	case 'save_payslip':
		echo $Master->save_payslip();
	break;
	case 'delete_payslip':
		echo $Master->delete_payslip();
	break;
	case 'generate_payslip':
		echo $Master->generate_payslip();
	break;
	case 'payroll_generate_payslips':
		echo $Master->payroll_generate_payslips();
	break;
	case 'payroll_generate_payslips_single':
		echo $Master->payroll_generate_payslips_single();
	break;
	case 'send_payslip':
		echo $Master->send_payslip();
	break;
	case 'send_payslip_single':
		echo $Master->send_payslip_single();
	break;
	default:
		// echo $sysset->index();
		break;
}