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
            <p>Usuario conectado</p>
            <form id='mod_perfil' action='Scripts/cerrar-sesion.php' method='POST' style='display: none;'>
                <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
            </form>
        </div>
        <form id='consola4' class='consolas' action='Scripts/agregar-usuarios.php' method='POST' style='display: none;'>
            <label class='prompt' for='usuario'>PS C:\Escriba su usuario></label><input type='text' id='usuario' name='usuario' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='clave'>PS C:\Escriba su contraseña></label><input type='password' id='clave' name='clave' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='correo'>PS C:\Escriba su correo electrónico></label><input type='correo' id='correo' name='correo' placeholder='|' size='50' min='9' max='50'><br>
            <label class='prompt' for='nombre'>PS C:\Escriba su nombre></label><input type='text' id='nombre' name='nombre' placeholder='|' size='20' min='2' max='20'><br>
            <label class='prompt' for='apellidos'>PS C:\Escriba su[s] apellido[s]></label><input type='text' id='apellidos' name='apellidos' placeholder='|' size='50' min='2' max='50'><br>
            <label class='prompt' for='activo'>PS C:\Marque si quiere activar el usuario></label><input type='checkbox' id='activo' name='activo'><br>
            <label class='prompt' for='rol'>PS C:\Seleccione el rol del usuario></label>
            <select id='rol' name='rol'>
                <option value='1'>Admin</option>
            </select><br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
    }
?>
    </div>
<?php
    include 'footer.php';
?>