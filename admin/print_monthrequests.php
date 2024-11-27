<?php 
include('function-file/db_connect.php');
if(isset($_GET['month'])){
    $month = $_GET['month'];
    $month_num = date('m', strtotime("01-" . $month . "-2000"));
    $qry = $conn->query("SELECT * FROM `time-off-request` WHERE MONTH(from_date) = '$month_num'");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
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
    <center><img src="../admin/images/mcdologo-nobg.png" class="" style="width:100px;"></center><br><br>
    <center><h2>Time-off Requests of <?php echo $month ?></h2></center>
    <thead>
        <tr>
            <td>#</td>
            <td>Requested Time</td>
            <td>Staff</td>
            <td>Start Date</td>
            <td>End Date</td>
            <td>Time-Off Type</td>
            <td>Description</td>
            <td>Status</td>

        </tr>
    </thead>
    <tbody>
        <tr>
        <?php 
            $i = 1;
                $month = $_GET['month'];
                $month_num = date('m', strtotime("01-" . $month . "-2000"));
                $qry = $conn->query("SELECT *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as name from staff e inner join `time-off-request` pp on pp.id_no = e.id_no WHERE MONTH(from_date) = '$month_num' order by date_created desc");
                while($row = $qry->fetch_assoc()):
            ?>
            <tr>
                <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
                <td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                <td data-title="Staff" class="td"><?php echo $row['name'] ?></td>
                <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['from_date'])) ?></td>
                <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['to_date'])) ?></td>
                <td data-title="Time Off Type" class="td"><?php echo $row['leave_type'] ?></td>
                <td data-title="Description" class="td"><?php echo $row['description']?></td>
                <td data-title="Type" class="td text-center">
                    <?php 
                    switch($row['stats']){
                        case 'pending':
                            echo '';
                            break;
                        case 'approved':
                            echo '';
                            break;
                        case 'declined':
                            echo 'Declined';
                            break;
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tr>
    </tbody>
</table>