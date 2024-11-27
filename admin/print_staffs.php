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
    <center><h2>Staffs</h2></center>
    <thead>
        <tr>
            <td>#</td>
            <td>ID No</td>
            <td>Position</td>
            <td>Name</td>
            <td>Age</td>
            <td>Gender</td>
            <td>Birthdate</td>
            <td>Email</td>
            <td>Contact No.</td>
            <td>Date Joined</td>
        </tr>
    </thead>
    <tbody>
    <?php 
        $i = 1;
        $staff =  $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name from staff order by concat(lastname,', ',firstname,' ',middlename) asc");
        while($row= $staff->fetch_assoc()):
        ?>
        <tr>
            <td data-title= "#" class="td text-center"><?php echo $i++ ?></td>
            <td data-title= "ID No." class="td">
                    <p><?php echo $row['id_no'] ?></p> 
            </td>
            <td data-title= "Position" class="td">
                    <p><?php echo ucwords($row['position']) ?></p>
            </td>
            <td data-title= "Name" class="td">
                    <p><?php echo ucwords($row['name']) ?></p>
            </td>
            <td data-title= "Age" class="td">
                    <p><?php echo ucwords($row['age']) ?></p>
            </td>
            <td data-title= "Gender" class="td">
                    <p><?php echo $row['gender'] ?></p>
            </td>
            <td data-title= "Birthdate" class="td">
                    <p><?php echo $row['birthdate'] ?></p>
            </td>
            <td data-title= "Email" class="td">
                    <p><?php echo $row['email'] ?></p>
            </td>
            <td data-title= "Contact No." class="td">
                    <p><?php echo $row['contact'] ?></p>
            </td>
            <td data-title= "Date Joined" class="td">
                    <p><?php echo $row['datejoined'] ?></p>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>