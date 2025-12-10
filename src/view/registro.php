<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceder - Crunchyroll Eats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/CrunchyEats_AlexRomeroLozano/public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body id="body-login">
    <div class="logo">
        <img src="/CrunchyEats_AlexRomeroLozano/public/img/logo.png" alt="Crunchyroll Eats">
    </div>
    <div class="login-wrapper">
        <div class="login-card">

            <h2>Registrarse</h2>

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

                <button type="submit" class="btn-login">Acceder</button>

                <div class="register-link">
                    <a href="index.php?controller=registro&action=index">Crear cuenta</a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>