
<?php 
require_once('function-file/db_connect.php');
if(isset($_GET['payroll_id'])){
    $payroll_id = $_GET['payroll_id'];
    $qry = $conn->query("SELECT * FROM `payroll` where id = '{$_GET['payroll_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k=> $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
        .table th, .table td {
        padding: 5px 2px;
        vertical-align: middle;
    }

    	@media (max-width: 564px){
		.card-body tbody, .card-body tr, .card-body td{
			display: block;
		}
		.card-body thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		.card-body .td {
			position: relative;
			padding-left: 50%;
			border: none;
			border-bottom: 1px solid #eee;
		}
		.card-body .td::before {
			content: attr(data-title);
			position: absolute;
			left: 5px;
		}
		.card-body tr {
			border-bottom: 1px solid #ccc;
		}
        .td {
            text-align: end !important;
        }
	}
</style>
<div class="container-fluid">
	<div class="row">
	    <div class="col-lg-12 ps-0">
				<div class="card">
					<div class="card-header">
                        <b class="card-title pl-3">Payslip List</b>
                        <span class="">
                            <button type="button" id="create_new" class="btn btn-flat btn-primary btn-sm  col-sm-2 float-right"><span class="fas fa-plus"></span>Create New</button>
                        </span>
                        <span class="">
                            <button type="button" data-id="<?php echo $payroll_id ?>" id="print_table" class="btn btn-flat btn-dark btn-sm col-sm-2 float-right"><span class="fa fa-print"></span> Print</button>
                        </span>
                    </div>
                <div class="card-body">
                    <fieldset class='border py-2 px-1 mb-3'>
                        <legend class="w-auto">Payroll Details</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="d-flex">
                                    <dt>Code: </dt> 
                                    <dd class="px-3"><?= $code ?></dd>
                                </dl>
                                <dl class="d-flex">
                                    <dt>Type: </dt>
                                    <dd class="px-3">
                                    <?php 
                                        switch($type){
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
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="d-flex">
                                    <dt>Start Date: </dt>
                                    <dd class="px-3"><?= date("M d, Y", strtotime($start_date)) ?></dd>
                                </dl>
                                <dl class="d-flex">
                                    <dt>End Date: </dt>
                                    <dd class="px-3"><?= date("M d, Y", strtotime($end_date)) ?></dd>
                                </dl>
                            </div>
                        </div>
                    </fieldset>
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Date Added</th>
                                <th>Staff</th>
                                <th>Net</th>
                                <th>Position</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                                $qry = $conn->query("SELECT p. *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, position from `payslip` p inner join staff e on p.id_no = e.id_no where p.payroll_id = '{$id}'");
                                while($row = $qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td data-title="#" class="text-center td"><?php echo $i++; ?></td>
                                <td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i:s",strtotime($row['date_created'])) ?></td>
                                <td data-title="Staff" class="td"><?php echo $row['id_no'].' - '.$row['fullname'] ?></td>
                                <td data-title="Net" class="td"><?php echo number_format($row['net'],2) ?></td>
                                <td data-title="Position" class="td"><?php echo $row['position']?></td>
                                <td align="center">
                                <a class="btn btn-sm btn-outline-primary print_btn" type="button" data-id="<?php echo $row['id'] ?>" >Print</a>
                                <a class="btn btn-sm btn-outline-primary view_data" type="button" data-id="<?php echo $row['id'] ?>" >View</a>
                                <a class="btn btn-sm btn-outline-primary edit_data" type="button" data-id="<?php echo $row['id'] ?>" >Edit</a>
                                <a class="btn btn-sm btn-outline-primary delete_data" type="button" data-id="<?php echo $row['id'] ?>">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#create_new').click(function() {
        uni_modal("Add New Payslip", "manage_payslip.php?payroll_id=<?= isset($id) ? $id: '' ?>", 'large');
    })
    $('.edit_data').click(function() {
        uni_modal("Edit Payslip", "manage_payslip.php?payroll_id=<?= isset($id) ? $id: '' ?>&id=" + $(this).attr('data-id'));
    })
    $('.view_data').click(function() {
        uni_modal("View Payslip", "view-payslip.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this payslip permanently?", "delete_payslip", [$(this).attr(
            'data-id')],'large')
    })
    $('.print_btn').click(function(){
        var nw = window.open("print_payslip.php?id=" + $(this).attr('data-id'),"_blank","height=500,width=800")
        setTimeout(function(){
            nw.print()
            setTimeout(function(){
                nw.close()
                },100)
        },100)
    })
    $('#print_table').click(function(){
        var nw = window.open("print_staffpayslips.php?payroll_id=" + $(this).attr('data-id'),"_blank","height=500,width=800")
        setTimeout(function(){
            nw.print()
            setTimeout(function(){
                nw.close()
                },100)
        },100)
    })
    $('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [5]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });
})
function delete_payslip($id) {
    start_load()
    $.ajax({
        url: "function-file/master_payroll.php?f=delete_payslip",
        method:'POST',
			data:{id:$id},
            error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
            success: function(resp) {
                if (resp) {
                    alert_toast("Data deleted successfully.", 'success')
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("An error occured.", 'error');
                    end_load();
                }
            }
		})
}

</script>