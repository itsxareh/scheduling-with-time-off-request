<?php include 'function-file/db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<p>Name: <b><?php echo ucwords($name) ?></b></p>
	<p>Status: </i> <b><?php echo $status ?></b></p>
	<p>Position: </i> <b><?php echo $position ?></b></p>
	<p>Gender: <b><?php echo ucwords($gender) ?></b></p>
	<p>Email: </i> <b><?php echo $email ?></b></p>
	<p>Contact: </i> <b><?php echo $contact ?></b></p>
	<p>Address: </i> <b><?php echo $address ?></b></p>
	<p>Age: </i> <b><?php echo $age ?></b></p>
	<p>Birthdate: </i> <b><?php echo $birthdate ?></b></p>
	<p>Date joined: </i> <b><?php echo $datejoined ?></b></p>
	<hr class="divider">
</div>
	<div class="modal-footer display">
		<button class="btn float-right btn-secondary" type="button" data-dismiss="modal">Close</button>
	</div>
<style>
	p{
		margin:unset;
	}
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display {
		display: block;
	}
</style>
<script>
	
</script>