<?php
include 'includes/session_handler.php'; 
include 'includes/header.php';
include 'includes/db_config.php'; 

if (!isUserLoggedIn()) {
    redirectToLogin();
}

$success_message = '';
$error_message = '';

$id_usu = $_SESSION['id_usu'] ?? null; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Conn();
    $conn = $db->Conexion();

    $nombre_cita = htmlspecialchars(trim($_POST['nombre_cita']));
    $profesional_id = intval($_POST['profesional_cita']); 
    $fecha_cita = htmlspecialchars(trim($_POST['fecha_cita']));
    $hora_cita = htmlspecialchars(trim($_POST['hora_cita']));
    $tipo_cita = htmlspecialchars(trim($_POST['tipo_cita']));
    $motivo_cita = htmlspecialchars(trim($_POST['motivo_cita']));
    $area_cita = "Atención Médica"; // ✅ NUEVO

    if (empty($nombre_cita) || empty($profesional_id) || empty($fecha_cita) || empty($hora_cita) || empty($tipo_cita)) {
        $error_message = "Todos los campos obligatorios deben ser llenados.";
    } elseif ($id_usu === null) {
        $error_message = "No se pudo identificar al usuario. Por favor, inicia sesión de nuevo.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO citas 
                (id_usu, nom_cita, id_profe, fechaH_cita, hora_cita, tipo_cita, area_cita, motivo_cita)
                VALUES 
                (:id_usu, :nom_cita, :id_profe, :fechaH_cita, :hora_cita, :tipo_cita, :area_cita, :motivo_cita)");

            $stmt->bindParam(':id_usu', $id_usu, PDO::PARAM_INT);
            $stmt->bindParam(':nom_cita', $nombre_cita);
            $stmt->bindParam(':id_profe', $profesional_id, PDO::PARAM_INT);
            $stmt->bindParam(':fechaH_cita', $fecha_cita);
            $stmt->bindParam(':hora_cita', $hora_cita);
            $stmt->bindParam(':tipo_cita', $tipo_cita);
            $stmt->bindParam(':area_cita', $area_cita); // ✅ NUEVO
            $stmt->bindParam(':motivo_cita', $motivo_cita);

            if ($stmt->execute()) {
                $success_message = "¡Cita médica solicitada con éxito! Nos pondremos en contacto contigo pronto.";
            } else {
                $error_message = "Hubo un error al solicitar la cita médica. Inténtalo de nuevo.";
            }
        } catch (PDOException $e) {
            $error_message = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<section class="form-section">
    <div class="container">
        <h2>Solicitar Cita de Atención Médica</h2>
        <p style="text-align: center; margin-bottom: 40px; color: #555;">Estamos aquí para brindarte atención médica de calidad, de forma segura y confidencial.</p>

        <div class="professional-grid">
            <div class="professional-card">
                <img src="img/Luis_ramos.jpg" alt="Dr. Luis Ramos" class="avatar">
                <h3>Dr. Luis Ramos</h3>
                <p>Médico General</p>
                <p>Especialista en consultas generales y chequeos.</p>
                <a href="#formulario" class="btn btn-primary">Reservar Cita</a>
            </div>
            <div class="professional-card">
                <img src="img/Carla_flores.jpg" alt="Dra. Carla Flores" class="avatar">
                <h3>Dra. Carla Flores</h3>
                <p>Medicina Familiar</p>
                <p>Atención a toda la familia, prevención y cuidados.</p>
                <a href="#formulario" class="btn btn-primary">Reservar Cita</a>
            </div>
            <div class="professional-card">
                <img src="img/pedro_silva.jpg" alt="Dr. Pedro Silva" class="avatar">
                <h3>Dr. Pedro Silva</h3>
                <p>Internista</p>
                <p>Especialista en enfermedades internas y diagnóstico clínico.</p>
                <a href="#formulario" class="btn btn-primary">Reservar Cita</a>
            </div>
        </div>

        <div class="form-container" id="formulario" style="margin-top: 50px;">
            <h2>Formulario de Cita Médica</h2>

            <?php if (!empty($success_message)): ?>
                <p style="color: green; text-align: center; margin-bottom: 15px;"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="nombre_cita">Tu Nombre:</label>
                    <input type="text" id="nombre_cita" name="nombre_cita" placeholder="Nombre" required
                           value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="profesional_cita">Médico Preferido:</label>
                    <select id="profesional_cita" name="profesional_cita" required>
                        <option value="">Selecciona un médico</option>
                        <option value="4">Dr. Luis Ramos</option>
                        <option value="5">Dra. Carla Flores</option>
                        <option value="6">Dr. Pedro Silva</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_cita">Fecha Preferida:</label>
                    <input type="date" id="fecha_cita" name="fecha_cita" required>
                </div>
                <div class="form-group">
                    <label for="hora_cita">Hora Preferida:</label>
                    <input type="time" id="hora_cita" name="hora_cita" required>
                </div>
                <div class="form-group">
                    <label for="tipo_cita">Tipo de Cita:</label>
                    <select id="tipo_cita" name="tipo_cita" required>
                        <option value="presencial">Presencial</option>
                        <option value="videollamada">Videollamada</option>
                        <option value="telefonica">Telefónica</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="motivo_cita">Motivo de la Cita (Opcional):</label>
                    <textarea id="motivo_cita" name="motivo_cita" rows="4" placeholder="Describe brevemente tu motivo."></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Solicitar Cita Médica</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
