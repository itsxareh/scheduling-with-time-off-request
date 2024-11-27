<?php
include("../admin/function-file/db_connect.php");
?>
<style> button:hover {
  background-color: rgba(180, 0, 0, 0.8);
  border-radius: 5px;
  
}
  .noti > ul > li {
    position: relative;
    display: inline-block;
  }
  .noti> ul > li .dropdown-check {
    display: none;
  }
  .noti > ul > li .dropdown-check:checked ~ .dropdown {
    visibility: visible;
    opacity: 1;
  }
  .noti > ul > li > .count {
    position: absolute;
    right: 7px;
    top: 1px;
    border-radius: 50%;
    font-size: 0.8rem;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    color: #ff0000;
    width: 12px;
    height: 12px;
    cursor: default;
  }
  .noti > ul > li > a {
    color: #fff;
    font-size: 1.5rem;
    display: inline-block;
  }
  .noti > ul > li > a > label{
    cursor: pointer;
  }
  .noti ul li .dropdown {
    position: absolute;
    top: 100%;
    left: -150px;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 1rem;
    visibility: hidden; 
    opacity: 0;
    width: 225px;
    transition: 0.3s;
  }
  .noti ul li .dropdown li {
    margin-bottom: 1rem;
    border-bottom: 1px solid #ccc;
    padding-bottom: 1rem;
  }
  .noti ul li .dropdown li a:hover {
    color: black;
    text-decoration: none;
}
  .noti ul li .dropdown li:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
  }
  .menu-btn {
    position: absolute;
    left: 5px;
    width: 20px;
    display: none;
    cursor: pointer;
    z-index: 10;
  }
  .system {
    white-space: nowrap;
    color: white;
  }
  .navibar { 
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .topbar {
    width: 100%;
    align-items: center;
    justify-content: space-between;
  }
  .title-logo{
    max-height: 4rem;
    display: flex;
    align-items: center;
    width: 100%;
    z-index: 10;
  }
  .title-logo .logo img{
    min-width: 3.5rem;
    max-height: 3.5rem;
    min-height: 3.5rem; 
  }
  .top-bar {
    padding: 0px !important;
  }
  @media (max-width: 748px){
    .menu-btn {
      display: block;
    }
    .top-bar {
    padding-left: 15px !important;
    }
    .navibar {
      position: absolute;
      top: -500px;
      left: -15px;
      right: 0;
      width: 100vw;
      background-color: #ff0000;
      display: flex;
      flex-direction: column;
      align-items:center;
      border-bottom-right-radius: 50px;
      border-bottom-left-radius: 50px;
      transition: all .50s ease;
      
    }
    .navibar li a{
      display: block;
      transition: all .50s ease;
    }
    .navibar.open {
      top: 110%;
    }
  }
</style>

<nav class="navbar navbar-light fixed-top bg-primary" style="padding:0;min-height: 3.5rem">
  <div class="container-fluid top-bar mt-2 mb-2">
    <img class="menu-btn" src="../admin/images/menu-icon.png">
      <div class="topbar col-lg-12 items-center grid grid-cols-4 gap-2">
        <div class="title-logo">
            <div class="logo">
              <a href="index.php?page=home"><img class="" src="../admin/images/mcdologo.jpg"></a>
            </div>
            <div class="title">
              <a class="system" href="index.php?page=home">Staff Schedules</a>
            </div>
        </div>
        <div class="col-span-2 flex items-center justify-center">
          <div class="flex whitespace-nowrap">
            <ul class="navibar">
              <li><a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a></li>
              <li><a href="index.php?page=announcement" class="nav-item nav-announcement"><span class='icon-field'><i class="fa fa-scroll"></i></span> Announcement</a></li>
              <li><a href="index.php?page=schedule" class="nav-item nav-schedule"><span class='icon-field'><i class="fa fa-calendar-day"></i></span> Schedule</a></li>
              <li><a href="index.php?page=payroll" class="nav-item nav-payroll"><span class='icon-field'><i class="fa fa-money-check"></i></span> Payslip</a></li>
              <li><a href="index.php?page=view_time_off" class="nav-item nav-view_time_off"><span class='icon-field'><i class="fa fa-calendar-times"></i></span> Time-off</a></li>
            <ul>
          </div> 
        </div> 
        <div class="flex flex-1 items-center justify-end noti">
            <ul>
              <li>
                <a href="#" id="notifications"><label for="check"><i class="fa fa-bell mr-3" aria-hidden="true"></i></label>
                </a>
                <input type="checkbox" class="dropdown-check" id="check"/>
                <ul class="dropdown">
                  <?php
                  $id_no = $_SESSION['login_id_no'];
                  $sql = "SELECT p.id_no, p.notifications, p.date_created, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname from staff e inner join `payslip` p on p.id_no = e.id_no WHERE p.notifications = 'payslip' and p.id_no ='$id_no' 
                  union SELECT t.stats, t.notifications, t.time_remark, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname from staff e inner join `time-off-request` t on t.id_no = e.id_no WHERE t.notifications = 'request1' and t.id_no = '$id_no' 
                  union SELECT s.id_no, s.notifications, a.date_created, CONCAT(s.lastname, ', ' , s.firstname,' ', COALESCE(s.middlename,'')) as fullname from staff s inner join announcement a WHERE s.notifications = 'announcement' AND s.id_no = '$id_no'  
                  union SELECT c.schedule_date, f.notification,  c.date_created, CONCAT(f.lastname, ', ' , f.firstname,' ', COALESCE(f.middlename,'')) as fullname from schedules c left join staff f on find_in_set('$id_no', c.id_no) > 0 WHERE f.id_no = '$id_no' and f.notification = 'schedule'
                  union SELECT c.schedule_date, f.notification,  c.date_created, CONCAT(f.lastname, ', ' , f.firstname,' ', COALESCE(f.middlename,'')) as fullname from staff f inner join schedules c WHERE f.notification = 'schedule' AND c.id_no = '0' and f.id_no = '$id_no'
                  union SELECT comments.user_id, staff.notificationa, max(comments.date_created) as date_created, users.name FROM staff JOIN comments ON staff.notificationa = 'comment' AND staff.id_no = '$id_no' JOIN users ON comments.user_id = users.id WHERE staff.notificationa = 'comment' GROUP BY comments.user_id
                  ORDER BY date_created DESC LIMIT 5;";
                  $res = mysqli_query($conn, $sql);   
                    if (mysqli_num_rows($res) < 1){
                      echo "No notifications.";
                    } else {
                      foreach ($res as $item){
                        if ($item['notifications'] == 'payslip'){
                          ?><li><a class="payslip" href="./?page=payroll"><?php echo ucwords($item["fullname"]);?>, your Payslip can be viewed and printed as PDF now.<br><small><?php echo $item["date_created"];?></small></a></li><?php
                        } if ($item['notifications'] == 'request1'){
                          ?><li><a class="time-off" href="./?page=view_time_off"><?php echo ucwords($item["fullname"]);?>, your time-off request status has been <?php echo $item["id_no"]?>.<br><small><?php echo $item["date_created"];?></small></a></li><?php
                        } if ($item['notifications'] == 'announcement'){
                          ?><li><a class="announcement" href="./?page=announcement"><?php echo ucwords($item["fullname"]);?>, a new announcement has arrived.<br><small><?php echo $item["date_created"];?></small></a></li><?php
                        } if ($item['notifications'] == 'schedule'){
                          ?><li><a class="schedule" href="./?page=schedule"><?php echo ucwords($item["fullname"]);?>, a new schedule on <?php echo $item["id_no"]?>.<br><small><?php echo $item["date_created"];?></small></a></li><?php
                        } if ($item['notifications'] == 'comment'){
                          ?><li><a class="comment" href="./?page=announcement"><?php echo ucwords($item["fullname"])?> commented on announcement post.<br><small><?php echo $item["date_created"];?></small></a></li><?php
                        }
                      }
                    }
                  ?>
                </ul>
                <span class="count"><?php echo mysqli_num_rows($res); ?></span>
              </li>
            </ul>
          <div class="dropdown">
            <a href="#" class="text-white dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i><?php echo $_SESSION['login_lastname'] ?></i> </a>
            <div class="dropdown-menu" aria-labelledby="account_settings">
              <a class="dropdown-item" href="../admin/function-file/ajax.php?action=logout2"><i class="fa fa-power-off"></i> Logout</a>
            </div>
          </div>
        </div>
      </div>
  </div>
</nav>

<script>
  const menu = document.querySelector('.menu-btn');
  const navbar = document.querySelector('.navibar');
  
  menu.onclick = () => {
    menu.classList.toggle('bx-x')
    navbar.classList.toggle('open')
  }
  $(".time-off").on("click", function(){
    $.ajax({
      url: "../admin/counter/leavecounter.php",
      success: function(resp){
        console.log(resp);
      }
    })
  })
  $(".payslip").on("click", function(){
    $.ajax({
      url: "../admin/counter/payslipcounter.php",
      success: function(resp){
        console.log(resp);
      }
    })
  })
  $(".announcement").on("click", function(){
    $.ajax({
      url: "../admin/counter/announcementcounter.php",
      success: function(resp){
        console.log(resp);
      }
    })
  })
  $(".comment").on("click", function(){
    $.ajax({
      url: "../admin/counter/staffcommentcounter.php",
      success: function(resp){
        console.log(resp);
      }
    })
  })
  $(".schedule").on("click", function(){
    $.ajax({
      url: "../admin/counter/schedulecounter.php",
      success: function(resp){
        console.log(resp);
      }
    })
  })
  $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
  
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>