<?php
session_start();

unset($_SESSION['usuario']);
unset($_SESSION['email']);
unset($_SESSION['id']);

// Eliminar cookies
setcookie("usuario", "", time() - 3600, "/");
setcookie("id", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");

header("Location: index.php");
die();
