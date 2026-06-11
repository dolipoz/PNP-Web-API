<?php
    include 'head.php';
?>
    <div id="ventana_index">
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
            <?php
            if (!$_SESSION["login"]) {
                echo "<li id='login' class='pestanias' style='background-color: #012456;'><a href='#' onclick='mostrarConsola(\"login\",\"consola_login\")'>Iniciar Sesión</a></li>";
            } else {
                echo "
                <li id='perfil' class='pestanias' style='background-color: #012456;'><a href='#' onclick='mostrarConsola(\"perfil\",\"consola_perfil\")'>Mi Perfil</a></li>
                <li id='logoff' class='pestanias'><a href='#' onclick='mostrarConsola(\"logoff\",\"consola_logoff\")'>Cerrar Sesión</a></li>
                ";
                if ($puede_crear_api) {
                    echo "<li id='addapi' class='pestanias'><a href='#' onclick='mostrarConsola(\"addapi\",\"consola_addapi\")'>Crear API</a></li>";
                }
                if ($puede_modificar_api or $puede_eliminar_api) {
                    echo "<li id='gestion_api' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_api\",\"consola_g_api\")'>Gestionar APIs</a></li>";
                }
                if ($puede_crear_certificados) {
                    echo "<li id='addcert' class='pestanias'><a href='#' onclick='mostrarConsola(\"addcert\",\"consola_addcert\")'>Crear Certificado</a></li>";
                }
                if ($puede_modificar_certificados or $puede_eliminar_certificados) {
                    echo "<li id='gestion_cert' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_cert\",\"consola_g_cert\")'>Gestionar Certificados</a></li>";
                }
                if ($puede_crear_usuarios) {
                    echo "<li id='signup' class='pestanias'><a href='#' onclick='mostrarConsola(\"signup\",\"consola_signup\")'>Crear Usuario</a></li>";
                }
                if ($puede_modificar_usuarios or $puede_eliminar_usuarios) {
                    echo "<li id='gestion_usuarios' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_usuarios\",\"consola_g_usuarios\")'>Gestionar Usuarios</a></li>";
                }
            }
            ?>
            </ul>
        </nav>
