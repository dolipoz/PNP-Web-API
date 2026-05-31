

<?php
    include 'head.php';
    if (!$_SESSION["login"]) {
        header('Location: index.php');
    }
?>
    <div id="ventana_api">
        <div>
            <h1>Gestión de Procesos</h1>
        </div>
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
            <?php
            if ($puede_crear_api) {
                echo "<li id='addapi' class='pestanias'><a href='#' onclick='mostrarConsola(\"addapi\",\"consola_addapi\")'>Crear API</a></li>";
            }
            if ($puede_modificar_api or $puede_eliminar_api) {
                echo "<li id='gestion_api' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_api\",\"consola_g_api\")'>Gestionar APIs</a></li>";
            }
            ?>
            </ul>
        </nav>


<?php
    // -----------------------------------------  Monitor 1 - Trabajos  ---------------------------------------------------------
    $solo_lectura = "";
    if (!$puede_modificar_api) {$solo_lectura = "readonly";}
    echo "
    <div id='Trabajos' class='monitor'>
        <div id='Monitor_Trabajos'></div>
        <table class='cabecera-tabla'>
            <tr>
                <td>Tenant</td>
                <td>URL</td>
                <td>Cliente ID</td>
    ";
    if ($puede_modificar_api) { echo "            <td>Actualizar</td>"; }
    if ($puede_eliminar_api) { echo "            <td>Eliminar</td>"; }
    echo "      <td>Certificados Asociados</td>";
    echo "  </tr>";

    $apis = mysqli_query($conexion, $q_apis);
    if ($apis and mysqli_num_rows($apis) > 0) {
        while ($api = mysqli_fetch_assoc($apis)) {
            $tenant = $api['tenant'];
            $sitio = $api['sitio'];
            $id_cliente = $api['id_cliente'];
            echo "
            <tr>
                <td><form action='Scripts/modificar-api.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar la api?\");'>
                <input type='text' name='tenant' value='$tenant' size='20' maxlength='50' required></td>
                <td><input type='text' name='sitio' value='$sitio' size='30' minlength='8' maxlength='255' required></td>
                <td><input type='text' name='id_cliente' value='$id_cliente' size='30' minlength='9' maxlength='255' required>
            ";
            if ($puede_modificar_api) { echo "  </td><td><input type='submit' value='Modificar'>"; }
            if ($puede_eliminar_api) { echo "   </td><td><input type='submit' formaction='Scripts/eliminar-api.php' value='Eliminar'>"; }
            echo "
                </td><td><select name='certificados'>
            ";
            $q_cert = "select certs.* from certificados certs inner join api_certificados api_cert on api_cert.id_certificado = certs.id where api_cert.id_api = ".$api['id'];
            $certificados = mysqli_query($conexion, $q_cert);
            if ($certificados and mysqli_num_rows($certificados) > 0) {
                while ($certificado = mysqli_fetch_assoc($certificados)) {
                    echo "      <option value='".$certificado['id']."'>".$certificado['nombre']."</option>";
                }
            }
            echo "
                    </select>
                </form></td>
            </tr>
            ";
        }
    }
    echo "
        </table>
    </div>
    ";
?>
    </div>
<?php
    include 'footer.php';
?>