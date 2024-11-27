<?php
require_once('function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted"><b>Name</b></dt>
        <dd class="pl-4"><?= isset($fullname) ? $fullname : "" ?></dd>
        <dt class="text-muted"><b>Gender</b></dt>
        <dd class="pl-4"><?= isset($gender) ? $gender : "" ?></dd>
        <dt class="text-muted"><b>Email</b></dt>
        <dd class="pl-4"><?= isset($email) ? $email : "" ?></dd>
        <dt class="text-muted"><b>Position</b></dt>
        <dd class="pl-4"><?= isset($position) ? $position : "" ?></dd>
        <dt class="text-muted"><b>Status</b></dt>
        <dd class="pl-4"><?= isset($status) ? $status : "" ?></dd>
    </dl>
    <div class="clear-fix mb-3"></div>
    <div class="text-right">
        <button class="btn btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>  
</div>