<?php
    include "variables.php";
    include "funciones.php";
    include "conectar-db.php";
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
                $sesion=mysqli_query($conexion,$q_sesion);
                $ult_sesion=mysqli_fetch_assoc($sesion);
                $_SESSION["usuario"]['id'] = $usuario['id'];
                $_SESSION["usuario"]['usuario'] = $usuario['usuario'];
                $_SESSION["usuario"]['id_rol'] = $usuario['id_rol'];
                $_SESSION["usuario"]['nombre'] = $usuario['nombre'];
                $_SESSION["usuario"]['apellidos'] = $usuario['apellidos'];
                $_SESSION["usuario"]['correo'] = $usuario['correo'];
                $_SESSION["usuario"]['activo'] = $usuario['activo'];
                $_SESSION["usuario"]['f_creado'] = $usuario['f_creado'];
                $_SESSION["usuario"]['ult_sesion'] = $ult_sesion['ult_sesion'];
                // Sacamos los permisos del usuario usando un select join con las tablas de rol y permisos
                $perm_user="select r.rol,p.id,p.permiso from permisos p join roles_permisos rp on p.id = rp.id_permiso join roles r on r.id = rp.id_rol where rp.id_rol = ".$usuario['id_rol'];
                $perms=mysqli_query($conexion,$perm_user);
                if ($perms) {
                    while ($p=mysqli_fetch_assoc($perms)) {
                        $_SESSION["usuario"]['rol'] = $p['rol'];
                        $_SESSION["usuario"]['permisos'][$p['id']] = [$p['permiso'] => true];
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