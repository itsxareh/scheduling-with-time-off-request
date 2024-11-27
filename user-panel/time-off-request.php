<?php
    include('../admin/function-file/db_connect.php');
?>
<div class="container fluid">
        <form action="" id="request-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="card-body">
        <div class="form-group">
            <label for="example-date-input" class="col-form-label">Starting Date</label>
            <input class="form-control" id="from_date" type="date" data-inputmask="'alias': 'date'" required id="example-date-input" name="from_date" required>
        </div>
        <div class="form-group">
            <label for="example-date-input" class="col-form-label">End Date</label>
            <input class="form-control" id="to_date" type="date" data-inputmask="'alias': 'date'" required id="example-date-input" name="to_date" required>
        </div>
        <div class="form-group">
            <label class="col-form-label">Time-Off Type</label>
            <select class="custom-select" name="leave_type" autocomplete="off" required>
                <option value="">Click here to select any ...</option>
                <?php
                $leavetype = $conn->query("SELECT * FROM leave_types");
                while($row= $leavetype->fetch_array()):
                ?>
                <option value='<?php echo ucwords($row['leave_type']) ?>'><?php echo ucwords($row['leave_type']) ?> - <?php echo ucwords($row['description']) ?></option>
            <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="example-text-input" class="col-form-label">Describe Your Conditions</label>
            <textarea class="form-control" name="description" type="text" name="description" id="example-text-input" rows="3" required></textarea>
        </div>
        </div>
    </form>    
</div>
<script>
    $('#to_date').on('change', function(){
		var startDate = $('#from_date').val();
		var endDate = $('#to_date').val();
            if (endDate < startDate){
                alert_toast('End date should be greater than start date.', 'danger');
            $('#to_date').val('');
            }
	});
    $('#request-form').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'../admin/function-file/ajax.php?action=save_time_off',
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
                    alert_toast("Your time-off application has been applied.", 'success')
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("Could not process this time. Please try again later", 'error');
                    end_load();
                }
            }
		})
	})
</script>