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
            if (!password_verify($pass, $usuario['clave'])) { break; }
            if ($usuario['id_rol'] == 1) { $_SESSION["administrador"] = true; }
            // Si usuario y contraseña son correctos y además está activo, modificará las variables de sesión para usuario
            if ($usuario['activo'] == true) {
                $_SESSION["login"] = true;
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
                    'ult_sesion' => $usuario['ult_sesion'],
                    'permisos' => []
                ];
                $_SESSION['correcto'] = true;
                $_SESSION['info'] = "Usuario conectado.";
            }
        }
        if ($_SESSION["login"]) {
            $upd_user="update usuarios set ult_sesion = current_timestamp where usuario = '$user'";
            mysqli_query($conexion,$upd_user);
            $perm_user="select r.rol,p.id from permisos p join roles_permisos rp on p.id = rp.id_permiso join roles r on r.id = rp.id_rol where rp.id_rol = ".$_SESSION["usuario"]['id_rol'];
            $perms=mysqli_query($conexion,$perm_user);
            if ($perms) {
                while ($p=mysqli_fetch_assoc($perms)) {
                    $_SESSION["usuario"]['rol'] = $p['rol'];
                    $_SESSION["usuario"]['permisos'][] = $p['id'];
                }
            }
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