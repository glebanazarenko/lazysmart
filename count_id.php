<?php
   $result = mysqli_query($mysql, "SELECT Count(*) as count from DEVICE_TABLE as d JOIN USER_TABLE as u ON d.USER_ID = u.ID WHERE d.USER_ID = '$user_id'");
   $Arr = mysqli_fetch_array($result);
   $count_id = $Arr['count'];
?>