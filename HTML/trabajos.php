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
            echo "<li id='p_m_trabajos' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_m_trabajos\",\"Monitor_Trabajos\")'>Monitor Trabajos</a></li>";
            echo "<li id='p_trabajos_comp' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_trabajos_comp\",\"Trabajos_Completados\")'>Trabajos Completados</a></li>";
            ?>
            </ul>
        </nav>
<?php
    // -----------------------------------------  Monitor 1 - Trabajos en tiempo real ---------------------------------------------------------
    echo "
    <div id='Monitor_Trabajos' class='monitor' style='display: none;'>
    </div>
    ";
    // -----------------------------------------  Monitor 2 - Trabajos  ---------------------------------------------------------
    //$solo_lectura = "";
    //if (!$puede_modificar_api) {$solo_lectura = "readonly";}
    echo "
    <div id='Trabajos_Completados' class='monitor'>
        <table class='cabecera-tabla'>
            <tr>
                <th>ID</th>
                <th>ID API</th>
                <th>Nombre Contenedor</th>
                <th>ID Trabajo</th>
                <th>Trabajo</th>
                <th>Estado</th>
                <th>Salida</th>
                <th>Error</th>
                <th>Progreso</th>
                <th>Fecha de Finalización</th>
    ";
    if ($puede_modificar_trabajos) { echo "            <th>Actualizar</th>"; }
    if ($puede_eliminar_trabajos) { echo "            <th>Eliminar</th>"; }
    echo "  </tr>";

    $trabajos = mysqli_query($conexion, $Q_trabajos);
    if ($trabajos and mysqli_num_rows($trabajos) > 0) {
        while ($trabajo = mysqli_fetch_assoc($trabajos)) {
            $id = $trabajo['id'];
            $id_api = $trabajo['id_api'];
            $nombre_contenedor = $trabajo['nombre_contenedor'];
            $id_trabajo = $trabajo['id_trabajo'];
            $comando = $trabajo['trabajo'];
            $estado = $trabajo['estado'];
            $salida = $trabajo['salida'];
            $error = $trabajo['error'];
            $progreso = $trabajo['progreso'];
            $f_finalizacion = $trabajo['f_finalizacion'];
            echo "
            <tr>
                <td><form action='Scripts/modificar-trabajo.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar el trabajo?\");'>
                <input type='number' name='id' value=$id required readonly></td>
                <td><input type='number' name='id_api' value=$id_api readonly></td>
                <td><input type='text' name='contenedor' value='$nombre_contenedor' size='30' readonly></td>
                <td><input type='number' name='id_trabajo' value=$id_trabajo readonly></td>
                <td><input type='text' name='trabajo' value='$comando' size='30' readonly></td>
                <td><input type='text' name='estado' value='$estado' size='30' readonly></td>
                <td><input type='text' name='salida' value='$salida' size='30' readonly></td>
                <td><input type='text' name='error' value='$error' size='30' readonly></td>
                <td><input type='text' name='progreso' value='$progreso' size='30' readonly></td>
                <td><p>$f_finalizacion</p>
            ";
            if ($puede_modificar_trabajos) { echo "  </td><td><input type='submit' value='Modificar'>"; }
            if ($puede_eliminar_trabajos) { echo "   </td><td><input type='submit' formaction='Scripts/eliminar-trabajo.php' value='Eliminar'>"; }
            echo "
                </form></td>
            </tr>
            ";
        }
    } else {
        echo "
        <tr>
            <td>No se encontró nada.</td>
        </tr>
        ";
    }
    echo "
        </table>
    </div>
</div>
    ";
    include 'footer.php';
?>