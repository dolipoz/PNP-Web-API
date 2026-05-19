<?php
    include 'head.php';
?>
<?php
    if (!$_SESSION["login"]) {
        echo "
        <form id='consola1' class='consolas' action='Scripts/iniciar-sesion.php' method='POST'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' min='8' max='20' required>
            <br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
    } else {
        // -----------------------------------------  Consolas 2 y 3 - Cerrar Sesión y Perfil de usuario modificable  ---------------------------------------------------------
        echo "
        <form id='consola2' class='consolas' action='Scripts/cerrar-sesion.php' method='POST' style='display: none;'>
            <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
        </form>
        <div id='consola3' class='consolas'>
            <h2>Usuario: ".$_SESSION['usuario']['usuario']."</h2>
            <h2>Correo: ".$_SESSION['usuario']['correo']."</h2>
            <h2>Nombre: ".$_SESSION['usuario']['nombre']."</h2>
            <h2>Apellidos: ".$_SESSION['usuario']['apellidos']."</h2>
            <h2>Activo: ".$_SESSION['usuario']['activo']."</h2>
            <h2>Rol: ".$_SESSION['usuario']['rol']."</h2>
            <ul>
        ";
        foreach ($_SESSION['usuario']['permisos'] as $id => $nombre_valor) {
            foreach ($nombre_valor as $nombre => $valor) {
                if ($valor) {
                    echo "  <li>$id: $nombre</li>";
                }
            }

        }
        echo "
            </ul>
            <h2>Fecha Creación: ".$_SESSION['usuario']['f_creado']."</h2>
            <h2>Última Sesión: ".$_SESSION['usuario']['ult_sesion']."</h2>

            <form id='mod_perfil' action='Scripts/modificar-mi-usuario.php' method='POST' style='display: none;'>
                <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' ".$_SESSION['usuario']['clave']." size='20' min='8' max='20' required><br>
                <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='email' id='correo' name='correo' ".$_SESSION['usuario']['correo']." size='20' min='8' max='20' required><br>
                <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='text' id='nombre' name='nombre' ".$_SESSION['usuario']['nombre']." size='20' min='8' max='20' required><br>
                <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='text' id='apellidos' name='apellidos' ".$_SESSION['usuario']['apellidos']." size='20' min='8' max='20' required><br>
                <br>
                <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
            </form>
        </div>
        ";
        // -----------------------------------------  Consola 4 Crear usuarios  ---------------------------------------------------------
        echo "
        <form id='consola4' class='consolas' action='Scripts/crear-usuario.php' method='POST' style='display: none;'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' minlength='8' maxlength='20' required><br>
            <label class='prompt' for='correo'>PS C:\Escriba su correo electrónico></label><input type='email' id='correo' name='correo' placeholder='|' size='50' minlength='9' maxlength='50'><br>
            <label class='prompt' for='nombre'>PS C:\Escriba su nombre></label><input type='text' id='nombre' name='nombre' placeholder='|' size='20' minlength='2' maxlength='20'><br>
            <label class='prompt' for='apellidos'>PS C:\Escriba su[s] apellido[s]></label><input type='text' id='apellidos' name='apellidos' placeholder='|' size='50' minlength='2' maxlength='50'><br>
            <label class='prompt' for='activo'>PS C:\Marque si quiere activar el usuario></label><input type='checkbox' id='activo' name='activo' checked><br>
            <label class='prompt' for='id_rol'>PS C:\Seleccione el rol del usuario></label>
            <select id='id_rol' name='id_rol'>
        ";
        $q_rol="select id,rol from roles";
        $roles=mysqli_query($conexion,$q_rol);
        // Recorremos los roles que existen
        if ($roles and mysqli_num_rows($roles) > 0) {
            while ($rol=mysqli_fetch_assoc($roles)) {
                echo "<option value='".$rol['id']."'>".$rol['rol']."</option>";
            }
        }
        echo "
            </select><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        $solo_lectura = "";
        if ($_SESSION['usuario']['permisos'][2]) {$solo_lectura = "readonly";}
        // -----------------------------------------  Consola 5 Gestión de usuarios  ---------------------------------------------------------
        echo "
        <div id='consola5' class='consolas' style='display: none;'>
            <table id='cabecera-tabla'>
                <tr>
                    <td>Usuario</td>
                    <td>Clave</td>
                    <td>Correo</td>
                    <td>Nombre</td>
                    <td>Apellidos</td>
                    <td>Activo</td>
                    <td>Rol</td>
                    <td>Creado el</td>
                    <td>Última Sesión</td>
                    <td>Actualizar</td>
                    <td>Eliminar</td>
                </tr>
        ";

        $q_users = "select * from usuarios";
        $usuarios = mysqli_query($conexion, $q_users);

        if ($usuarios && mysqli_num_rows($usuarios) > 0) {
            while ($usuario = mysqli_fetch_assoc($usuarios)) {
                $activo = '';
                if ($usuario['activo']) {
                    $activo = 'checked';
                }
                echo "
                <tr>
                    <td><form action='Scripts/modificar-usuario.php' method='POST' class='filas-tabla'>
                    <input type='text' name='usuario' value='".$usuario['usuario']."' size='20' maxlength='20' required $solo_lectura></td>
                    <td><input type='password' name='clave' placeholder='Nueva contraseña' size='20' minlength='8' maxlength='20'></td>
                    <td><input type='email' name='correo' value='".$usuario['correo']."' size='50' minlength='9' maxlength='50'></td>
                    <td><input type='text' name='nombre' value='".$usuario['nombre']."' size='20' minlength='2' maxlength='20'></td>
                    <td><input type='text' name='apellidos' value='".$usuario['apellidos']."' size='50' minlength='2' maxlength='50'></td>
                    <td><input type='checkbox' name='activo' $activo></td>
                    <td><select name='id_rol'>
                ";

                $roles = mysqli_query($conexion, $q_rol);
                if ($roles && mysqli_num_rows($roles) > 0) {
                    while ($rol = mysqli_fetch_assoc($roles)) {
                        if ($rol['id'] == $usuario['id_rol']) {
                            echo "<option value='".$rol['id']."' selected>".$rol['rol']."</option>";
                        } else {
                            echo "<option value='".$rol['id']."'>".$rol['rol']."</option>";
                        }
                    }
                }

                echo "
                    </select></td>
                    <td>".$usuario['f_creado']."</td>
                    <td>".$usuario['ult_sesion']."
                ";

                if (isset($_SESSION['usuario']['permisos'][2])) {
                    $valor_permisos = array_values($_SESSION['usuario']['permisos'][2]);
                    if ($valor_permisos) {
                        echo "
                        </td><td><input type='submit' value='Modificar'>
                        ";
                    }
                }
                if (isset($_SESSION['usuario']['permisos'][3])) {
                    $valor_permisos = array_values($_SESSION['usuario']['permisos'][3]);
                    if ($valor_permisos) {
                        echo "
                        </td><td><input type='submit' formaction='Scripts/eliminar-usuario.php' value='Eliminar'>
                        ";
                    }
                }

                echo "
                    </form>
                    </td>
                </tr>
                ";
            }
        }

        echo "
            </table>
        </div>
        ";
    }
?>
    </div>
<?php
    include 'footer.php';
?>
