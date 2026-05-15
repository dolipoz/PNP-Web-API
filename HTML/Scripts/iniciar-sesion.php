<?php
    include "../head.php";
    $user=$_POST['usuario'];
    $pass=$_POST['clave'];

    $q_user="select * from usuarios where usuario = '$user'";

    $usuarios=mysqli_query($conexion,$q_user);

    if ($usuarios) {
        while ($usuario=mysqli_fetch_assoc($usuarios)) {
            if (!password_verify($pass, $usuario['clave'])) { break; };
            if ($usuario['usuario'] == 'admin') {
                $_SESSION["login"] = true;
                $_SESSION["administrador"] = true;
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = 'Administrador conectado.';
                header('Location: ../index.php');
            } else {
                if ($usuario['activo'] == true)
                $_SESSION["login"] = true;
                $_SESSION["administrador"] = false;
                $_SESSION["usuario"] = $usuario['correo'];
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = "Usuario conectado.";
                header('Location: ../index.php');
            }
        }
        if ($_SESSION["login"]) {
            $upd_user="update usuarios set ult_sesion = current_timestamp where usuario = '$user'";
            mysqli_query($conexion,$upd_user);
        }
    }
    $_SESSION['error'] = true;
    $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
    header('Location: ../index.php');

?>