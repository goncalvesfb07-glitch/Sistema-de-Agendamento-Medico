<?php
session_start();

if(isset($_session['usuario'])) {
    header("localtion: dashboard.php");
    exit;
}
?>
