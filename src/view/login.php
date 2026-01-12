<section id="login-section">
    <div class="logo">
        <img src="public/img/logo.png" alt="Crunchyroll Eats">
    </div>
    <div class="login-wrapper">
        <div class="login-card">

            <h2>Acceder</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=login&action=auth" method="POST">

                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                    <label class="form-label" for="email">Dirección de email</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder=" " required>
                    <label class="form-label" for="contraseña">Contraseña</label>
                </div>

                <button type="submit" class="btn-login">Acceder</button>

                <div class="register-link">
                    <a href="index.php?controller=registro&action=index">Crear cuenta</a>
                </div>

            </form>
        </div>
    </div>
</section>