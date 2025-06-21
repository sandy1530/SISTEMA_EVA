<?php
include 'includes/session_handler.php'; 
include 'includes/db_config.php'; 

$db = new Conn();
$conn = $db->Conexion();

$error_message = '';

if (isUserLoggedIn()) {
    redirectToDashboard();
}

// Lógica para procesar el formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($conn) {
        $stmt = $conn->prepare("SELECT id_usu, nom_usu, email_usu, contra_usu FROM Usuarios WHERE email_usu = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['contra_usu'])) {
                // Iniciar sesión
                $_SESSION['loggedin'] = true;
                $_SESSION['id_usu'] = $user['id_usu'];
                $_SESSION['nom_usu'] = $user['nom_usu'];
                $_SESSION['email_usu'] = $user['email_usu'];

                redirectToDashboard(); // Redirige después de un login exitoso
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "No existe una cuenta con ese correo electrónico.";
        }
    } else {
        $error_message = "Error de conexión a la base de datos.";
    }
}
?>

<?php include 'includes/header.php'; ?>

    <section class="form-section">
        <div class="container">
            <div class="form-container">
                <h2>Inicia Sesión en EVA</h2>
                <?php if (!empty($error_message)): ?>
                    <p class="error-message" style="color: red; text-align: center;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
                <form action="login.php" method="POST"> <div class="form-group">
                        <label for="email_login">Correo Electrónico:</label>
                        <input type="email" id="email_login" name="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password_login">Contraseña:</label>
                        <input type="password" id="password_login" name="password" placeholder="Tu contraseña" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </div>
                    <p style="text-align: center; margin-top: 20px;">¿Olvidaste tu contraseña? <a href="#">Recuperar Contraseña</a></p>
                    <p style="text-align: center; margin-top: 10px;">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                </form>
            </div>
        </div>
    </section>


</body>
</html>
