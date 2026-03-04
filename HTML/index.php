<?php
    include "head.php";
?>
    <div id="ventana">
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
                <?php if (!$_SESSION["login"]) echo '<li id="login" class="pestanias"><a href="">Iniciar Sesión</a></li>' ?>
                <?php if ($_SESSION["login"]) echo '<li id="signup" class="pestanias"><a href="">Crear Usuario</a></li>' ?>
                <?php if ($_SESSION["login"]) echo '<li id="logoff" class="pestanias"><a href="">Cerrar Sesión</a></li>' ?>
            </ul>
        </nav>

        <form id="consola" method="POST">
            <label class="prompt" for="user">PS C:\Escriba su usuario></label><input type="text" id="user" name="user" placeholder="|"><br>
            <label class="prompt" for="pass">PS C:\Escriba su contraseña></label><input type="text" id="pass" name="pass" placeholder="|">
        </form>
    </div>
</body>
</html>
