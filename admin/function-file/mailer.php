<?php 
include 'db_connect.php';
require "../../vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST["save_schedule"])){
    $id_no = $_POST['id_no'];
    $title = $_POST['title'];
    $schedule_type = $_POST['schedule_type'];
    $description = $_POST['description'];
    $station = $_POST['station'];
    $is_repeating = $_POST['is_repeating'];
    $repeating_data = $_POST['repeating_data'];
    $schedule_date = $_POST['schedule_date'];
    $schedule_end = $_POST['schedule_end'];
    $time_from = $_POST['time_from'];
    $time_to = $_POST['time_to'];
    $date_created = $_POST['date_created'];
    $notifications = $_POST['notifications'];

    $query = "INSERT INTO schedules (id_no, title, schedule_type, description, station, is_repeating, repeating_data, schedule_date, schedule_end, time_from, time_to, date_created, notifications) VALUES ('$id_no', '$title', '$schedule_type', '$description', '$station', '$is_repeating', '$repeating_data', '$schedule_date', '$schedule_end', '$time_from', '$time_to', '$notifications')";
    $result = mysqli_query($conn, $query);
    if($result){
        echo "Data inserted successfully";
    }else{
        echo "Insertion failed".mysqli_error($conn);
    }
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPSecure = 'tls';
    $mail->Username = 'schedulemcdomanager@gmail.com';
    $mail->Password = 'kayfeoowutyacepb';
    $mail->Port = 587;
    $mail->setFrom('schedulemcdomanager@gmail.com');
    $query = "SELECT email FROM staff WHERE id_no = $id_no";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
    }
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $message; 
    $mail->send();

    echo "<script>alert('Success'); document.location.href = 'index.php'; </script>";
}