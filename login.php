<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomina-Consulting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
    <header>
        <label>NOMINA-CONSULTING</label>
    </header>

    <div class="div_center">
        <form method="post" action="">
            <nav class="center-form">
                <nav class="card-form-login">
                    <nav class="card-gray">
                        <img src="assets/images/logo.png" class="logo"/>
                    </nav>

                    <div class="center-form mb-3">
                        <input type="email" name="email" class="input-email form-control" placeholder="name@example.com" autocomplete="email" required>
                    </div>

                    <div class="center-form mb-3">
                        <input type="password" name="password" class="input-pass form-control" placeholder="*******" autocomplete="current-password" required>
                    </div>

                    <button type="submit" name="login" class="login-btn btn btn-primary">Iniciar sesión</button>

                    <div class="center-form mb-3">
                        <a href="modules/restablecer_contrasena.php" class="recover-pass btn">¿Olvidaste la contraseña?</a>
                    </div>
                </nav>
            </nav>
        </form>

        <span>Derecho de autor 2024</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php'; // Asegúrate de incluir las funciones

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión solo si no está activa
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    login(); // Llamar a la función de login cuando se envíe el formulario
}

function login() {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    $conn = getConnection();
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $sql = "{call sp_login(?, ?)}";
    $params = array(
        array(&$email, SQLSRV_PARAM_IN),
        array(&$password, SQLSRV_PARAM_IN)
    );
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        echo '<script>alert("Crendenciales incorrectas, por favor verifica e intenta nuevamente.");</script>';
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
        // Si el login es exitoso, iniciar sesión y redirigir
        $_SESSION['usuario_logueado'] = true;
        $_SESSION['correo_usuario'] = $email;
        
        header("Location: index.php");
        exit();
    } else {
        echo '<script>alert("Credenciales incorrectas, por favor verifica e intenta nuevamente.");</script>';
    }
    
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>
