<?php include 'includes/header.php'; ?>

    <section class="form-section">
        <div class="container">
            <div class="form-container">
                <h2>Denuncia Anónima</h2>
                <p style="text-align: center; margin-bottom: 25px; color: #555;">Comunica una situación de peligro de forma segura y confidencial.</p>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="tipo_violencia">Tipo de Violencia:</label>
                        <select id="tipo_violencia" name="tipo_violencia" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="fisica">Física</option>
                            <option value="psicologica">Psicológica</option>
                            <option value="sexual">Sexual</option>
                            <option value="economica">Económica</option>
                            <option value="acoso">Acoso</option>
                            <option value="otra">Otra</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Describe la situación:</label>
                        <textarea id="descripcion" name="descripcion" rows="8" placeholder="Sé lo más detallada posible..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ubicacion">¿Dónde ocurrió el incidente? (Opcional)</label>
                        <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Mi casa, en el trabajo, vía pública">
                    </div>
                    <div class="form-group">
                        <label for="fecha_incidente">¿Cuándo ocurrió el incidente? (Fecha aproximada)</label>
                        <input type="date" id="fecha_incidente" name="fecha_incidente">
                    </div>
                    <div class="form-group">
                        <label for="adjuntos">Adjuntar evidencia (fotos, audios, etc. - opcional):</label>
                        <input type="file" id="adjuntos" name="adjuntos" multiple>
                        <small style="color: #777;">(Máx. 5MB por archivo, formatos permitidos: jpg, png, mp3, mp4, pdf)</small>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="anonimo_denuncia" name="anonimo_denuncia" checked>
                        <label for="anonimo_denuncia">Mantener mi denuncia anónima</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enviar Denuncia</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>