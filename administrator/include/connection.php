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
  if (!isset($_SESSION))
      session_start();
  $_SESSION['connection_setup'] = mysql_connect($mysql_host . ':' . $mysql_port, $mysql_username, $mysql_password) or die("ارتباط با سرور قطع شد"); // ایجاد کانکشن با دیتابیس

?>