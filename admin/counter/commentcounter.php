<?php
include('../function-file/db_connect.php');
$sql = "UPDATE `comments` SET notifications = 'read'";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>