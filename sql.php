<?php
//-----------------Получаем из БД все данные об устройстве-------------------
    $result = mysqli_query($mysql, "SELECT * FROM DEVICE_TABLE WHERE DEVICE_ID = '$id'");
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о имени для этого устройства
        $Arr = mysqli_fetch_array($result);
        $device_name = $Arr['NAME'];
    } else { //Если в БД нет данных о имени для этого устройства
        $device_name = '?';
    }

    $result = mysqli_query($mysql, "SELECT * FROM TEMPERATURE_TABLE WHERE DEVICE_ID = '$id'");
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о температуре для этого устройства
        $Arr = mysqli_fetch_array($result);
        $temperature = $Arr['TEMPERATURE'];
        $temperature_dt = $Arr['DATE_TIME'];
    } else { //Если в БД нет данных о температуре для этого устройства
        $temperature = '?';
        $temperature_dt = '?';
    }

    $result = mysqli_query($mysql, "SELECT * FROM OUT_STATE_TABLE WHERE DEVICE_ID = '$id'");
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
        $Arr = mysqli_fetch_array($result);
        $out_state = $Arr['OUT_STATE'];
        $out_state_dt = $Arr['DATE_TIME'];
    } else { //Если в БД нет данных о реле для этого устройства
        $out_state = '?';
        $out_state_dt = '?';
    }

    $result = mysqli_query($mysql, "SELECT * FROM COMMAND_TABLE WHERE DEVICE_ID = '$id'");
    if (mysqli_num_rows($result) == 1) { //Если в БД есть данные о реле для этого устройства
        $Arr = mysqli_fetch_array($result);
        $com = $Arr['COMMAND'];
        $com_dt = $Arr['DATE_TIME'];
    } else { //Если в БД нет данных о реле для этого устройства
        $com = '?';
        $com_dt = '?';
    }
   if($out_state != $com){
     $out_state = $com;

     $date_today = date("Y-m-d H:i:s");
     $mysql->query("UPDATE OUT_STATE_TABLE SET OUT_STATE='$out_state', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        if (mysqli_affected_rows($mysql) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
        { //вставляем в таблицу строчку с данными о команде для устройства
            $result = mysqli_query($mysql, "INSERT OUT_STATE_TABLE SET DEVICE_ID='$id', OUT_STATE='1', DATE_TIME='$date_today'");
        }
   }

   $count_click_server += 1;


    //----------------------------------------------------------------------------------------
    
    //------Проверяем данные, полученные от пользователя---------------------
       
    /*while ($_POST['button_off'] && $temp == 0) {
        $temp = $temp -35;
        $date_today = date("Y-m-d H:i:s");
        $mysql->query("UPDATE TEMPERATURE_TABLE SET TEMPERATURE='$temp', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        
    }  */
   //UPDATE COMMAND_TABLE SET COMMAND='1' WHERE DEVICE_ID = '1'

/*function changehidden() {
    const button = document.getElementById('button_on'.$id.'');
    elem.style.color = newColor;
  }

   var button = document.getElementById('');
   button.addEventListener("click",function(){ //add a click event to button
       counter++; //incement the counter
       console.log(a);
       if(counter==5){
         button.style.display = 'none'; //hide if the clicks reached to 5
       }
    })
*/

/*    $date_minuta = date("Y-m-d H:i");

    $result = mysqli_query($mysql, "SELECT count(*) as coun FROM HYSTORY as h WHERE h.DATE_TIME LIKE '$date_minuta'% and h.DEVICE_ID='$id'");
    $Arr = mysqli_fetch_array($result);
    $count_click = $Arr['coun'];
    */

    /*пользователя блокируют при многом нажатии на разные кнопку
    а устройство, если куча обновлений и куча разных нажатий на одну кнопку
    */
    if ($_POST['button_on'.$id.'']) {
        $temp = $temperature + 4;
        if ($temp > $id * 50){
            $temp = $id * 50;
        }
        $date_today = date("Y-m-d H:i:s");

        $result = mysqli_query($mysql, "SELECT COUNT(*) as coun from HYSTORY as h WHERE h.DEVICE_ID = '$id'");
        $Arr = mysqli_fetch_array($result);
        $count = $Arr['coun'];

        $mysql->query("DROP TABLE IF EXISTS device_command");
        $mysql->query("CREATE TEMPORARY TABLE device_command as SELECT * from HYSTORY as h WHERE h.DEVICE_ID = '$id'");
        $mysql->query("ALTER table device_command add id_i int primary key auto_increment");


        $result = mysqli_query($mysql, "SELECT dc.out_state as command FROM device_command as dc WHERE dc.id_i = '$count'");
        $Arr = mysqli_fetch_array($result);
        $prev_command = $Arr['command'];

        //узнаем id пользователя
        $result = mysqli_query($mysql, "SELECT DISTINCT h.USER_ID From HYSTORY as h WHERE h.DEVICE_ID = '$id'");
        $Arr = mysqli_fetch_array($result);
        $user_id = $Arr['USER_ID'];

        if($prev_command == 0){
            //запихиваем в таблиц
            $result = mysqli_query($mysql, "INSERT HYSTORY SET USER_ID = '$user_id', DEVICE_ID = '$id', NAME = '$device_name', OUT_STATE = '1', DATE_TIME = '$date_today'");
            
        }else{
            //создать новую таблицу, куда заносятся данные при нажатии одной кнопки много раз
            $result = mysqli_query($mysql, "INSERT HISTORY_ONE_BUTTON SET USER_ID = '$user_id', DATE_TIME = '$date_today'");
        }



        $mysql->query("UPDATE COMMAND_TABLE SET COMMAND='1', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        $mysql->query("UPDATE TEMPERATURE_TABLE SET TEMPERATURE='$temp', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        if (mysqli_affected_rows($mysql) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
        { //вставляем в таблицу строчку с данными о команде для устройства
            $result = mysqli_query($mysql, "INSERT COMMAND_TABLE SET DEVICE_ID='$id', COMMAND='1', DATE_TIME='$date_today'");
        }
    }    

    if ($_POST['button_off'.$id.'']) {
        $date_today = date("Y-m-d H:i:s");
        $temp = $temperature - 4;
        if ($temp < $id * 10){
            $temp = $id * 10;
        }

        $result = mysqli_query($mysql, "SELECT COUNT(*) as coun from HYSTORY as h WHERE h.DEVICE_ID = '$id'");
        $Arr = mysqli_fetch_array($result);
        $count = $Arr['coun'];

        $mysql->query("DROP TABLE IF EXISTS device_command;");
        $mysql->query("CREATE TEMPORARY TABLE device_command as 
        SELECT * from HYSTORY as h WHERE h.DEVICE_ID = '$id';");
        $mysql->query("ALTER table device_command add id_i int primary key auto_increment;");



        $result = mysqli_query($mysql, "SELECT dc.out_state as command FROM device_command as dc WHERE dc.id_i = '$count';");
        $Arr = mysqli_fetch_array($result);
        $prev_command = $Arr['command'];

        //узнаем id пользователя
        $result = mysqli_query($mysql, "SELECT DISTINCT h.USER_ID From HYSTORY as h WHERE h.DEVICE_ID = '$id'");
        $Arr = mysqli_fetch_array($result);
        $user_id = $Arr['USER_ID'];

        if($prev_command == 1){
            //запихиваем в таблиц
            $result = mysqli_query($mysql, "INSERT HYSTORY SET USER_ID = '$user_id', DEVICE_ID = '$id', NAME = '$device_name', OUT_STATE = '0', DATE_TIME = '$date_today'");
            
        }else{
            //создать новую таблицу, куда заносятся данные при нажатии одной кнопки много раз
            $result = mysqli_query($mysql, "INSERT HISTORY_ONE_BUTTON SET USER_ID = '$user_id', DATE_TIME = '$date_today'");
        }



        $result = mysqli_query($mysql, "UPDATE COMMAND_TABLE SET COMMAND='0', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        $mysql->query("UPDATE TEMPERATURE_TABLE SET TEMPERATURE='$temp', DATE_TIME='$date_today' WHERE DEVICE_ID = '$id'");
        if (mysqli_affected_rows($mysql) != 1) //Если не смогли обновить - значит в таблице просто нет данных о команде для этого устройства
        { //вставляем в таблицу строчку с данными о команде для устройства
            $result = mysqli_query($mysql, "INSERT COMMAND_TABLE SET DEVICE_ID='$id', COMMAND='0', DATE_TIME='$date_today'");
        }
    }
    ?>