<?php
include('function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `announcement` where id = '1' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>

<div class="container-fluid">
<form action="" id="announcement-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group mb-3">
            <label for="title" class="control-label">Title</label>
            <input name="title" id="title" class="form-control form"
                value="<?php echo isset($title) ? $title : ''; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description" class="control-label">Description</label>
            <textarea name="description" id="description" type="text" class="form-control form"
                rows="8" required><?php echo isset($description) ? $description : ''; ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="author" class="control-label">Author</label>
            <input name="author" id="author" type="text" class="form-control form"
                value="<?php echo isset($author) ? $author : ''; ?>" required>
        </div>
    </form>
</div>
<script>
    $('#announcement-form').submit(function(e){
		e.preventDefault()
        if ( $('#title').val() == "" || $('#description').val() == ""){
            alert_toast("All fields are required", "danger");
            return false;
        }
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'function-file/ajax.php?action=save_announcement',
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
                    alert_toast("Announcement updated.", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("Couldn't change for now. Please try again later", 'error');
                    end_load();
                }
            }
		})
	})
</script>

