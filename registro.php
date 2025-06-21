<?php
include 'includes/header.php';
include 'includes/db_config.php';

$db = new Conn();
$conn = $db->Conexion();

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nom_usu'];
    $apellidos = $_POST['ape_usu'];
    $telefono = $_POST['tele_usu'];
    $email = $_POST['email_usu'];
    $password = $_POST['contra_usu'];
    $confirm_password = $_POST['confirm_password_usu'];
    $anonimo_perfil = isset($_POST['anoni_usu']) ? 1 : 0;

    // Validaciones
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Todos los campos obligatorios deben ser llenados.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El formato del correo electrónico no es válido.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $error_message = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($conn) {
            $stmt_check = $conn->prepare("SELECT id_usu FROM Usuarios WHERE email_usu = :email");
            $stmt_check->bindParam(':email', $email);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $error_message = "Ya existe una cuenta con este correo electrónico.";
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO Usuarios (nom_usu, ape_usu, tele_usu, email_usu, contra_usu, anoni_usu)
                                               VALUES (:nom_usu, :ape_usu, :tele_usu, :email_usu, :contra_usu, :anoni_usu)");

                $stmt_insert->bindParam(':nom_usu', $nombre);
                $stmt_insert->bindParam(':ape_usu', $apellidos);
                $stmt_insert->bindParam(':tele_usu', $telefono);
                $stmt_insert->bindParam(':email_usu', $email);
                $stmt_insert->bindParam(':contra_usu', $hashed_password);
                $stmt_insert->bindParam(':anoni_usu', $anonimo_perfil, PDO::PARAM_INT);

                if ($stmt_insert->execute()) {
                    header("Location: login.php?registered=true");
                    exit();
                } else {
                    $error_message = "Error al registrar el usuario.";
                }
            }
        }
    }
}
?>

<section class="form-section">
    <div class="container">
        <div class="form-container">
            <h2>Crea una cuenta en EVA</h2>

            <?php if (!empty($success_message)): ?>
                <p style="color: green; text-align: center; margin-bottom: 15px;"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre_registro">Nombre:</label>
                    <input type="text" id="nombre_registro" name="nom_usu" placeholder="Tu nombre" required value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="apellidos_registro">Apellidos:</label>
                    <input type="text" id="apellidos_registro" name="ape_usu" placeholder="Tus apellidos" required value="<?php echo isset($apellidos) ? htmlspecialchars($apellidos) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="telefono_registro">Teléfono (Opcional):</label>
                    <input type="tel" id="telefono_registro" name="tele_usu" placeholder="Ej: +51987654321" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email_registro">Correo Electrónico:</label>
                    <input type="email" id="email_registro" name="email_usu" placeholder="tu@correo.com" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password_registro">Contraseña:</label>
                    <input type="password" id="password_registro" name="contra_usu" placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password_registro">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password_registro" name="confirm_password_usu" placeholder="Repite tu contraseña" required>
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="anonimo_perfil" name="anoni_usu" <?php echo isset($anonimo_perfil) && $anonimo_perfil ? 'checked' : ''; ?>>
                    <label for="anonimo_perfil">Prefiero que mi perfil sea anónimo por defecto (para ciertas funciones)</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Registrarme</button>
                </div>

                <p style="text-align: center; margin-top: 20px;">¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión aquí</a></p>
            </form>
        </div>
    </div>
</section>


