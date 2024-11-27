<?php
include('function-file/db_connect.php');
if(isset($_GET['payroll_id'])){
    $payroll_id = $_GET['payroll_id'];
    $qry = $conn->query("SELECT * FROM `payroll` where id = '$payroll_id' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k=> $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>}
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
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br>
    <center><h2>Staff Payslips</h2></center>
    <table>
    <tr>
        <th style="text-align: center" colspan = 4>Payroll Details</th>
    </tr>
    <tr>
        <td style="width: 25%">Code</td>
        <td style="width: 25%"><?= isset($payroll_id) ? $payroll_id : '' ?></td>
        <td style="width: 25%">Type</td>
        <td style="width: 25%">
            <?php
                $type = isset ($type) ? $type : '';
                switch($type){
                    case 1:
                        echo 'Monthly';
                        break;
                    case 2:
                        echo 'Semi-Monthly';
                        break;
                    case 3:
                        echo 'Daily';
                        break;
                    default:
                        echo 'N/A';
                        break;
                }
            ?>
        </td>
    </tr>
    <tr>
        <td>Cut-off Start</td>
        <td><?= isset($start_date) ? date("M d, Y", strtotime($start_date)) : '' ?></td>
        <td>Cut-off End</td>
        <td><?= isset($end_date) ? date("M d, Y", strtotime($end_date)) : '' ?></td>
    </tr>
    </table>
    <hr>
    <br>
    <table>
        <tr>
            <th style="text-align: center" colspan="10">Payslips</th>
        </tr>
        <tr>
            <th style="width: 20%">Name</th>
            <th style="width: 20%">ID No.</th>
            <th style="width: 20%">Rate Per Hour</th>
            <th style="width: 20%">Days of Present</th>
            <th style="width: 20%">Net</th>
        </tr> 
            <?php 
            $i = 1;
                $qry = $conn->query("SELECT p. *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, position from `payslip` p inner join staff e on p.id_no = e.id_no where p.payroll_id = '{$payroll_id}'");
                while($row = $qry->fetch_assoc()):
            ?>      
        <tr>
            <td style="width: 20%"><?= ucwords($row['fullname']) ?></td>
            <td style="text-align:center; width: 20%"><?= $row['id_no'] ?></td>
            <td style="text-align:center; width: 20%"><?= $row['rate'] ?></td>
            <td style="text-align:center; width: 20%"><?= $row['days_present'] ?></td>
            <td style="text-align:center; width: 20%"><?= $row['net'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>