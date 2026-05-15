<?php
    include 'head.php';
?>
<?php
    if (!$_SESSION["login"]) {
        echo "
        <form id='consola1' class='consolas' action='Scripts/iniciar-sesion.php' method='POST'>
            <label class='prompt' for='user'>PS C:\Escriba su usuario></label><input type='text' id='user' name='user' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='pass'>PS C:\Escriba su contraseña></label><input type='password' id='pass' name='pass' placeholder='|' size='20' min='8' max='20' required>
            <br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
    } else {
        echo "
        <form id='consola2' class='consolas' action='Scripts/cerrar-sesion.php' method='POST'>
            <label class='prompt'>PS C:\Haga click en Cerrar Sesión para salir></label><input type='submit' value='Cerrar Sesión'>
        </form>
        <form id='consola3' class='consolas' action='Scripts/agregar-usuarios.php' method='POST'>
            <label class='prompt' for='user'>PS C:\Escriba su usuario></label><input type='text' id='user' name='user' placeholder='|' size='20' min='8' max='20' required><br>
            <label class='prompt' for='pass'>PS C:\Escriba su contraseña></label><input type='password' id='pass' name='pass' placeholder='|' size='20' min='8' max='20' required>
            <label class='prompt' for='email'>PS C:\Escriba su correo electrónico></label><input type='email' id='email' name='email' placeholder='|' size='50' min='9' max='50'><br>
            <label class='prompt' for='nombre'>PS C:\Escriba su nombre></label><input type='text' id='nombre' name='nombre' placeholder='|' size='20' min='2' max='20'><br>
            <label class='prompt' for='apellidos'>PS C:\Escriba su[s] apellido[s]></label><input type='text' id='apellidos' name='apellidos' placeholder='|' size='50' min='2' max='50'><br>
            <label class='prompt' for='activo'>PS C:\Marque si quiere activar el usuario></label><input type='checkbox' id='activo' name='activo'><br>
            <label class='prompt' for='rol'>PS C:\Seleccione el rol del usuario></label>
            <select id='rol' name='rol'>
                <option value='1'>Admin</option>
            </select><br>
            <br>
            <label class='prompt'>PS C:\Haga click en Aceptar para continuar></label><input type='submit' value='Aceptar'> / <input type='reset' value='Reiniciar'>
        </form>
        ";
    }
?>
    </div>
<?php
    include 'footer.php';
?>