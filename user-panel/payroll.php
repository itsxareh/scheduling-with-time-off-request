<?php 
include('../admin/function-file/db_connect.php');
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
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
<div class="card card-outline card-primary rounded-0 shadow">
				<div class="card">
					<div class="card-header">
						<b>Staff Payslip</b>
						<span class="">
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid ps-0">
            <table class="table table-bordered table-stripped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Added</th>
                        <th>Payroll Code</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                        <td data-title=" Type" class="td text-center">
                            <?php 
                            switch($row['type']){
                                case 1:
                                    echo '<span class="badge badge-primary bg-gradient px-3 rounded-pill">Monthly</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge badge-warning bg-gradient px-3 rounded-pill">Semi-monthly</span>';
                                    break;
                                case 3:
                                    echo '<span class="badge badge-default border bg-gradient px-3 rounded-pill">Daily</span>';
                                    break;
                            }
                            ?>
                        </td>

                        <td align="center">
                        <a class="btn btn-sm btn-outline-primary view_data" href="./?page=payslip&payroll_id=<?= $row['id'] ?>" type="button" data-id="<?php echo $row['id'] ?>" ><span class="fa fa-eye"></span>View</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.view_data').click(function() {
        uni_modal("View Payroll", "payslip.php?id=" + $(this).attr('data-id'));
    })
    $('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [6]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });

})

function delete_payroll($id) {
    start_load()
    $.ajax({
        url: "../admin/function-file/master_payroll.php?f=delete_payroll",
        method: "POST",
        data: {id: $id},
        error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
        success: function(resp) {
            if (JSON.parse(resp).status == "success") {
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