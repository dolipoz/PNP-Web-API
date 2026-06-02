<?php
    include 'head.php';
    if (!$_SESSION["login"]) {
        header('Location: index.php');
    }
?>
    <div id="ventana_tareas">
        <div>
            <h1>Gestión de Procesos</h1>
        </div>
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
            <?php
            echo "<li id='p_m_tareas' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_m_tareas\",\"monitor_tareas\")'>Monitor Tareas</a></li>";
            echo "<li id='p_tareas_comp' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_tareas_comp\",\"tareas_completadas\")'>Tareas Completados</a></li>";
            echo "<li id='p_tareas_lanz' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_tareas_lanz\",\"lanzar_tareas\")'>Lanzar Tareas</a></li>";
            ?>
            </ul>
        </nav>
<?php
    // -----------------------------------------  Monitor 1 - tareas en tiempo real ---------------------------------------------------------
    echo "
    <div id='monitor_tareas' class='monitor' style='display: none;'>
    </div>
    ";
    // -----------------------------------------  Monitor 2 - tareas  ---------------------------------------------------------
    //$solo_lectura = "";
    //if (!$puede_modificar_api) {$solo_lectura = "readonly";}
    echo "
    <div id='tareas_completadas' class='monitor'>
        <button onclick='location.reload()'>Recargar Tareas</button>
        <table class='cabecera-tabla'>
            <tr>
                <th>ID</th>
                <th>ID API</th>
                <th>Nombre Contenedor</th>
                <th>ID Trabajo</th>
                <th>Comando</th>
                <th>Estado</th>
                <th>Salida</th>
                <th>Error</th>
                <th>Progreso</th>
                <th>Fecha de Finalización</th>
    ";
    if ($puede_modificar_tareas) { echo "            <th>Actualizar</th>"; }
    if ($puede_eliminar_tareas) { echo "            <th>Eliminar</th>"; }
    echo "  </tr>";
    $q_tareas = "select * from tareas where estado = 'completada'";
    $tareas = mysqli_query($conexion, $q_tareas);
    if ($tareas and mysqli_num_rows($tareas) > 0) {
        while ($tarea = mysqli_fetch_assoc($tareas)) {
            $id = $tarea['id'];
            $id_api = $tarea['id_api'];
            $nombre_contenedor = $tarea['nombre_contenedor'];
            $id_tarea = $tarea['id_tarea'];
            $comando = $tarea['comando'];
            $estado = $tarea['estado'];
            $salida = $tarea['salida'];
            $error = $tarea['error'];
            $progreso = $tarea['progreso'];
            $f_finalizacion = $tarea['f_finalizacion'];
            echo "
            <tr>
                <td><form action='Scripts/modificar-tarea.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar el tarea?\");'>
                <input type='number' name='id' value='$id' required readonly></td>
                <td><input type='number' name='id_api' value='$id_api' readonly></td>
                <td><input type='text' name='contenedor' value='$nombre_contenedor' size='30' readonly></td>
                <td><input type='number' name='id_tarea' value='$id_tarea' readonly></td>
                <td><input type='text' name='comando' value='$comando' size='30' readonly></td>
                <td><input type='text' name='estado' value='$estado' size='30' readonly></td>
                <td><input type='text' name='salida' value='$salida' size='30' readonly></td>
                <td><input type='text' name='error' value='$error' size='30' readonly></td>
                <td><input type='text' name='progreso' value='$progreso' size='30' readonly></td>
                <td><p>$f_finalizacion</p>
            ";
            if ($puede_modificar_tareas) { echo "  </td><td><input type='submit' value='Modificar'>"; }
            if ($puede_eliminar_tareas) { echo "   </td><td><input type='submit' formaction='Scripts/eliminar-tarea.php' value='Eliminar'>"; }
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
    ";
    // -----------------------------------------  Monitor 3 - Lanzar Tareas  ---------------------------------------------------------
    echo "
    <form id='lanzar_tareas' class='monitor' action='Scripts/Crear/lanzar-tarea.php' method='POST' style='display: none;'>
        <label class='lanzador' for='script'>Elija que acción quiere lanzar </label>
        <select id='script' name='script' widht='10'>
            <option value='ver-carpetas.ps1' selected>Mostrar Carpetas y Permisos</option>
            <option value='resetear-permisos.ps1'>Resetear Permisos de todas las carpetas</option>
            <option value='cambiar-permisos.ps1'>Cambiar Permisos a partir de CSV</option>
        </select><br>
        <label class='lanzador' for='apis' required>Elija la api</label>
        <select id='apis' name='apis'>
    ";
    $apis = mysqli_query($conexion, $Q_apis);
    if ($apis and mysqli_num_rows($apis) > 0) {
        $grupos = [];
        while ($api = mysqli_fetch_assoc($apis)) {
            $id_api = $api['id'];
            $tenant = $api['tenant'];
            $sitio = $api['sitio'];
            if (!isset($grupos[$tenant])) {
                $grupos[$tenant] = [];
            }
            $grupos[$tenant][] = [$id_api,$sitio];
        }
        foreach ($grupos as $grupo => $sitios) {
            echo "      <optgroup label='$grupo'>";
            foreach ($sitios as $sitio) {
                echo "          <option value='".$sitio[0]."'>Sitio: ".$sitio[1]."</option>";
            }
            echo "      </optgroup>";
        }
    }
    echo "
        </select><br>
        <label class='lanzador' for='certificado' required>Seleccione el certificado asociado a la API</label>
        <select id='certificado' name='certificado'>
        </select><br>
        <label class='lanzador'>Pulse Aceptar para lanzar la tarea ></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
    </form>
    ";
    echo "</div>";
    include 'footer.php';
?>