<?php
    include 'head.php';
?>
<?php
    $q_rol="select id,rol from roles";
    $q_users = "select * from usuarios";
    if (!$_SESSION["login"]) {
        echo "
        <form id='consola_login' class='consolas' action='Scripts/iniciar-sesion.php' method='POST'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' min='8' max='20' required>
            <br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
    } else {
        // -----------------------------------------  Consolas 2 y 3 - Cerrar Sesión y Perfil de usuario modificable  ---------------------------------------------------------
        echo "
        <form id='consola_logoff' class='consolas' action='Scripts/cerrar-sesion.php' method='POST' style='display: none;'>
            <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
        </form>
        <div id='consola_perfil' class='consolas'>
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
                <label class='prompt' for='clave'>PS C:\Cambiar contraseña></label><input type='password' id='clave' name='clave' placeholder='Nueva Contraseña' size='20' min='8' max='20'><br>
                <label class='prompt' for='clave'>PS C:\Cambiar correo></label><input type='email' id='correo' name='correo' ".$_SESSION['usuario']['correo']." size='20' min='8' max='20' required><br>
                <label class='prompt' for='clave'>PS C:\Cambiar nombre></label><input type='text' id='nombre' name='nombre' ".$_SESSION['usuario']['nombre']." size='20' min='8' max='20' required><br>
                <label class='prompt' for='clave'>PS C:\Cambiar apellidos></label><input type='text' id='apellidos' name='apellidos' ".$_SESSION['usuario']['apellidos']." size='20' min='8' max='20' required><br>
                <br>
                <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
            </form>
        </div>
        ";
        // -----------------------------------------  Consola 4 Crear usuarios  ---------------------------------------------------------
        echo "
        <form id='consola_signup' class='consolas' action='Scripts/crear-usuario.php' method='POST' style='display: none;'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' minlength='8' maxlength='20' required><br>
            <label class='prompt' for='correo'>PS C:\Escriba su correo electrónico></label><input type='email' id='correo' name='correo' placeholder='|' size='50' minlength='9' maxlength='50'><br>
            <label class='prompt' for='nombre'>PS C:\Escriba su nombre></label><input type='text' id='nombre' name='nombre' placeholder='|' size='20' minlength='2' maxlength='20'><br>
            <label class='prompt' for='apellidos'>PS C:\Escriba su[s] apellido[s]></label><input type='text' id='apellidos' name='apellidos' placeholder='|' size='50' minlength='2' maxlength='50'><br>
            <label class='prompt' for='activo'>PS C:\Marque si quiere activar el usuario></label><input type='checkbox' id='activo' name='activo' checked><br>
            <label class='prompt' for='id_rol'>PS C:\Seleccione el rol del usuario></label>
            <select id='id_rol' name='id_rol'>
        ";
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
        // -----------------------------------------  Consola 5 Gestión de usuarios  ---------------------------------------------------------
        $solo_lectura = "";
        if (!$puede_modificar_usuarios) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_usuarios' class='consolas' style='display: none;'>
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
        ";
        if ($puede_modificar_usuarios) { echo "            <td>Actualizar</td>"; }
        if ($puede_eliminar_usuarios) { echo "            <td>Eliminar</td>"; }
        echo "  </tr>";

        $usuarios = mysqli_query($conexion, $q_users);
        if ($usuarios and mysqli_num_rows($usuarios) > 0) {
            while ($usuario = mysqli_fetch_assoc($usuarios)) {
                $activo = '';
                if ($usuario['activo']) { $activo = 'checked'; }
                echo "
                <tr>
                    <td><form action='Scripts/modificar-usuario.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar este usuario?\");'>
                    <input type='text' name='usuario' value='".$usuario['usuario']."' size='20' maxlength='20' required $solo_lectura></td>
                    <td><input type='password' name='clave' placeholder='Nueva contraseña' size='20' minlength='8' maxlength='20'></td>
                    <td><input type='email' name='correo' value='".$usuario['correo']."' size='50' minlength='9' maxlength='50'></td>
                    <td><input type='text' name='nombre' value='".$usuario['nombre']."' size='20' minlength='2' maxlength='20'></td>
                    <td><input type='text' name='apellidos' value='".$usuario['apellidos']."' size='50' minlength='2' maxlength='50'></td>
                    <td><input type='checkbox' name='activo' $activo></td>
                    <td><select name='id_rol'>
                ";
                $roles = mysqli_query($conexion, $q_rol);
                if ($roles and mysqli_num_rows($roles) > 0) {
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
                if ($puede_modificar_usuarios) { echo "   </td><td><input type='submit' value='Modificar'>"; }                
                if ($puede_eliminar_usuarios) { echo "   </td><td><input type='submit' formaction='Scripts/eliminar-usuario.php' value='Eliminar'>"; }
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
        // -----------------------------------------  Consola 6 - Crear API  -------------------------------------------------------------
        echo "
        <form id='consola_addapi' class='consolas' action='Scripts/crear-api.php' method='POST' style='display: none;'>
            <label class='prompt' for='tenant'>PS C:\Escriba el tenant de la api></label><input type='text' name='tenant' placeholder='|' size='20' maxlength='50' required><br>
            <label class='prompt' for='url_sharepoint'>PS C:\Escriba la url del sharepoint></label><input type='text' name='url_sharepoint' placeholder='|' size='30' minlength='8' maxlength='255' required><br>
            <label class='prompt' for='id_cliente'>PS C:\Escriba el cliente id de la api></label><input type='text' name='id_cliente' placeholder='|' size='30' minlength='9' maxlength='255' required><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        // -----------------------------------------  Consola 7 - Gestionar API  ---------------------------------------------------------
        $solo_lectura = "";
        if (!$puede_modificar_api) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_api' class='consolas' style='display: none;'>
            <table id='cabecera-tabla'>
                <tr>
                    <td>Tenant</td>
                    <td>URL</td>
                    <td>Cliente ID</td>
        ";
        if ($puede_modificar_api) { echo "            <td>Actualizar</td>"; }
        if ($puede_eliminar_api) { echo "            <td>Eliminar</td>"; }
        echo "      <td>Certificados</td>";
        if ($valor_permisos_modificar_api) { echo "            <td>Eliminar Certificado</td>"; }
        echo "  </tr>";

        $q_api = "select * from api";
        $apis = mysqli_query($conexion, $q_api);

        if ($apis and mysqli_num_rows($apis) > 0) {
            while ($api = mysqli_fetch_assoc($apis)) {
                echo "
                <tr>
                    <td><form action='Scripts/modificar-api.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar la api?\");'>
                    <input type='text' name='tenant' placeholder='|' size='20' maxlength='50' required></td>
                    <td><input type='text' name='url_sharepoint' placeholder='|' size='30' minlength='8' maxlength='255' required></td>
                    <td><input type='text' name='id_cliente' placeholder='|' size='30' minlength='9' maxlength='255' required>
                ";
                if ($puede_modificar_api) { echo "      </td><td><input type='submit' value='Modificar'>"; }
                if ($puede_eliminar_api) { echo "      </td><td><input type='submit' formaction='Scripts/eliminar-api.php' value='Eliminar'>"; }
                echo "    </form></td>";
                echo "    <td><form action='Scripts/eliminar-certificado.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar el certificado?\");'>
                            <select name='certificados'>
                ";
                $q_cert = "select * from certificados where id_api = ".$api['id'];
                $certificados = mysqli_query($conexion, $q_cert);
                if ($certificados and mysqli_num_rows($certificados) > 0) {
                    while ($certificado = mysqli_fetch_assoc($certificados)) {
                        echo "<option value='".$certificado['id']."'>".$certificado['nombre']."</option>";
                    }
                }
                if ($puede_eliminar_certificados and mysqli_num_rows($certificados) > 0) { echo "      </td><td><input type='submit' value='Eliminar Certificado'>"; }
                echo "
                    </select></td>
                </tr>
                ";
            }
        }

        echo "
            </table>
        </div>
        ";
        // -----------------------------------------  Consola 8 - Crear Certificados  -------------------------------------------------------------
        echo "
        <form id='consola_addcert' class='consolas' action='Scripts/crear-certificado.php' method='POST' style='display: none;'>
            <label class='prompt' for='tenant'>PS C:\Escriba el tenant de la api></label><input type='text' name='tenant' placeholder='|' size='20' maxlength='50' required><br>
            <label class='prompt' for='url_sharepoint'>PS C:\Escriba la url del sharepoint></label><input type='text' name='url_sharepoint' placeholder='|' size='30' minlength='8' maxlength='255' required><br>
            <label class='prompt' for='id_cliente'>PS C:\Escriba el cliente id de la api></label><input type='text' name='id_cliente' placeholder='|' size='30' minlength='9' maxlength='255' required><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        // -----------------------------------------  Consola 9 - Gestionar Certificados  ---------------------------------------------------------
        $solo_lectura = "";
        if ($puede_eliminar_certificados and !$puede_modificar_certificados) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_cert' class='consolas' style='display: none;'>
            <table id='cabecera-tabla'>
                <tr>
                    <td>Tenant</td>
                    <td>URL</td>
                    <td>Cliente ID</td>
        ";
        if ($puede_modificar_api) { echo "            <td>Actualizar</td>"; }
        if ($puede_eliminar_api) { echo "            <td>Eliminar</td>"; }
        echo "      <td>Certificados</td>";
        if ($valor_permisos_modificar_api) { echo "            <td>Eliminar Certificado</td>"; }
        echo "  </tr>";

        $q_api = "select * from api";
        $apis = mysqli_query($conexion, $q_api);

        if ($apis and mysqli_num_rows($apis) > 0) {
            while ($api = mysqli_fetch_assoc($apis)) {
                echo "
                <tr>
                    <td><form action='Scripts/modificar-certificado.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar el certificado?\");'>
                    <input type='text' name='tenant' placeholder='|' size='20' maxlength='50' required></td>
                    <td><input type='text' name='url_sharepoint' placeholder='|' size='30' minlength='8' maxlength='255' required></td>
                    <td><input type='text' name='id_cliente' placeholder='|' size='30' minlength='9' maxlength='255' required>
                ";
                if ($puede_modificar_api) { echo "      </td><td><input type='submit' value='Modificar'>"; }
                if ($puede_eliminar_api) { echo "      </td><td><input type='submit' formaction='Scripts/eliminar-api.php' value='Eliminar'>"; }
                echo "    </form></td>";
                echo "    <td><form action='Scripts/eliminar-certificado.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar el certificado?\");'>
                            <select name='certificados'>
                ";
                $q_cert = "select * from certificados where id_api = ".$api['id'];
                $certificados = mysqli_query($conexion, $q_cert);
                if ($certificados and mysqli_num_rows($certificados) > 0) {
                    while ($certificado = mysqli_fetch_assoc($certificados)) {
                        echo "<option value='".$certificado['id']."'>".$certificado['nombre']."</option>";
                    }
                }
                if ($puede_eliminar_certificados and mysqli_num_rows($certificados) > 0) { echo "      </td><td><input type='submit' value='Eliminar Certificado'>"; }
                echo "
                    </select></td>
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
