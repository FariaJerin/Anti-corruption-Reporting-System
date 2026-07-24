<?php
session_start();
session_destroy();
header("Location: universal-login.php");
exit;