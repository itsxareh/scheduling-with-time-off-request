<?php 
include ('function-file/db_connect.php');
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

        .month {
            text-align: center;
            font-size: 20px;
        }
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
<div class="container-fluid">
	<div class="row">
	    <div class="col-lg-12 ps-0">
            <div class="card">
                <div class="card-header">
                        <b>Time-Off Requests</b>
                        <span class="">
                            <button class="btn btn-dark btn-block btn-sm col-sm-2 float-right"  id="print_table">
                            <i class="fa fa-print"></i>Print</button>
                        </span>
                    </div>
                <div class="card-body">
                    <?php 
                        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        for($m = 0; $m < count($months); $m++) {
                        $month = date('m', strtotime("01-" . $months[$m] . "-2000"));
                        $qry = $conn->query("SELECT *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as name from staff e inner join `time-off-request` pp on pp.id_no = e.id_no WHERE MONTH(from_date) = '$month' order by date_created desc");
                        if ($qry->num_rows > 0) {
                            echo "<p style='font-size: 40px'; class='month text-center'>" . $months[$m] . "</p>
                                <span class=''>
                                    <button class='print_monthtable btn btn-dark btn-block btn-sm col-sm-2 float-right' id='print_monthtable_<?php echo $m; ?>' data-month='$months[$m]'>
                                    <i class='fa fa-print'></i>Print</button>
                                </span>";
                    ?>
                    <table class="table table-bordered table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Date Added</th>
                                <th>Staff</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Time Off Type</th>
                                <th>Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                while($row = $qry->fetch_assoc()){
                            ?>
                            <tr>
                                <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
                                <td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td data-title="Staff" class="td"><?php echo $row['name'] ?></td>
                                <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['from_date'])) ?></td>
                                <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['to_date'])) ?></td>
                                <td data-title="Time Off Type" class="td"><?php echo $row['leave_type'] ?></td>
                                <td data-title="Description" class="td"><?php echo $row['description'] ? $row['description'] : "NONE" ?></td>
                                <td data-title="Type" class="td text-center">
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
                                    <td data-title="Action" class="text-center">
                                        <button class="btn btn-sm btn-outline-primary decide_data" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >Decide</button>
                                        <button class="btn btn-sm btn-outline-primary view_data" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
                                        <button class="btn btn-sm btn-outline-primary delete_data" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >Delete</button>
                                    </td>
                                </td>
                            </tr>
                            <?php   } ?>
                        </tbody>
                    </table>
                    <?php
                        } 
                        } 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.decide_data').click(function() {
        uni_modal("Action", "manage_time_off.php?id=" + $(this).attr('data-id'));
    })
    $('.view_data').click(function() {
        uni_modal("View Request", "view-request.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this request?", "delete_time_off", [$(this).attr(
            'data-id')])
    })
    $('#print_table').click(function(){
        var nw = window.open("print_requests.php","_blank","height=500,width=800")
        setTimeout(function(){
            nw.print()
            setTimeout(function(){
                nw.close()
                },100)
        },100)
    })
    $(document).on("click", ".btn[id^='print_monthtable_']", function() {
    var month = $(this).data("month");
    var nw = window.open("print_monthrequests.php?month=" + month, "_blank", "height=500,width=800");
    setTimeout(function() {
        nw.print();
        setTimeout(function() {
        nw.close();
        }, 100);
    }, 100);
    });
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
        url: "function-file/ajax.php?action=delete_time_off",
        method: "POST",
        data: {id: $id},
        error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
        success: function(resp) {
            if (resp) {
                alert_toast("Request deleted successfully.", 'success')
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