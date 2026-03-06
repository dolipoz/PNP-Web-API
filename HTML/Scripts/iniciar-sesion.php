<?php
    include "../head.php";
    $user=$_POST['usuario'];
    $pass=$_POST['contrasena'];

    $q_user="select * from usuarios where usuario = '$user' and contrasena = '$pass'";

    $usuarios=mysqli_query($conexion,$q_user);

    if ($usuarios) {
        while ($usuario=mysqli_fetch_row($usuarios)) {
            if ($usuario[9] == True) {
                $_SESSION["login"] = True;
                $_SESSION["administrador"] = True;
                $_SESSION['correcto'] = True;
                $_SESSION['info'] = 'Administrador conectado.';
                header('Location: ../index.php');
            } else {
                $_SESSION["login"] = True;
                $_SESSION["administrador"] = False;
                $_SESSION["correo"] = $usuario[1];
                $_SESSION['correcto'] = True;
                $_SESSION['info'] = "Cliente conectado.";
                header('Location: ../index.php');
            }
        }
        if (!$_SESSION["login"]) {
            $_SESSION['error'] = True;
            $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
            header('Location: ../iniciar_sesion.php');
        }
    } else {
        $_SESSION['error'] = True;
        $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
        header('Location: ../iniciar_sesion.php');
    }
?>