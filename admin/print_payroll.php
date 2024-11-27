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
</style>
<table>
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br><br>
    <center><h2>Staff Payroll</h2></center>
    <thead>
        <tr>
            <td class="text-center">#</td>
            <td class="text-center">Date Added</td>
            <td class="text-center">Payroll Code</td>
            <td class="text-center">Start Date</td>
            <td class="text-center">End Date</td>
            <td class="text-center">Type</td>
        </tr>
    </thead>
    <tr>
        <?php 
        $i = 1;
            $qry = $conn->query("SELECT * from `payroll` where delete_flag = 0 order by unix_timestamp(`start_date`) desc, unix_timestamp(`end_date`) desc ");
            while($row = $qry->fetch_assoc()):
        ?>
        <tr>
            <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
            <td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
            <td data-title="Payroll Code" class="td"><?php echo $row['code'] ?></td>
            <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['start_date'])) ?></td>
            <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['end_date'])) ?></td>
            <td data-title="Type" class="td text-center">
                <?php 
                switch($row['type']){
                    case 1:
                        echo '<span class="badge badge-primary bg-gradient px-3 rounded-pill">Monthly</span>';
                        break;
                    case 2:
                        echo '<span class="badge badge-warning bg-gradient px-3 rounded-pill">Semi-Monthly</span>';
                        break;
                    case 3:
                        echo '<span class="badge badge-default border bg-gradient px-3 rounded-pill">Daily</span>';
                        break;
                }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tr>
</table>