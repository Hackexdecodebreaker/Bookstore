<?php
$servername="localhost";
$username="ceiscy_aj513";
$password="_@I%8WFGFp+2";
$dbname="ceiscy_aj513";

$conn=mysqli_connect($servername,$username,$password,$dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}