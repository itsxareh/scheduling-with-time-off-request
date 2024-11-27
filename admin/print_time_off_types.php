<?php 
include('function-file/db_connect.php');
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
    .btn-dark {
        background-color: #343a40 !important;
    }
    .btn-dark:hover {
        background-color: #212529 !important;
    }
    table{
        width:100%;
        border-collapse:collapse;
    }
    tr,td,th{
        border:1px solid black
    }
    .text-center{
        text-align:center;
    }
    .text-right{
        text-align:right;
    }
    table thead tr td {
        text-align: center;
    }
</style>
<table>
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br>
    <center><h2>Leave Types</h2></center>
    <thead>
        <tr>
            <td>#</td>
            <td>Time-off Type</td>
            <td>Description</td>
        </tr>
    </thead>
    <tbody>
        <?php 
            $i = 1;
            $qry = $conn->query("SELECT * FROM leave_types");
            while($row = $qry->fetch_assoc()):
        ?>
        <tr>
            <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
            <td data-title="Leave Type" class="td"><?php echo $row['leave_type'] ?></td>
            <td data-title="Description" class="td"><?php echo $row['description']?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
<table