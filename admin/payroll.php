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
    .table th, .table td {
        padding: 5px 2px;
        vertical-align: middle;
    }

    	@media (max-width: 564px){
		.card-body tbody, .card-body tr, .card-body td {
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
                    <b>Staff Payslip</b>
                    <span class="">
                        <button type="button" id="create_new" class="btn btn-flat btn-primary btn-sm col-sm-2 float-right"><span
                        class="fas fa-plus"></span> Create New</button>
                    </span>
                    <span class="">
                        <button type="button" id="print_table" class="btn btn-flat btn-dark btn-sm col-sm-2 float-right"><span
                        class="fa fa-print"></span> Print</button>
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Date Added</th>
                                <th>Payroll Code</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Action</th>
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
                                <td data-title="Action" class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="./?page=payslip&payroll_id=<?= $row['id'] ?>" type="button" data-id="<?php echo $row['id'] ?>" >View</a>
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
        uni_modal("Add New Payroll", "manage_payroll.php");
    })
    $('.edit_data').click(function() {
        uni_modal("Edit Payroll", "manage_payroll.php?id=" + $(this).attr('data-id'));
    })
    $('.view_data').click(function() {
        uni_modal("View Payroll", "view_payroll.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this payroll permanently?", "delete_payroll", [$(this).attr(
            'data-id')])
    })
    $('#print_table').click(function(){
        var nw = window.open("print_payroll.php","_blank","height=500,width=800")
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
        url: "function-file/master_payroll.php?f=delete_payroll",
        method: "POST",
        data: {id: $id},
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