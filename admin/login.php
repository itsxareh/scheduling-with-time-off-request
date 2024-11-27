<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('function-file/db_connect.php');
ob_start();
ob_end_flush();
?>	
<head>
	<link rel="shortcut icon" type="x-icon" href="images/mcdotranslogo.png">
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Scheduler Login</title>
		

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php");

?>

</head>
<style>
	.card {
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: transparent;
	}
	.text-black:hover {
		color: white !important;
		background-color: black !important;
	}
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
	#login-form {
		width: 100%;
	}
	#login-form button {
		width: 50%;
	}

</style>

<body>
  <main id="main">
  <img src="images/loginbg.png" class="main-image">
  	<div class="card">
  			<div class="card-body">
			  	<center><p class="text-4xl text-white mb-4">Staff Scheduling System</p></center>
  				<form id="login-form">
  						<div class="form-group">
  							<center><label for="username" class="control-label text-lg text-white">Username</label></center>
  							<input type="text" id="username" name="username" class="form-control" autocomplete="off">
  						</div>
  						<div class="form-group">
						  <center><label for="password" class="control-label text-lg text-white">Password</label></center>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="text-lg btn-sm btn-block btn-wave col-md-4 p-2 login">Login</button></center>
						<div class="form-footer text-center mt-4 mr-2">
							<p class="text-muted text-lg"><a href="../user-panel/login.php">Go to Staff Panel</a></p>
                		</div>
					</form>
  			</div>
  		</div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'function-file/ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>