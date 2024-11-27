<?php
include('function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `payroll` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    .uni_modal {
        padding: 0;
    }
</style>
<div class="container-fluid">
    <form action="" id="payroll-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group mb-3">
            <label for="code" class="control-label">Payroll Code</label>
            <input name="code" id="code" class="form-control rounded-0 form no-resize"
                value="<?php echo isset($code) ? $code : ''; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="start_date" class="control-label">Cut-off Start Date</label>
            <input name="start_date" id="start_date" type="date" class="form-control rounded-0 form no-resize"
                value="<?php echo isset($start_date) ? $start_date : ''; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="end_date" class="control-label">Cut-off End Date</label>
            <input name="end_date" id="end_date" type="date" class="form-control rounded-0 form no-resize"
                value="<?php echo isset($end_date) ? $end_date : ''; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="type" class="control-label">Payroll Type</label>
            <select name="type" id="type" class="custom-select rounded-0">
                <option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Monthly</option>
                <option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Semi-Monthly</option>
                <option value="3" <?php echo isset($type) && $type == 3 ? 'selected' : '' ?>>Daily</option>
            </select>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    $('#end_date').on('change', function(){
			var startDate = $('#start_date').val();
			var endDate = $('#end_date').val();
			if (endDate < startDate){
				alert_toast('End date should be greater than start date.', 'danger');
			$('#end_date').val('');
			}
		});
    $('#payroll-form').submit(function(e){
		e.preventDefault()
        if($('#code').val() == "" || $('#start_date').val() == "" || $('#end_date').val() == "" || $('#type').val() == ""){
            alert_toast("All fields are required", "danger");
            return false;
        }
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'function-file/master_payroll.php?f=save_payroll',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
            error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
            success: function(resp) {
                if (resp) {
                    alert_toast("Data saved successfully.", 'success')
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("An error occured.", 'error');
                    end_load();
                }
            }
		})
	})
})
</script>