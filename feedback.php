<?php
include 'includes/header.php';
include 'includes/db_config.php'; // tu clase Conn

$success_message = '';
$error_message = '';

// Captura ID de usuario si está logueado (opcional)
$id_usu = $_SESSION['id_usu'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Conn();
    $conn = $db->Conexion();

    // Captura datos del formulario
    $pun_fee = intval($_POST['puntuacion_general'] ?? 0);
    $coment_fee = htmlspecialchars(trim($_POST['comentarios'] ?? ''));
    $sugere_fee = htmlspecialchars(trim($_POST['sugerencias_mejora'] ?? ''));
    $anonima = isset($_POST['anonimo_feedback']) ? 1 : 0;

    // Validar puntuación
    if ($pun_fee <= 0) {
        $error_message = "Por favor califica la plataforma antes de enviar.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO feeback 
                (id_usu, pun_fee, coment_fee, anonima, sugere_fee)
                VALUES (:id_usu, :pun_fee, :coment_fee, :anonima, :sugere_fee)");

            $stmt->bindParam(':id_usu', $id_usu);
            $stmt->bindParam(':pun_fee', $pun_fee, PDO::PARAM_INT);
            $stmt->bindParam(':coment_fee', $coment_fee);
            $stmt->bindParam(':anonima', $anonima, PDO::PARAM_INT);
            $stmt->bindParam(':sugere_fee', $sugere_fee);

            if ($stmt->execute()) {
                $success_message = "¡Gracias por tu feedback!";
            } else {
                $error_message = "No se pudo enviar tu feedback. Intenta de nuevo.";
            }
        } catch (PDOException $e) {
            $error_message = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<section class="feedback-section">
    <div class="container">
        <div class="form-container">
            <h2>¿Qué opinas de nuestra plataforma?</h2>

            <!-- Mensajes -->
            <?php if (!empty($success_message)): ?>
                <p style="color: green; text-align: center;"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <p style="text-align: center; margin-bottom: 25px; color: #555;">¡Queremos escucharte! Tu feedback nos ayuda a mejorar.</p>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Califica nuestra plataforma:</label>
                    <div class="rating-stars">
                        <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                        <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                        <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                        <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                        <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                    </div>
                    <input type="hidden" name="puntuacion_general" id="hidden_rating" value="0">
                </div>

                <div class="form-group">
                    <label for="comentarios">Cuéntanos tus comentarios o sugerencias:</label>
                    <textarea id="comentarios" name="comentarios" rows="6" placeholder="Escribe aquí tus comentarios..."></textarea>
                </div>

                <div class="form-group">
                    <label for="funcionalidad_util">¿Qué funcionalidad te ha sido más útil?</label>
                    <input type="text" id="funcionalidad_util" name="funcionalidad_util" placeholder="Ej: Denuncia Anónima, Citas con psicólogos">
                </div>

                <div class="form-group">
                    <label for="sugerencias_mejora">¿Qué podríamos mejorar?</label>
                    <textarea id="sugerencias_mejora" name="sugerencias_mejora" rows="4" placeholder="Ej: Añadir más horarios, mejorar la búsqueda."></textarea>
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="anonimo_feedback" name="anonimo_feedback" checked>
                    <label for="anonimo_feedback">Enviar feedback de forma anónima</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- JavaScript para las estrellas ⭐ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelector('.rating-stars');
    if (ratingStars) {
        const stars = ratingStars.querySelectorAll('.star');
        const hiddenRating = document.getElementById('hidden_rating');
        let currentRating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                currentRating = index + 1;
                updateStars(currentRating);
                hiddenRating.value = currentRating;
            });

            star.addEventListener('mouseover', () => {
                updateStars(index + 1);
            });

            star.addEventListener('mouseout', () => {
                updateStars(currentRating);
            });
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }
    }
});
</script>

<style>
.rating-stars .star {
    cursor: pointer;
    font-size: 30px;
    color: #ccc;
}
.rating-stars .star.selected i {
    color: gold;
}
</style>
