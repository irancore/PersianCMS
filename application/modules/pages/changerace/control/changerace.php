<?php
/*
	This is a Full Featured CMS for all.
	You may also have problems with this. Please report issues. we will fix that soon.
	Copyright (C) 2015  AmirHosein.L Email:AmirOperator@gmail.com

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if($_SESSION['username'] == ""){
    redirect("login");
}
$cryptinstall="application/plugins/crypt/cryptographp.fct.php";
include_once $cryptinstall;
require ("application/config/config.php");
include_once("application/class/mysql.php");
$username = $_SESSION['username'];
$MySQL = new MySQL($db_auth, $mysql_username, $mysql_password, $mysql_host, $mysql_port);
$MySQL->executeSQL("SELECT * FROM account WHERE username = '$username';");
$id = $MySQL->arrayedResult['id'];

$MySQL->database = $db_website;
$MySQL->Connect($persistant);
$MySQL->executeSQL("SELECT * FROM account_data WHERE id = $id;");
$amount = $MySQL->arrayedResult['dp'];

$MySQL->executeSQL("SELECT * FROM service_name WHERE name = 'changerace';");
$dp = $MySQL->arrayedResult['dp'];

$MySQL->database = $db_characters;
$MySQL->Connect($persistant);
$character = $_SESSION['main_char'];
$MySQL->executeSQL("SELECT * FROM characters WHERE name = '$character';");
$at_login = $MySQL->arrayedResult['at_login'];

if(isset($_POST['changerace'])){
    $result = 1;
    if($character == "")
        $result = -1;
    
    if (!chk_crypt($_POST['captcha']))
        $result = -2;
        
    if($dp > $amount)
        $result = -3;
        
    if($at_login != 0)
        $result = -4;
        
    if($result == 1){
        $MySQL->executeSQL("UPDATE characters SET at_login = '128' WHERE name = '$character';");
        $MySQL->database = $db_website;
        $MySQL->Connect($persistant);
        $MySQL->executeSQL("UPDATE `account_data` SET dp = dp - $dp WHERE `id` = $id;");
        $result = 0;
    }
    if($result == 1 && is_numeric($result)) {
    } else {
        switch($result){	
            case 0:  $message = '<p class="success"><a href="panel">عملیات با موفقیت انجام شد.</a></p>'; break;
            case -1: $message = '<p class="error">هیرو خود را از نوار کناری سمت راست انتخاب کنید</p>'; break;
            case -2: $message = '<p class="error">تصوير امنيتي را به درستي وارد نکرديد</p>'; break;
            case -3: $message = '<p class="error">موجودی شما کافی نمیباشد</p>'; break;
            case -4: $message = '<p class="error">شما قبلا یک سرویسی را روی این هیرو اعمال کرده اید و از آن استفاده نکرده اید.</p>'; break;
            default: $message = '<p class="error">خطایی نامعلوم رخ داده است لطفا دوباره سعی کنید. اگر باز هم دچار این خطا شدید با مدیریت تماس بگیرید.</p>'; break;
        }
    }
}
?>