<?php
include('function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT pp.*, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, e.status, e.position from staff e inner join payslip pp on pp.id_no = e.id_no where pp.id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
        if(isset($payroll_id)){
            $payroll = $conn->query("SELECT * FROM payroll where id = '{$payroll_id}'");
            if($payroll->num_rows > 0){
                foreach($payroll->fetch_array() as $k=> $v){
                    if(!is_numeric($k))
                        $_payroll[$k] = $v;
                }
            }
        }
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
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br>
    <center><h2>Payslip</h2></center>
    <table>
    <tr>
        <th style="text-align: center" colspan = 4>Payroll Details</th>
    </tr>
    <tr>
        <td style="width: 25%">Code</td>
        <td style="width: 25%"><?= isset($_payroll['code']) ? $_payroll['code'] : '' ?></td>
        <td style="width: 25%">Type</td>
        <td style="width: 25%">
            <?php 
                $_payroll['type'] = isset($_payroll['type']) ? $_payroll['type'] : '';
                switch($_payroll['type']){
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
        <td><?= isset($_payroll['start_date']) ? date("M d, Y", strtotime($_payroll['start_date'])) : '' ?></td>
        <td>Cut-off End</td>
        <td><?= isset($_payroll['end_date']) ? date("M d, Y", strtotime($_payroll['end_date'])) : '' ?></td>
    </tr>
    <tr>
    </table>
    <hr>
    <table>
        <th style="text-align: center" colspan="4">Staff Details</th>
    </tr>
    <tr>
        <td style="width: 25%">Name</td>
        <td style="width: 25%"><?= ucwords($fullname) ?></td>
        <td style="width: 25%">ID No.</td>
        <td style="width: 25%"><?= $id_no ?></td>
    </tr>
    <tr>
        <td>Status</td>
        <td><?= $status ?></td>
        <td>Position</td>
        <td><?= $position ?></td>
    </tr>
    </table>
    <hr>
    <table>
        <tr>
            <th style="text-align: center" colspan="2">Allowances</th>

        </tr>
        <tr>
            <?php 
            $allowances = $conn->query("SELECT * FROM `allowance_list` where payslip_id = '{$id}'");
            while($row = $allowances->fetch_assoc()):
            ?>
            <td><?= $row['name'] ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
        </tr>
            <?php endwhile; ?>
        <tr>
            <td class="text-right" style="width: 80%"><b>Total:</b></td>
            <td><b><?= isset($total_allowance) ?  number_format($total_allowance,2) : 0.00 ?></b></td>
        </tr>
    </table>
    <hr>
    <table>
        <tr>
        <th style="text-align: center" colspan="2">Deduction</th>
        </tr>
        <tr>
            <?php 
            $deductions = $conn->query("SELECT * FROM `deduction_list` where payslip_id = '{$id}'");
            while($row = $deductions->fetch_assoc()):
            ?>
            <td><?= $row['name'] ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
        </tr>
            <?php endwhile; ?> 
        <tr>
            <td class="text-right" style="width: 80%"><b>Total:</b></td>
            <td><b><?= isset($total_deduction) ?  number_format($total_deduction,2) : 0.00 ?></b></td>
        </tr>
    </table>
    <hr>
    <table>
    <tr>
        <th style="text-align: center" colspan="2">Details</th>
    </tr>
    <tr>
        <td>Rate Per Hour</td>
        <td style="width: 50%"><?= isset($rate) ? number_format($rate,2) : 0.00 ?></td>

    </tr>
    <tr>
        <td>Total Time of Present <small>(mins)</small></td>
        <td style="width: 50%"><?= isset($minutes_present) ? number_format($minutes_present) : 0 ?></td>
    </tr>   
    <tr> 
        <td>Attendance <small>(days)</small></td>
        <td style="width: 50%"><?= isset($days_present) ? number_format($days_present + $days_absent) : 0 ?></td>
    </tr>
    <tr>
        <td>Absences <small>(days)</small></td>
        <td style="width: 50%"><?= isset($days_absent) ? number_format($days_absent) : 0 ?></td>
    </tr>
    <tr>
        <td>Late/Undertime <small>(mins)</small></td>
        <td style="width: 50%"><?= isset($tardy_undertime) ? number_format($tardy_undertime) : 0 ?></td>
    </tr>
    <tr>
            <td class="text-right" style="width: 80%"><b>Total Allowances:</b></td>
            <td><b><?= isset($total_allowance) ?  number_format($total_allowance,2) : 0.00 ?></b></td>
        </tr>
    <tr>
            <td class="text-right" style="width: 80%"><b>Total Deduction:</b></td>
            <td><b><?= isset($total_deduction) ?  number_format($total_deduction,2) : 0.00 ?></b></td>
        </tr>
    <tr>
        <td class="text-right" style="width: 80%"><b>Net Income:</b></td>
        <td style="width: 50%"><b><?= number_format($net,2) ?></b></td>
    </tr>
    </table>
</table>