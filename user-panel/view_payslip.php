<?php
include('../admin/function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT pp.*, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, e.status, e.position from staff e inner join payslip pp on pp.id_no = ".$_GET['e.id_no']);
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
</style>
<div class="container">
      <div class="col-lg-12 ps-0">
    <div class="row">
        <div class="card">
        <div class="card-body">
        <div class="col-12 text-center border m-0 font-weight-bold">Payroll Details</div>
        <div class="col-2 border p-1  font-weight-bold">Code</div>
        <div class="col-4 border p-1"><?= isset($_payroll['code']) ? $_payroll['code'] : '' ?></div>
        <div class="col-2 border p-1  font-weight-bold">Type</div>
        <div class="col-4 border p-1">
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
        </div>
        <div class="col-2 border p-1  font-weight-bold">Cut-off Start</div>
        <div class="col-4 border p-1"><?= isset($_payroll['start_date']) ? date("M d, Y", strtotime($_payroll['start_date'])) : '' ?></div>
        <div class="col-2 border p-1  font-weight-bold">Cut-off End</div>
        <div class="col-4 border p-1"><?= isset($_payroll['end_date']) ? date("M d, Y", strtotime($_payroll['end_date'])) : '' ?></div>
    </div>
    <div class="row">
        <div class="col-12 text-center border m-0 font-weight-bold">Staff Details</div>
        <div class="col-2 border p-1  font-weight-bold">Name</div>
        <div class="col-4 border p-1"><?php echo ucwords($fullname) ?></div>
        <div class="col-2 border p-1  font-weight-bold">ID No.</div>
        <div class="col-4 border p-1"><?= $id_no ?></div>
        <div class="col-2 border p-1  font-weight-bold">Status</div>
        <div class="col-4 border p-1"><?= $status ?></div>
        <div class="col-2 border p-1  font-weight-bold">Position</div>
        <div class="col-4 border p-1"><?= $position ?></div>
    </div>
    
    <div class="row">
        <div class="col-4 border p-1">Details</div>
        <div class="col-4 border p-1">Allowances</div>
        <div class="col-4 border p-1">Deduction</div>
        
        <div class="col-4 border p-1">
            <dl class="row">
                <dt class="col-7">Basic Salary</dt>
                <dd class="text-right col-5"><?= isset($rate) ? number_format($rate,2) : 0.00 ?></dd>
                <dt class="col-7">Attendance <small>(days)</small></dt>
                <dd class="text-right col-5"><?= isset($days_present) ? number_format($days_present + $days_absent) : 0 ?></dd>
                <dt class="col-7">Absences <small>(days)</small></dt>
                <dd class="text-right col-5"><?= isset($days_absent) ? number_format($days_absent) : 0 ?></dd>
                <dt class="col-7">Late/Undertime <small>(mins)</small></dt>
                <dd class="text-right col-5"><?= isset($tardy_undertime) ? number_format($tardy_undertime) : 0 ?></dd>
            </dl>
        </div>
        <div class="col-4 border p-1">
            <dl class="row">
                <?php 
                $allowances = $conn->query("SELECT * FROM `allowance_list` where payslip_id = '{$id}'");
                while($row = $allowances->fetch_assoc()):
                ?>
                    <dt class="col-7"><?= $row['name'] ?></dt>
                    <dd class="text-right col-5"><?= number_format($row['amount'],2) ?></dd>
                <?php endwhile; ?>
                <dt class="col-7">Total</dt>
                <dd class="text-right col-5"><?= isset($total_allowance) ?  number_format($total_allowance,2) : 0.00 ?></dd>
            </dl>
        </div>
        <div class="col-4 border p-1">
            <dl class="row">
                <?php 
                $deductions = $conn->query("SELECT * FROM `deduction_list` where payslip_id = '{$id}'");
                while($row = $deductions->fetch_assoc()):
                ?>
                    <dt class="col-7"><?= $row['name'] ?></dt>
                    <dd class="text-right col-5"><?= number_format($row['amount'],2) ?></dd>
                <?php endwhile; ?>
                <dt class="col-7">Total</dt>
                <dd class="text-right col-5"><?= isset($total_deduction) ?  number_format($total_deduction,2) : 0.00 ?></dd>
            </dl>
        </div>
    </div>
    <div class="d-flex w-100 mt-3">
        <div class="col-auto flex-shrink-1 flex-grow-1 text-right h4"><b>Net Income:</b></div>
        <div class="col-auto px-3 h4"><b><?= number_format($net,2) ?></b></div>
    </div>
    <hr>
    <div class="clear-fix mb-3"></div>
        </div>
    </div>
</div>