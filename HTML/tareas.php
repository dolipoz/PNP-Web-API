<?php
    include 'head.php';
    if (!$_SESSION["login"]) {
        header('Location: index.php');
    }
?>
    <div id="ventana_tareas">
        <div id='cabecera_tareas'>
            <h1>Gestión de Tareas</h1>
        </div>
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
            <?php
            echo "<li id='p_m_tareas' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_m_tareas\",\"monitor_tareas\")'>Monitor de Tareas</a></li>";
            echo "<li id='p_tareas_comp' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_tareas_comp\",\"tareas_completadas\")'>Tareas</a></li>";
            if ($puede_crear_tareas) {
                echo "<li id='p_tareas_lanz' class='pestanias'><a href='#' onclick='mostrarMonitor(\"p_tareas_lanz\",\"lanzar_tareas\")'>Lanzar Tareas</a></li>";
            }
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
    echo "
    <div id='tareas_completadas' class='monitor'>
        <button class='reiniciar' onclick='location.reload()'></button>
        <table class='cabecera-tabla'>
            <tr>
                <th>ID</th>
                <th>ID API</th>
                <th>Nombre Contenedor</th>
                <th>ID Tarea</th>
                <th>Estado</th>
                <th>Salida</th>
                <th>Error</th>
                <th>Progreso</th>
                <th>Fecha de Finalización</th>
    ";
    //if ($puede_modificar_tareas) { echo "            <th>Actualizar</th>"; }
    if ($puede_eliminar_tareas) { echo "            <th>Eliminar</th>"; }
    echo "  </tr>";
    $q_tareas = "select * from tareas where estado = 'completada' or estado = 'fallida' or estado = 'pendiente'";
    $tareas = mysqli_query($conexion, $q_tareas);
    if ($tareas and mysqli_num_rows($tareas) > 0) {
        while ($tarea = mysqli_fetch_assoc($tareas)) {
            $id = $tarea['id'];
            $id_api = $tarea['id_api'];
            $nombre_contenedor = $tarea['nombre_contenedor'];
            $id_tarea = $tarea['id_tarea'];
            $comando = $tarea['comando'];
            $estado = $tarea['estado'];
            $salida = isset($tarea['salida']) ? $tarea['salida'] : null;
            $error = $tarea['error'];
            $progreso = $tarea['progreso'];
            $f_finalizacion = $tarea['f_finalizacion'];
            echo "
            <tr>
                <td><form action='Scripts/Modificar/modificar-tarea.php' method='POST' class='filas-tabla' onsubmit='return confirm(\"¿Seguro que quieres modificar/eliminar la tarea?\");'>
                <input type='number' name='id' value='$id' required readonly></td>
                <td><input type='number' name='id_api' value='$id_api' readonly></td>
                <td><input type='text' name='contenedor' value='$nombre_contenedor' size='30' readonly></td>
                <td><input type='number' name='id_tarea' value='$id_tarea' readonly></td>
                <td><input type='hidden' name='comando' value='$comando'>
                <input type='text' name='estado' value='$estado' size='30' readonly></td>
            ";
            if ($salida) {
                echo "<td><input type='hidden' name='salida' value='$salida'><input type='submit' formaction='ver-carpetas.php' value='Mostrar Carpetas'></td>";
            } else {
                echo "<td>Null</td>";
            }
            echo "
                <td><input type='text' name='error' value='$error' size='30' readonly></td>
                <td><input type='text' name='progreso' value='$progreso' size='30' readonly></td>
                <td><p>$f_finalizacion</p>
            ";
            //if ($puede_modificar_tareas) { echo "  </td><td><input class='editar' type='submit' value=''>"; }
            if ($puede_eliminar_tareas) { echo "   </td><td><input class='eliminar' type='submit' value='' formaction='Scripts/Eliminar/eliminar-tarea.php'>"; }
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
    <div id='lanzar_tareas' class='monitor' style='display: none;'>
        <h1>Lanzar Tareas a Powershell</h1>
        <form enctype='multipart/form-data' action='Scripts/Crear/lanzar-tarea.php' method='POST'>
            <div>
                <i class='i_iconos i_pwsh'></i>
                <label class='lanzador' for='script'>Elegir Tarea: </label>
                <select id='script' name='script'>
                    <option value='ver-carpetas.ps1' selected>Mostrar Carpetas y Permisos</option>
                    <option value='resetear-permisos.ps1'>Resetear Permisos de todas las carpetas</option>
                    <option value='cambiar-permisos.ps1'>Cambiar Permisos a partir de CSV</option>
                </select>
            </div>
            <div>
                <i class='i_iconos i_api'></i>
                <label class='lanzador' for='apis'>Elija la api</label>
                <select id='apis' name='apis' style='display:none;' required>
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
                    </select>
                </div>
                <div>
                    <i class='i_iconos i_certificado'></i>
                    <label class='lanzador' for='certificado'>Seleccione el certificado asociado a la API</label>
                    <select id='certificado' name='certificado' required>
                    </select>
                </div>
                <div>
                    <i class='i_iconos i_csv'></i>
                    <label id='etiqueta_csv' class='lanzador' for='csv'>Suba el csv con los permisos asociados </label>
                    <input type='file' id='csv' name='csv'>
                </div>
                <button><i class='i_iconos i_ejecucion'></i><input type='submit' value='Aceptar'></button>
                <button><i class='i_iconos i_reiniciar'></i><input type='reset' value='Reiniciar'></button>
        </form>
    </div>
    ";
    echo "</div>";
    include 'footer.php';
?>