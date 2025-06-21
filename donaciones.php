<?php
include 'includes/header.php';
include 'includes/db_config.php'; 

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $db = new Conn();
    $conn = $db->Conexion();

    $monto_dona = floatval($_POST['donation_amount'] ?? 0);
    $mon_dona = $monto_dona; 
    $nomb_dona = htmlspecialchars(trim($_POST['nombre_donante'] ?? ''));
    $email_dona = htmlspecialchars(trim($_POST['email_donante'] ?? ''));
    $metodoP_dona = htmlspecialchars(trim($_POST['metodo_pago'] ?? ''));
    $anoni_dona = isset($_POST['anonimo_donacion']) ? 1 : 0;

    // Validación mínima
    if ($monto_dona <= 0 || empty($metodoP_dona)) {
        $error_message = "Por favor completa el monto y selecciona un método de pago.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO donaciones 
                (monto_dona, mon_dona, nomb_dona, email_dona, metodoP_dona, anoni_dona)
                VALUES (:monto_dona, :mon_dona, :nomb_dona, :email_dona, :metodoP_dona, :anoni_dona)");

            $stmt->bindParam(':monto_dona', $monto_dona);
            $stmt->bindParam(':mon_dona', $mon_dona);
            $stmt->bindParam(':nomb_dona', $nomb_dona);
            $stmt->bindParam(':email_dona', $email_dona);
            $stmt->bindParam(':metodoP_dona', $metodoP_dona);
            $stmt->bindParam(':anoni_dona', $anoni_dona, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success_message = "¡Gracias por tu donación! ❤️";
            } else {
                $error_message = "No se pudo guardar tu donación. Intenta de nuevo.";
            }
        } catch (PDOException $e) {
            $error_message = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>
<section class="donation-section">
    <div class="container">
        <h2>Apoya nuestra causa</h2>
        <p style="margin-bottom: 30px; color: #555;">Cada donación nos ayuda a seguir empoderando a mujeres vulnerables.</p>

        <?php if (!empty($success_message)): ?>
            <p style="color: green; text-align: center; margin-bottom: 15px;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <div class="donation-options">
            <div class="donation-option" data-amount="10">
                <h4>$10</h4>
                <p>Pequeño aporte</p>
            </div>
            <div class="donation-option selected" data-amount="25">
                <h4>$25</h4>
                <p>Ayuda media</p>
            </div>
            <div class="donation-option" data-amount="50">
                <h4>$50</h4>
                <p>Gran ayuda</p>
            </div>
            <div class="donation-option" data-amount="100">
                <h4>$100</h4>
                <p>Cambia vidas</p>
            </div>
            <div class="donation-option" data-amount="otro">
                <h4>Otro</h4>
                <p>Personalizado</p>
            </div>
        </div>

        <div class="donation-form">
            <h3>Realiza tu Donación</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="donation_amount">Monto de la Donación ($):</label>
                    <input type="number" id="donation_amount_input" name="donation_amount" value="25" step="0.01" min="1" required>
                </div>
                <div class="form-group">
                    <label for="nombre_donante">Tu Nombre (Opcional):</label>
                    <input type="text" id="nombre_donante" name="nombre_donante" placeholder="Nombre completo">
                </div>
                <div class="form-group">
                    <label for="email_donante">Tu Correo Electrónico (Opcional):</label>
                    <input type="email" id="email_donante" name="email_donante" placeholder="correo@ejemplo.com">
                </div>
                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select id="metodo_pago" name="metodo_pago" required>
                        <option value="">Selecciona</option>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                        <option value="paypal">PayPal</option>
                        <option value="transferencia">Transferencia Bancaria</option>
                    </select>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="anonimo_donacion" name="anonimo_donacion">
                    <label for="anonimo_donacion">Donar de forma anónima</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Donar Ahora</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
