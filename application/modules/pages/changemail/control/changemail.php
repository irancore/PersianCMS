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
if($_SESSION['username'] == "")
{
	redirect("panel");
}
$username = $_SESSION['username'];
$cryptinstall="application/plugins/crypt/cryptographp.fct.php";
include_once $cryptinstall;
require ("application/config/config.php");
include_once("application/class/mysql.php");

if(isset($_POST['changemail'])) {
	$_username = strtoupper($_SESSION['username']);
	$oldemail = mysql_real_escape_string(strtoupper($_POST['oldemail']));
	$email = mysql_real_escape_string(strtoupper($_POST['email']));
	$confirmEmail = mysql_real_escape_string(strtoupper($_POST['confirmEmail']));
	$question = $_POST['question'];
	$answer = mysql_real_escape_string($_POST['answer']);
	
	$MySQL = new MySQL($db_auth, $mysql_username, $mysql_password, $mysql_host, $mysql_port);
	$MySQL->executeSQL("SELECT * FROM account WHERE username = '$username'");
	$id = $MySQL->arrayedResult['id'];
	
	$MySQL->database = $db_website;
	$MySQL->Connect($persistant);
	$MySQL->executeSQL("SELECT * FROM account_data WHERE id = $id");
	$_question = $MySQL->arrayedResult['question'];
	$_answer = $MySQL->arrayedResult['answer'];
	
	$MySQL->database = $db_auth;
	$MySQL->Connect($persistant);
	$MySQL->executeSQL("SELECT * FROM account WHERE email = '$email'");
	$mail_num_rows = $MySQL->records;
	
	$result = 1;
	if($_POST['oldemail'] == "" || $_POST['email'] == "" || $_POST['confirmEmail'] == "" || $_POST['question'] == "" || $_POST['answer'] == "")
	   $result = -1;
	   
	if (!chk_crypt($_POST['captcha']))
		$result = -2;
	
	if($mail_num_rows != 0)
		$result = -3;
		
	if($email != $confirmEmail)
		$result = -4;
		
	if (!preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email))
		$result = -5;
		
	if($_question != $question)
		$result = -6;
	
	if($_answer != $answer)
		$result = -7;
		
	if($result == 1){
		$MySQL->executeSQL("UPDATE account SET email = '$email' WHERE username = '$_username'");
		$result = 0;
	}
	if($result == 1 && is_numeric($result)) {
		//redirect("panel");
	} else {
		switch($result) {
			case 0:  $message = '<p class="success"><a href="panel">عملیات با موفقیت انجام شد.</a></p>'; break;
			case -1: $message = '<p class="error">تمامی فیلد ها را پر کنید.</p>'; break;
			case -2: $message = '<p class="error">تصوير امنيتي را به درستي وارد نکرديد.</p>'; break;
			case -3: $message = '<p class="error">این ایمیل قبلا استفاده شده است.</p>'; break;
			case -4: $message = '<p class="error">ایمیل با هم مطابقت ندارد.</p>'; break;
			case -5: $message = '<p class="error">ایمیل وارد شده معتبر نمیباشد.</p>'; break;
			case -6: $message = '<p class="error">پرسش شما اشتباه میباشد.</p>'; break;
			case -7: $message = '<p class="error">پاسخ شما اشتباه میباشد.</p>'; break;
			default: $message = '<p class="error">خطایی نامعلوم رخ داده است لطفا دوباره سعی کنید. اگر باز هم دچار این خطا شدید با مدیریت تماس بگیرید.</p>'; break;
		}
	}
}
?>