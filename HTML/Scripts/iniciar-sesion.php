<?php
    include "../head.php";
    $user=$_POST['usuario'];
    $pass=$_POST['clave'];

    $q_user="select * from usuarios where usuario = '$user'";

    $usuarios=mysqli_query($conexion,$q_user);
    // Recorremos la lista que en caso de encontrar usuarios es solo uno ya que no se puede repetir el nombre de usuario
    if ($usuarios) {
        while ($usuario=mysqli_fetch_assoc($usuarios)) {
            // Comprueba que la contraseña hasheada sea la misma que la introducida por el usuario, si no saldrá sin iniciar sesión
            if (!password_verify($pass, $usuario['clave'])) { break; };
            if ($usuario['id_rol'] == 1) { $_SESSION["administrador"] = true; };
            // Si usuario y contraseña son correctos y además está activo, modificará las variables de sesión para usuario
            if ($usuario['activo'] == true) {
                $_SESSION["login"] = true;
                $_SESSION["usuario"] = [
                    'id' => $usuario['id'],
                    'usuario' => $usuario['usuario'],
                    'id_rol' => $usuario['id_rol'],
                    'nombre' => $usuario['nombre'],
                    'apellidos' => $usuario['apellidos'],
                    'correo' => $usuario['correo'],
                    'activo' => $usuario['activo'],
                    'f_creado' => $usuario['f_creado'],
                    'ult_sesion' => $usuario['ult_sesion']
                ];
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = "Usuario conectado.";
            }
        }
        if ($_SESSION["login"]) {
            $upd_user="update usuarios set ult_sesion = current_timestamp where usuario = '$user'";
            mysqli_query($conexion,$upd_user);
        } else {
            $_SESSION['error'] = true;
            $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['info'] = 'El usuario no existe o la contraseña no es correcta.';
    }
    header('Location: ../index.php');
?>