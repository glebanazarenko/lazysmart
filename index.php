<?php

    //--------------------------Настройки подключения к БД-----------------------
    include "db.php";
    //----------------------------------------------------------------------------------------
    $user_id = 1;
    include "count_id.php";

//создание временной таблиццы, где только устройства определененого человека
$mysql->query("DROP TABLE IF EXISTS user_device;");
$mysql->query("CREATE TEMPORARY TABLE user_device as SELECT * from DEVICE_TABLE as d JOIN USER_TABLE as u ON d.USER_ID = u.ID WHERE d.USER_ID = '$user_id'");
$mysql->query("ALTER table user_device add id_i int primary key auto_increment;");


echo'
     <!DOCTYPE HTML>
    <html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>MyApp</title>
    <script src="/UpdateScript.js"> </script>
    </head>
    <body>';
    for($i = 1; $i <= $count_id; $i++){
        $result = mysqli_query($mysql, "SELECT ud.DEVICE_ID from user_device as ud WHERE ud.id_i = '$i';");
        $Arr = mysqli_fetch_array($result);
        $id = $Arr['DEVICE_ID'];
        include "sql.php";
        echo'
        <p>'.$i.'</p>
        ';

        $date_minuta = date("Y-m-d H:i");
        $result = mysqli_query($mysql, "SELECT count(*) as coun from HYSTORY as h where h.DATE_TIME LIKE('$date_minuta%') and h.DEVICE_ID = '$i';");
        $Arr = mysqli_fetch_array($result);
        $count_click_user = $Arr['coun'];
        if($count_click_server >= 10 * $count_id){
            Echo'Обнаружена множестванная атака запросов на сервер.<br>
            В качестве защиты вам будет ограничен доступ к данным на время';
            break;
        }
        if($count_click_user >= 5){
            echo'Вы много раз обращались к этому устройству.<br>
            Не делайте так. Лучше отдохните<br>
            И обновите страницу, если отдохнули)<br>
            Рекомендация: отдых от 1 минуты<br>';
        }else{
        echo'
        <table>
        <tr>
        <td width=100px> Устройство:
        </td>
        <td width=40px>'.$device_name.'
        </td>
        </tr>
        </table>
        <table border=1>
        <tr>
        <td width=100px> Tемпература
        </td>
        <td width=40px>'.$temperature.'
        </td>
        <td width=150px>'.$temperature_dt.'
        </td>
        </tr>
        <tr>
        <td width=100px> Реле
        </td>
        <td width=40px>'.$out_state.'
        </td>
        <td width=150px>'.$out_state_dt.'
        </td>
        </tr>
        </table>
        ';
        if($out_state == 0){
            echo'<form method=POST>
            <button formmethod=POST name=button_on'.$id.' value=1>Включить реле</button>
            </form>';
        }else{
            echo'<form method=POST>
            <button formmethod=POST name=button_off'.$id.' value=1>Выключить реле</button>
            </form>';
        }
        echo'
        <form method=POST action="history.php">
        <input type="hidden" name="varname" value="'.$id.'">
        <button formmethod=POST name=history>История устройсва</button>
        </form><br>
        Текущая минута: '.$date_minuta.'<br>
        Кол-во кликов от юзера: '.$count_click_user.'<br>
        Кол-во кликов от сервера: '.$count_click_server.'';
        }
    }
echo'
    </body>
    </html>';

    ?>