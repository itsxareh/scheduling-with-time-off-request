<?php 
include ('../admin/function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `time-off-request` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
        .table th, .table td {
        padding: 5px 2px;
        vertical-align: middle;
    }

    	@media (max-width: 760px){
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
						<b>Time-Off Requests</b>
						<span class="">

            <button type="button" id="create_new" class="btn btn-flat btn-primary btn-sm col-sm-2 float-right"><span
                    class="fas fa-plus"></span> Request a time-off</button>
                        </span>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid ps-0">
            <table class="table table-bordered table-stripped">
                <colgroup>
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Date Added</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time Off Type</th>
                        <th>Description</th>
                        <th class="text-center">Status</th>
                        <th>Admin Remark</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
					$i = 1;
						$qry = $conn->query("SELECT *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as name from staff e inner join `time-off-request` pp on pp.id_no = e.id_no where pp.id_no = '{$_SESSION['login_id_no']}' order by unix_timestamp(`from_date`) desc, unix_timestamp(`to_date`) desc ");
						while($row = $qry->fetch_assoc()):
					?>
                    <tr>
                        <td data-title="#" class="td txtctr text-center"><?php echo $i++; ?></td>
                        <td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                        <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['from_date'])) ?></td>
                        <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['to_date'])) ?></td>
                        <td data-title="Time Off Request" class="td"><?php echo $row['leave_type'] ?></td>
                        <td data-title="Description" class="td"><?php echo $row['description'] ? $row['description'] : "NONE" ?></td>
                        <td data-title="Status" class="td txtctr text-center">
                            <?php 
                            switch($row['stats']){
                                case 'pending':
                                    echo '<span class="badge badge-dark border bg-gradient px-3 rounded-pill">Pending</span>';
                                    break;
                                case 'approved':
                                    echo '<span class="badge badge-success bg-gradient px-3 rounded-pill">Approved</span>';
                                    break;
                                case 'declined':
                                    echo '<span class="badge badge-primary bg-gradient px-3 rounded-pill">Declined</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td data-title="Admin Remark" class="td"><?php echo $row['admin_remark'] ? $row['admin_remark'] : "NONE"?></td>
                        <td data-title="Action" class="text-center">
                            <button class="btn btn-sm btn-outline-primary view_data" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
                            <button class="btn btn-sm btn-outline-primary edit_data" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
                            <button class="btn btn-sm btn-outline-primary delete_data" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
    $('#create_new').click(function() {
        uni_modal("Add New Time-off Request", "time-off-request.php?id=<?= isset($id) ? $id: '' ?>");
    })
    $('.view_data').click(function() {
        uni_modal("View Request", "view_request.php?id=" + $(this).attr('data-id'));
    })
    $('.edit_data').click(function() {
        uni_modal("Edit Request", "manage_time_off.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this request?", "delete_time_off", [$(this).attr(
            'data-id')])
    })
    $('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [8]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });
})

function delete_time_off($id) {
    start_load()
    $.ajax({
        url: "../admin/function-file/ajax.php?action=delete_time_off",
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