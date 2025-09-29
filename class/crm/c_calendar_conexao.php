<?php
/*
 * @author Jhon Kenedy
 * @pagina desenvolvida usando FullCalendar,
 */

define('HOST', HOSTNAME);
define('USER', DB_USER);
define('PASS', DB_PASSWORD);
define('DBNAME', DB_NAME);

$conn = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME . ';', USER, PASS);
