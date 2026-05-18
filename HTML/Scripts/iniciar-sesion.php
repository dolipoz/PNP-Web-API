<?php
    include "../head.php";
    $user=$_POST['usuario'];
    $pass=$_POST['clave'];

    $q_user="select * from usuarios where usuario = '$user'";

    $usuarios=mysqli_query($conexion,$q_user);
    $usuario=mysqli_fetch_assoc($usuarios);
    // Buscamos al usuario dentro de la base de datos
    if ($usuario) {
        // Comprueba que la contraseña hasheada sea la misma que la introducida por el usuario, si no saldrá sin iniciar sesión
        if (password_verify($pass, $usuario['clave'])) {
            // Si usuario y contraseña son correctos y además está activo, modificará las variables de sesión para usuario
            if ($usuario['activo'] == true) {
                $upd_user="update usuarios set ult_sesion = current_timestamp where usuario = '$user'";
                mysqli_query($conexion,$upd_user);
                $q_sesion="select ult_sesion from usuarios where usuario = '$user'";
                $q_sesion=mysqli_query($conexion,$q_sesion);
                $ult_sesion=mysqli_fetch_assoc($q_sesion);
                $_SESSION["usuario"] = [
                    'id' => $usuario['id'],
                    'usuario' => $usuario['usuario'],
                    'id_rol' => $usuario['id_rol'],
                    'rol' => '',
                    'nombre' => $usuario['nombre'],
                    'apellidos' => $usuario['apellidos'],
                    'correo' => $usuario['correo'],
                    'activo' => $usuario['activo'],
                    'f_creado' => $usuario['f_creado'],
                    'ult_sesion' => $ult_sesion['ult_sesion'],
                    'permisos' => []
                ];

                $perm_user="select r.rol,p.id from permisos p join roles_permisos rp on p.id = rp.id_permiso join roles r on r.id = rp.id_rol where rp.id_rol = ".$_SESSION["usuario"]['id_rol'];
                $perms=mysqli_query($conexion,$perm_user);
                if ($perms) {
                    while ($p=mysqli_fetch_assoc($perms)) {
                        $_SESSION["usuario"]['rol'] = $p['rol'];
                        $_SESSION["usuario"]['permisos'][] = $p['id'];
                    }
                }

                $_SESSION["login"] = true;
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = "Usuario conectado.";
            } else {
                $_SESSION['error'] = true;
                $_SESSION['info'] = 'El usuario no está activo.';
            }
        } else {
            $_SESSION['error'] = true;
            $_SESSION['info'] = 'La contraseña no es correcta.';
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['info'] = 'El usuario no existe.';
    }
    header('Location: ../index.php');
?>