<?php
    include "head.php";
?>
        <form id="consola" action="" method="POST">
            <label class="prompt" for="user">PS C:\Escriba su usuario></label><input type="text" id="user" name="user" placeholder="|" size="20" min="8" max="20" required><br>
            <label class="prompt" for="pass">PS C:\Escriba su contraseña></label><input type="text" id="pass" name="pass" placeholder="|" size="20" min="8" max="20" required>
            <br>
            <label class="prompt">PS C:\Haga click en Aceptar para continuar></label><input type="submit" value="Aceptar"> / <input type="reset" value="Reiniciar">
        </form>
    </div>
<?php
    include "footer.php";
?>