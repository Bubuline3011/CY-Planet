<?php
session_start();
session_destroy();
header("Location: acceuil.php");
exit();
?>
