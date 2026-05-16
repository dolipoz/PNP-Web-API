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
            <h2>Fecha Creación: ".$_SESSION['usuario']['f_creado']."</h2>
            <h2>Última Sesión: ".$_SESSION['usuario']['ult_sesion']."</h2>

            <form id='mod_perfil' action='Scripts/cerrar-sesion.php' method='POST' style='display: none;'>
                <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
            </form>
        </div>
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
        // Recorremos la lista que en caso de encontrar usuarios es solo uno ya que no se puede repetir el nombre de usuario
        if ($roles) {
            while ($rol=mysqli_fetch_assoc($roles)) {
                echo "<option value='".$rol['id']."'>".$rol['rol']."</option>";
            }
        }
        echo "
            </select><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        <form id='consola5' class='consolas' action='Scripts/gestionar-usuarios.php' method='POST' style='display: none;'>
            <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
        </form>
        ";
    }
?>
    </div>
<?php
    include 'footer.php';
?>

