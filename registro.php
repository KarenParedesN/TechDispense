<?php

$host = "localhost";
$dbname = "dispensador";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
    $apellido_materno = trim($_POST['apellido_materno'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (!$nombre || !$apellido_paterno || !$apellido_materno || !$correo || !$contrasena) {
        echo "<script>
                alert('Por favor completa todos los campos');
                window.history.back();
              </script>";
        exit;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email_usu = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>
                alert('El correo ya está registrado');
                window.history.back();
              </script>";
        exit;
    }


    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);


    $sql = "INSERT INTO usuarios (nombre_usu, apPaterno_usu, apMaterno_usu, email_usu, codigoSecreto_usu, id_rol)
            VALUES (?, ?, ?, ?, ?, 2)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellido_paterno, $apellido_materno, $correo, $contrasena_hash]);

    echo "<script>
            alert('Registro exitoso 💗');
            window.location.href = '../View/modulos/inicioSesion.php';
          </script>";

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
