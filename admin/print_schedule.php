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
    #uni_modal .modal-footer{
        display:none;
    }
    .btn-dark {
        background-color: #343a40 !important;
    }
    .btn-dark:hover {
        background-color: #212529 !important;
    }
    table{
        width:100%;
        border-collapse:collapse;
    }
    tr,td,th{
        border:1px solid black
    }
    .text-center{
        text-align:center;
    }
    .text-right{
        text-align:right;
    }
    table thead tr td {
        text-align: center;
    }
</style>
<table>
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br>
    <center><h2>Schedules</h2></center>
    <thead>
        <colgroup>
            <col style="width: 3%">
            <col style="width: 20%">
            <col style="width: 10%">
            <col style="width: 7%">
            <col style="width: 5%">
            <col style="width: 10%">
            <col style="width: 10%"> 
            <col style="width: 10%">
            <col style="width: 5%">
            <col style="width: 5%">
        </colgroup>
        <tr>   
            <td class="text-center">#</td>
            <td class="text-center">Staff</td>
            <td class="text-center">Title</td>
            <td class="text-center">Schedule Type</td>
            <td class="text-center">Station</td>
            <td class="text-center">Schedule Date</td>
            <td class="text-center">Schedule End</td>
            <td class="text-center">Days of Week</td>
            <td class="text-center">Time From</td>
            <td class="text-center">Time To</td>
        </tr>
    </thead>

    <tr>
        <?php 
            $i = 1;
            $qry = $conn->query("SELECT schedules.*, GROUP_CONCAT(CONCAT(staff.lastname, ', ' , staff.firstname,' ', COALESCE(staff.middlename,'')) SEPARATOR ', ') as name FROM schedules 
            LEFT JOIN staff ON FIND_IN_SET(staff.id_no, schedules.id_no) > 0
            GROUP BY schedules.id
            ORDER BY schedules.date_created desc");
                while($row = $qry->fetch_assoc()):
        ?>
        <tr>
        <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
        <td data-title="Staff" class="td">
            <?php 
                echo ($row['id_no'] == 0) ? 'All' : $row['name'];
            ?>
        </td>
        <td><?php echo $row['title']?></td>
        <td data-title="Type" class="td">
                <?php 
                switch($row['schedule_type']){
                    case 1:
                        echo 'Work';
                        break;
                    case 2:
                        echo 'Meeting';
                        break;
                    case 3:
                        echo 'Others';
                        break;
                }
                ?>
        </td>   
        <td><?php echo $row['station']?></td>
        <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['schedule_date'])) ?></td>
        <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['schedule_end'] )) ?></td>	
        <td>
            <?php 
                $repeating_data = $row['repeating_data'];
                $data = json_decode($repeating_data, true);

                $days_of_week = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
                
                if (isset($data['dow'])) {
                    $dow = explode(',', $data['dow']);
                    $dow = array_map(function($day) use ($days_of_week) {
                        return $days_of_week[$day];
                    }, $dow);
                
                    echo implode(', ', $dow);
                } else {
                    echo "None";
                }
            ?>
        </td>			
        <td data-title="Start Time" class="td"><?php echo date("G:i:s", strtotime($row['time_from'])) ?></td>
        <td data-title="End Time" class="td"><?php echo date("G:i:s", strtotime($row['time_to'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </tr>
</table>