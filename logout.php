<?php
session_start();        // Inicia sesión
session_unset();        // Limpia variables de sesión
session_destroy();      // Destruye sesión

header("Location: login.php"); // Redirige al formulario de login
exit();
?>
