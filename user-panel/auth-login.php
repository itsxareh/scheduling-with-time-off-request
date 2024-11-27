<!DOCTYPE html>
<html lang="en">
<?php 
$id_no = $_GET['id_no'];
session_start();
include('../admin/function-file/db_connect.php');
ob_start();
ob_end_flush();
?>
<head>
	<link rel="shortcut icon" type="x-icon" href="../admin/images/mcdotranslogo.png">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Staff Schedules</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php");

?>

</head>
<style>
	main .main-image{
		width: 100%;
		height: 100%;
		position: absolute;
	}

	main{
		width: 100vw;
		height: 100vh;
		align-items: center;
		justify-content: center;
	}
	.card-body {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: end;
	}
	button:hover {
		background-color: black !important;
		color: white !important;
		border: 1px solid white !important;
	}
	.card {
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: transparent;
	}
</style>

<body>


  <main id="main" class="">
  	<img src="../admin/images/loginbg.png" class="main-image">
        <div class="card">
            <div class="card-body">		
                <form id="auth-form" >
                    <h4><b class="text-white text-4xl">Kindly check your email!</b></h4>
                    <input type="hidden" name="id_no" value="<?php echo isset($id_no) ? $id_no : '' ?>">
                    <div class="form-group">
                        <label for="authcode" class="control-label text-white mt-4">One-Time Password has been sent to your email.</label>
                        <input type="number" id="authcode" name="authcode" class="form-control" placeholder="0000000"  oninput="if (this.value.length > 7) this.value = this.value.slice(0, 7);">
                    </div>
                    <center><button class="text-lg btn-sm btn-block btn-wave col-md-4 p-2 login">Login</button></center>
                    <div class="form-footer text-center mt-4 mr-2">
                    <p class="text-muted text-lg"><a href="../admin/login.php">Go to Admin Panel</a></p>
                    </div>
                </form>
            </div>
        </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
$('#auth-form').submit(function(e){
  e.preventDefault();
  $('#auth-form button[type="button"]').attr('disabled',true).html('Logging in...');
  if($(this).find('.alert-danger').length > 0 )
    $(this).find('.alert-danger').remove();

  var authcode = $('#auth-form input[name="authcode"]').val().trim();
  if (!authcode) {
    $('#auth-form').prepend('<div class="alert alert-danger">One-Time Password(OTP) is Required.</div>')
    $('#auth-form button[type="button"]').removeAttr('disabled').html('Login');
    return;
  }

  $.ajax({
    url:'../admin/function-file/ajax.php?action=auth_staff',
    method:'POST',
    data:$(this).serialize(),
    error:err=>{
      console.log(err)
      $('#auth-form button[type="button"]').removeAttr('disabled').html('Login');
    },
    success:function(resp){
      console.log(resp);
      if(resp == 1){
        location.href ='index.php?page=home';
      }else{
        $('#auth-form').prepend('<div class="alert alert-danger">One-Time Password(OTP) is Incorrect.</div>')
        $('#auth-form button[type="button"]').removeAttr('disabled').html('Login');
      }
    }
  });
});
</script>	
</html>