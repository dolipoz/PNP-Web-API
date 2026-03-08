<?php
    include "../head.php";
    $user=$_POST['usuario'];
    $pass=$_POST['clave'];

    $q_user="select * from usuarios where nombre = '$user' and clave = '$pass'";

    $usuarios=mysqli_query($conexion,$q_user);

    if ($usuarios) {
        while ($usuario=mysqli_fetch_row($usuarios)) {
            if ($usuario[9] == true) {
                $_SESSION["login"] = true;
                $_SESSION["administrador"] = true;
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = 'Administrador conectado.';
                header('Location: ../index.php');
            } else {
                $_SESSION["login"] = true;
                $_SESSION["administrador"] = false;
                $_SESSION["correo"] = $usuario[1];
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = "Cliente conectado.";
                header('Location: ../index.php');
            }
        }
        if (!$_SESSION["login"]) {
            $_SESSION['error'] = true;
            $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
        header('Location: ../index.php');
    }
?>