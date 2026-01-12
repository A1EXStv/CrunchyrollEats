<section id="register-section">
    <div class="logo">
     <a href="index.php?controller=home&action=index"><img src="public/img/logo.png" alt="Crunchyroll Eats"></a>
    </div>
    <div class="login-wrapper">
        <div class="login-card">

            <h2>Registrarse</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=registro&action=registrar" method="POST">

                <div class="form-group">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder=" " required>
                    <label class="form-label" for="nombre">Nombre y Apellidos</label>
                </div>
                <div class="form-group">
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder=" " required>
                    <label class="form-label" for="telefono">Telefono</label>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                    <label class="form-label" for="email">Dirección de email</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder=" " required>
                    <label class="form-label" for="contraseña">Contraseña</label>
                </div>
                <input type="hidden" name="rol" id="rol" value="usuario">

                <button type="submit" class="btn-login">Crear Cuenta</button>

                <div class="register-link">
                    <a href="index.php?controller=login&action=index">Inicio de sesión</a>
                </div>

            </form>
        </div>
    </div>
</section>