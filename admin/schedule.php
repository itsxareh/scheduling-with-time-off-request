<?php include('function-file/db_connect.php');?>
<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 ps-0">
				<div class="card">
					<div class="card-header">
						<b>Schedule</b>
						<span class="">
							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right"  id="new_schedule">
							<i class="fa fa-plus"></i> New Entry</button>
						</span>
						<span class="">
							<button class="btn btn-dark btn-block btn-sm col-sm-2 float-right"  id="print_table">
							<i class="fa fa-print"></i>Print</button>
						</span>
					</div>
					<div class="card-body">
						<div class="row align-items-center">
							<label for="" class="control-label col-md-2 offset-md-2">View Schedule of:</label>
							<div class=" col-md-4">
							<select name="id_no" id="id_no" class="custom-select select2">
								<option value=""></option>
								<option value="all">All</option>
							<?php 
								$staff = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff order by concat(lastname,', ',firstname,' ',middlename) asc");
								while($row= $staff->fetch_array()):
							?>
								<option value='<?php echo $row['id_no'] ?>'><?php echo ucwords($row['name']) ?></option>
							<?php endwhile; ?>
							</select>
							</div>
						</div>
						<hr class="m-2">
						<div id="calendar" class="mt-2"></div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>
<style>
@media (max-width: 530px){
	.fc .fc-toolbar {
		display: block;
	}
	.fc .fc-button-group, .fc-toolbar-title{
		display: flex !important;
	}
	.fc-toolbar-title{
		justify-content: center !important;
	}
	.fc-today-button {
		margin-top: 3px !important;
		text-align: center !important;
		width: 100%;
	}
	.fc-direction-ltr .fc-toolbar > * > :not(:first-child) {
    margin-left: 0;
	}
	.fc-toolbar-chunk {
		margin-bottom: 3px;
	}
	.fc-dayGridMonth-view, .fc-dayGridMonth-button {
		display: none !important;
	}
}
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: 150px;
	} 
	.avatar {
	    display: flex;
	    border-radius: 100%;
	    width: 100px;
	    height: 100px;
	    align-items: center;
	    justify-content: center;
	    border: 3px solid;
	    padding: 5px;
	}
	.avatar img {
	    max-width: calc(100%);
	    max-height: calc(100%);
	    border-radius: 100%;
	}
		input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
a.fc-daygrid-event.fc-daygrid-dot-event.fc-event.fc-event-start.fc-event-end.fc-event-past {
    cursor: pointer;
}
a.fc-timegrid-event.fc-v-event.fc-event.fc-event-start.fc-event-end.fc-event-past {
    cursor: pointer;
}
</style>

<script>

	$('#new_schedule').click(function(){
		uni_modal('New Schedule','manage_schedule.php')
	})
	$('#print_table').click(function(){
        var nw = window.open("print_schedule.php","_blank","height=500,width=800")
        setTimeout(function(){
            nw.print()
            setTimeout(function(){
                nw.close()
                },100)
        },100)
    })
	// $('.view_alumni').click(function(){
	// 	uni_modal("Bio","view_alumni.php?id="+$(this).attr('data-id'))
	// })
	// $('.delete_alumni').click(function(){
	// 	_conf("Are you sure to delete this scheduleee?","delete_alumni",[$(this).attr('data-id')])
	// })
	
	// function delete_alumni($id){
	// 	start_load()
	// 	$.ajax({
	// 		url:'function-file/ajax.php?action=delete_alumni',
	// 		method:'POST',
	// 		data:{id:$id},
	// 		success:function(resp){
	// 			if(resp==1){
	// 				alert_toast("Data successfully deleted",'success')
	// 				setTimeout(function(){
	// 					location.reload()
	// 				},100)

	// 			}
	// 		}
	// 	})
	// }
	var calendarEl = document.getElementById('calendar');
    var calendar;
	document.addEventListener('DOMContentLoaded', function() {
   

        calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          initialDate: '<?php echo date('Y-m-d') ?>',
          weekNumbers: true,
          navLinks: true, // can click day/week names to navigate views
          editable: false,
          selectable: true,
          nowIndicator: true,
          dayMaxEvents: true, // allow "more" link when too many events
          // showNonCurrentDates: false,
          events: []
        });
        calendar.render();
  });
	$('#id_no').change(function(){
		 calendar.destroy()
		 start_load()
		 $.ajax({
		 	url:'function-file/ajax.php?action=get_schedule',
		 	method:'POST',
		 	data:{id_no: $(this).val()},
		 	success:function(resp){
		 		if(resp){
		 			resp = JSON.parse(resp)
		 					var evt = [] ;
		 			if(resp.length > 0){
		 					Object.keys(resp).map(k=>{
		 						var obj = {};
		 							obj['title']=resp[k].title
		 							obj['data_id']=resp[k].id
		 							obj['data_station']=resp[k].station
		 							obj['data_description']=resp[k].description
		 							if(resp[k].is_repeating == 1){
		 							obj['daysOfWeek']=resp[k].dow
		 							obj['startRecur']=resp[k].start
		 							obj['endRecur']=resp[k].end
									obj['startTime']=resp[k].time_from
		 							obj['endTime']=resp[k].time_to
		 							}else{
		 							obj['start']=resp[k].schedule_date+'T'+resp[k].time_from;
		 							obj['end']=resp[k].schedule_date+'T'+resp[k].time_to;
		 							}	 							
		 							evt.push(obj)
		 					})
							 console.log(evt)
		 			}
		 				  calendar = new FullCalendar.Calendar(calendarEl, {
				          headerToolbar: {
				            left: 'prev,next today',
				            center: 'title',
				            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
				          },
				          initialDate: '<?php echo date('Y-m-d') ?>',
				          weekNumbers: true,
				          navLinks: true,
				          editable: false,
				          selectable: true,
				          nowIndicator: true,
				          dayMaxEvents: true, 
				          events: evt,
				          eventClick: function(e,el) {
							   var data =  e.event.extendedProps;
								uni_modal('View Schedule Details','view_schedule.php?id='+data.data_id)
							  }
				        });
		 			}
		 	},complete:function(){
		 		calendar.render()
		 		end_load()
		 	}
		})
	})

</script>