<?php
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
        $p_activo = isset($_SESSION['usuario']['activo']) ? "Si" : "No";
        // -----------------------------------------  Consolas 2 y 3 - Cerrar Sesión y Perfil de usuario modificable  ---------------------------------------------------------
        echo "
        <form id='consola_logoff' class='consolas' action='Scripts/cerrar-sesion.php' method='POST' style='display: none;'>
            <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
        </form>
        <div id='consola_perfil' class='consolas'>
            <table>
                <tr>
                    <td><label class='prompt'>PS C:\Users\\{$_SESSION['usuario']['usuario']}></label> net user {$_SESSION['usuario']['usuario']}</td>
                </tr>
                <tr>
                    <td>Nombre de usuario</td><td>{$_SESSION['usuario']['usuario']}</td>
                </tr>
                <tr>
                    <td>Nombre completo</td><td>{$_SESSION['usuario']['nombre']} {$_SESSION['usuario']['apellidos']}</td>
                </tr>
                <tr>
                    <td>Cuenta activa</td><td>{$p_activo}</td>
                </tr>
                <tr>
                    <td>Correo de usuario</td><td>{$_SESSION['usuario']['correo']}</td>
                </tr>
                <tr>
                    <td>Creación</td><td>{$_SESSION['usuario']['f_creado']}</td>
                </tr>
                <tr>
                    <td>Ultimo sesión</td><td>{$_SESSION['usuario']['ult_sesion']}</td>
                </tr>
            </table>
            <h3><a id='mostrar_permisos' onclick='mostrarPermisos()' href='#'>Mostrar Permisos v</a></h3>
            <ul id='p_permisos' style='display: none;'>
        ";
        foreach ($_SESSION['usuario']['permisos'] as $id_permiso => $nombre_valor) {
            foreach ($nombre_valor as $nombre_permiso => $valor) {
                if ($valor) {
                    echo "  <li>$id_permiso: $nombre_permiso</li>";
                }
            }
        }
        echo "
            </ul>
            <h3><a id='modificar_mi_perfil' onclick='mostrarEditarPerfil()' href='#'>Modificar Perfil v</a></h3>
            <form id='mod_perfil' action='Scripts/Modificar/modificar-mi-usuario.php' method='POST' style='display: none;'>
                <input type='hidden' name='id_usuario' value='{$_SESSION['usuario']['id']}' required>
                <label class='prompt' for='clave'>PS C:\Cambiar contraseña></label><input type='password' name='clave' placeholder='Nueva Contraseña' size='20' minlenght='8' maxlenght='20'><br>
                <label class='prompt' for='correo'>PS C:\Cambiar correo></label><input type='email' name='correo' value='{$_SESSION['usuario']['correo']}' size='20' minlenght='8' maxlenght='20'><br>
                <label class='prompt' for='nombre'>PS C:\Cambiar nombre></label><input type='text' name='nombre' value='{$_SESSION['usuario']['nombre']}' size='20' minlenght='8' maxlenght='20'><br>
                <label class='prompt' for='apellidos'>PS C:\Cambiar apellidos></label><input type='text' name='apellidos' value='{$_SESSION['usuario']['apellidos']}' size='20' minlenght='8' maxlenght='20'><br>
                <br>
                <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
            </form>
        </div>
        ";
        // -----------------------------------------  Consola 4 - Crear API  -------------------------------------------------------------
        echo "
        <form id='consola_addapi' class='consolas' action='Scripts/Crear/crear-api.php' method='POST' style='display: none;'>
            <label class='prompt' for='tenant'>PS C:\Escriba el tenant de la api></label><input type='text' name='tenant' placeholder='|' size='20' maxlength='50' required><br>
            <label class='prompt' for='sitio'>PS C:\Escriba el sitio del sharepoint></label><input type='text' name='sitio' placeholder='|' size='30' minlength='8' maxlength='255' required><br>
            <label class='prompt' for='id_cliente'>PS C:\Escriba el id cliente de la api></label><input type='text' name='id_cliente' placeholder='|' size='30' minlength='9' maxlength='255' required><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        // -----------------------------------------  Consola 5 - Gestionar API  ---------------------------------------------------------
        $solo_lectura = "";
        if (!$puede_modificar_api) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_api' class='consolas' style='display: none;'>
            <table class='cabecera-tabla'>
                <tr>
                    <th>Tenant</th>
                    <th>Sitio</th>
                    <th>Cliente ID</th>
        ";
        if ($puede_modificar_api) { echo "            <th>Actualizar</th>"; }
        if ($puede_eliminar_api) { echo "            <th>Eliminar</th>"; }
        echo "      <th>Certificados Asociados</th>";
        echo "  </tr>";

        $apis = mysqli_query($conexion, $Q_apis);
        if ($apis and mysqli_num_rows($apis) > 0) {
            while ($api = mysqli_fetch_assoc($apis)) {
                $id_api = $api['id'];
                $tenant = $api['tenant'];
                $sitio = $api['sitio'];
                $id_cliente = $api['id_cliente'];
                echo "
                <tr>
                    <td><form action='Scripts/Modificar/modificar-api.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar la api?\");'>
                    <input type='hidden' name='id_api' value='$id_api' required>
                    <input type='text' name='tenant' value='$tenant' size='20' maxlength='50' required></td>
                    <td><input type='text' name='sitio' value='$sitio' size='30' minlength='2' maxlength='100' required></td>
                    <td><input type='text' name='id_cliente' value='$id_cliente' size='30' minlength='9' maxlength='255' required>
                ";
                if ($puede_modificar_api) { echo "  </td><td><input class='editar' type='submit' value=''>"; }
                if ($puede_eliminar_api) { echo "   </td><td><input class='eliminar' type='submit' value='' formaction='Scripts/Eliminar/eliminar-api.php'>"; }
                echo "
                    </form></td>
                    <td><form action='Scripts/Modificar/desasociar-certificado-api.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres desasociar el certificado?\");'>
                    <input type='hidden' name='id_api' value='$id_api' required>
                    <select name='id_cert'>
                ";
                $q_cert = "
                    select c.* 
                    from certificados c 
                    inner join api_certificados ac on ac.id_certificado = c.id
                    where ac.id_api = $id_api
                ";
                $certificados = mysqli_query($conexion, $q_cert);
                if ($certificados and mysqli_num_rows($certificados) > 0) {
                    while ($certificado = mysqli_fetch_assoc($certificados)) {
                        echo "      <option value='{$certificado['id']}'> {$certificado['nombre']} </option>";
                    }
                }
                echo "
                    </select>
                    <input class='eliminar' type='submit' value=''>
                    </form></td>
                </tr>
                ";
            }
        }
        echo "
            </table>
        </div>
        ";
        // -----------------------------------------  Consola 6 - Crear Certificados  -------------------------------------------------------------
        
        echo "
        <form id='consola_addcert' class='consolas' action='Scripts/Crear/crear-certificado.php' method='POST' style='display: none;'>
            <label class='prompt' for='certificado'>PS C:\Escriba el nombre del certificado></label><input type='text' id='certificado' name='certificado' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='pais'>PS C:\Escoja el pais></label>
            <select id='pais' name='pais'>
                <option value='ES' selected>España</option>
                <option value='US'>Estados Unidos</option>
                <option value='DE'>Alemania</option>
                <option value='FR'>Francia</option>
            </select><br>
            <label class='prompt' for='ciudad'>PS C:\Escriba el nombre de la ciudad></label><input type='text' id='ciudad' name='ciudad' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='localidad'>PS C:\Escriba el nombre de la localidad></label><input type='text' id='localidad' name='localidad' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='expira'>PS C:\Indique cuantos años debe durar el certificado></label><input type='number' id='expira' name='expira' value='1' min='1' max='4' required><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        // -----------------------------------------  Consola 7 - Gestionar Certificados  ---------------------------------------------------------
        $solo_lectura = "";
        if ($puede_eliminar_certificados and !$puede_modificar_certificados) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_cert' class='consolas' style='display: none;'>
            <table class='cabecera-tabla'>
                <tr>
                    <th>Nombre</th>
                    <th>Descargar Certificado</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Expiración</th>
        ";
        if ($puede_eliminar_certificados) { echo "            <th>Eliminar</th>"; }
        echo "  
                    <td>Asociar Certificados</td>
                </tr>
        ";

        $certs = mysqli_query($conexion, $Q_certs);
        if ($certs and mysqli_num_rows($certs) > 0) {
            while ($cert = mysqli_fetch_assoc($certs)) {
                $id_cert = $cert['id'];
                $nombre_certificado = $cert['nombre'];
                $f_creado = $cert['f_creado'];
                $f_expira = $cert['expira'];
                echo "
                <tr>
                    <td><form action='Scripts/Modificar/modificar-certificado.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres eliminar el certificado?\");'>
                    <input type='text' name='nombre' value='$nombre_certificado' size='20' maxlength='20' required></td>
                    <td><a href='Scripts/descargar-cert.php?nombre=".htmlspecialchars($nombre_certificado)."'>Descargar</a></td>
                    <td><p>$f_creado</p></td>
                    <td><p>$f_expira</p>
                ";
                if ($puede_eliminar_certificados) { echo "      </td><td><input class='eliminar' type='submit' value='' formaction='Scripts/Eliminar/eliminar-cert.php'>"; }
                echo "
                    </form></td>
                    <td><form action='Scripts/Modificar/asociar-certificado-api.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres asociar el certificado?\");'>
                    <input type='hidden' name='id_cert' value='$id_cert' required>
                    <select name='id_api' required>
                ";
                $q_cert_apis = "
                    select 
                        a.*
                    from api a
                    where not exists (
                        select 1 from api_certificados ac
                        where ac.id_api = a.id and ac.id_certificado = $id_cert
                    )
                    order by a.tenant
                ";
                $cert_apis = mysqli_query($conexion, $q_cert_apis);
                if ($cert_apis and mysqli_num_rows($cert_apis) > 0) {
                    $grupos = [];
                    while ($cert_api = mysqli_fetch_assoc($cert_apis)) {
                        $c_id_api = $cert_api['id'];
                        $c_tenant = $cert_api['tenant'];
                        $c_sitio = $cert_api['sitio'];
                        if (!isset($grupos[$c_tenant])) {
                            $grupos[$c_tenant] = [];
                        }
                        $grupos[$c_tenant][] = [$c_id_api,$c_sitio];
                    }
                    foreach ($grupos as $grupo => $sitios) {
                        echo "      <optgroup label='$grupo'>";
                        foreach ($sitios as $sitio) {
                            echo "          <option value='{$sitio[0]}'>Sitio: {$sitio[1]}</option>";
                        }
                        echo "      </optgroup>";
                    }
                }
                echo "
                    </select>
                    <input type='submit' value='Asociar'>
                    </form></td>
                </tr>
                ";
            }
        }
        echo "
            </table>
        </div>
        ";
        // -----------------------------------------  Consola 8 Crear usuarios  ---------------------------------------------------------
        echo "
        <form id='consola_signup' class='consolas' action='Scripts/Crear/crear-usuario.php' method='POST' style='display: none;'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' maxlength='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' minlength='8' maxlength='20' required><br>
            <label class='prompt' for='correo'>PS C:\Escriba su correo electrónico></label><input type='email' id='correo' name='correo' placeholder='|' size='50' minlength='9' maxlength='50'><br>
            <label class='prompt' for='nombre'>PS C:\Escriba su nombre></label><input type='text' id='nombre' name='nombre' placeholder='|' size='20' minlength='2' maxlength='20'><br>
            <label class='prompt' for='apellidos'>PS C:\Escriba su[s] apellido[s]></label><input type='text' id='apellidos' name='apellidos' placeholder='|' size='50' minlength='2' maxlength='50'><br>
            <label class='prompt' for='activo'>PS C:\Marque si quiere activar el usuario></label><input type='checkbox' id='activo' name='activo' checked><br>
            <label class='prompt' for='id_rol'>PS C:\Seleccione el rol del usuario></label>
            <select id='id_rol' name='id_rol'>
        ";
        $cu_roles = mysqli_query($conexion,$Q_roles);
        // Recorremos los roles que existen
        if ($cu_roles and mysqli_num_rows($cu_roles) > 0) {
            while ($cu_rol = mysqli_fetch_assoc($cu_roles)) {
                echo "<option value='{$cu_rol['id']}'>{$cu_rol['rol']}</option>";
            }
        }
        echo "
            </select><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
        // -----------------------------------------  Consola 9 Gestión de usuarios  ---------------------------------------------------------
        $solo_lectura = "";
        if (!$puede_modificar_usuarios) {$solo_lectura = "readonly";}
        echo "
        <div id='consola_g_usuarios' class='consolas' style='display: none;'>
            <table class='cabecera-tabla'>
                <tr>
                    <th>Usuario</th>
                    <th>Clave</th>
                    <th>Correo</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Activo</th>
                    <th>Rol</th>
                    <th>Creado el</th>
                    <th>Última Sesión</th>
        ";
        if ($puede_modificar_usuarios) { echo "            <th>Actualizar</th>"; }
        if ($puede_eliminar_usuarios) { echo "            <th>Eliminar</th>"; }
        echo "  </tr>";

        $usuarios = mysqli_query($conexion, $Q_usuarios);
        if ($usuarios and mysqli_num_rows($usuarios) > 0) {
            while ($usuario = mysqli_fetch_assoc($usuarios)) {
                $activo = '';
                if ($usuario['activo']) { $activo = 'checked'; }
                echo "
                <tr>
                    <td><form action='Scripts/Modificar/modificar-usuario.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar este usuario?\");'>
                    <input type='text' name='usuario' value='{$usuario['usuario']}' size='20' maxlength='20' required $solo_lectura></td>
                    <td><input type='password' name='clave' placeholder='Nueva contraseña' size='20' minlength='8' maxlength='20'></td>
                    <td><input type='email' name='correo' value='{$usuario['correo']}' size='50' minlength='9' maxlength='50'></td>
                    <td><input type='text' name='nombre' value='{$usuario['nombre']}' size='20' minlength='2' maxlength='20'></td>
                    <td><input type='text' name='apellidos' value='{$usuario['apellidos']}' size='50' minlength='2' maxlength='50'></td>
                    <td><input type='checkbox' name='activo' $activo></td>
                    <td><select name='id_rol'>
                ";
                $gu_roles = mysqli_query($conexion, $Q_roles);
                if ($gu_roles and mysqli_num_rows($gu_roles) > 0) {
                    while ($gu_rol = mysqli_fetch_assoc($gu_roles)) {
                        if ($gu_rol['id'] == $usuario['id_rol']) {
                            echo "<option value='{$gu_rol['id']}' selected>{$gu_rol['rol']}</option>";
                        } else {
                            echo "<option value='{$gu_rol['id']}'>{$gu_rol['rol']}</option>";
                        }
                    }
                }
                echo "
                    </select></td>
                    <td><span>{$usuario['f_creado']}</span></td>
                    <td><span>{$usuario['ult_sesion']}</span>
                ";
                if ($puede_modificar_usuarios) { echo "   </td><td><input class='editar' type='submit' value=''>"; }                
                if ($puede_eliminar_usuarios) { echo "   </td><td><input class='eliminar' type='submit' value='' formaction='Scripts/Eliminar/eliminar-usuario.php'>"; }
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
    echo "</div>";

    include 'footer.php';
?>