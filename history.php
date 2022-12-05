<?php
include "db.php";

$id_device = $_POST['varname'];

echo'<p>Тут история об этом устройстве '.$id_device.'</p><br>';


$result = mysqli_query($mysql, "SELECT COUNT(*) as coun FROM HYSTORY as h WHERE h.DEVICE_ID = '$id_device';");
$Arr = mysqli_fetch_array($result);
$count = $Arr['coun'];

$mysql->query("DROP TABLE IF EXISTS device_command");
$mysql->query("CREATE TEMPORARY TABLE device_command as SELECT * from HYSTORY as h JOIN USER_TABLE as u ON h.USER_ID = u.ID WHERE h.DEVICE_ID = '$id_device'");
$mysql->query("ALTER table device_command add id_i int primary key auto_increment");

echo'
<table border=1>
<tr>
<td width=40px> NAME_USER
</td>
<td width=40px> ID_USER
</td>
<td width=40px> ID_DEVICE
</td>
<td width=60px> NAME_DEVICE
</td>
<td width=40px> COMMAND
</td>
<td width=100px> DATA_TIME
</td>
</tr>
';
for($i = 1; $i <= $count; $i ++){
    $result = mysqli_query($mysql, "SELECT * FROM device_command as dc WHERE dc.id_i = '$i'");
    $Arr = mysqli_fetch_array($result);
    $name_user = $Arr['USER_NAME'];
    $id_user = $Arr['USER_ID'];
    $name_device = $Arr['NAME'];
    $command = $Arr['OUT_STATE'];
    $data_time = $Arr['DATE_TIME'];
    echo'
    <tr>
    <td width=40px>'.$name_user.'
    </td>
    <td width=40px>'.$id_user.'
    </td>
    <td width=40px>'.$id_device.'
    </td>
    <td width=60px>'.$name_device.'
    </td>
    <td width=40px>'.$command.'
    </td>
    <td width=100px>'.$data_time.'
    </td>
    </tr>
    ';
}
echo'
</table>
';

echo'
<form method=POST action="index.php">
<button>Вернуться обратно</button>
</form><br>';
?>