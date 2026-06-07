<?php
    include 'head.php';
    if (!$_SESSION["login"]) {
        header('Location: index.php');
    }
    if (isset($_POST['salida'])) {
        $salida = $_POST['salida'];
    } else {
        $_SESSION["error"] = True;
        $_SESSION["info"] = "No se encontró información de salida.";
        header('Location: tareas.php');
    }
    $id = $_POST['id'];
    $id_api = $_POST['id_api'];
    $q_apis = "select * from api where id = $id_api";
    $apis = mysqli_query($conexion,$q_apis);
    $api = mysqli_fetch_assoc($apis);
    $tenant = $api['tenant'];
    $sitio = $api['sitio'];

    $carpetas = json_decode($salida, JSON_UNESCAPED_UNICODE);
    ksort($carpetas);
?>
    <div id="ventana_tareas">
        <div>
            <h1>Info de Carpetas de <?php echo "$tenant/$sitio de la tarea con ID: $id"; ?></h1>
        </div>
        <nav>
            <ul>
            </ul>
        </nav>
<?php
    // -----------------------------------------  Mostrar Carpetas  ---------------------------------------------------------
    echo "
    <div class='carpetas'>
        <table class='cabecera-tabla'>
    ";
    if ($carpetas) {
        echo "
            <tr>
                <th>Nombre de Carpeta</th>
                <th>Grupos y Permisos</th>
            </tr>
        ";
        foreach ($carpetas as $dir => $grupo_permisos) {
            echo "
            <tr>
                <td><h2>$dir</h2></td>
            ";
            foreach ($grupo_permisos as $grupo => $permisos) {
                echo "<td>Grupo: $grupo -- Puede: $permisos</td>";
            }
            echo "
                
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
    echo "</div>";
    include 'footer.php';
?>