<?php
    include "head.php";
?>
        <form id="consola" action="Scripts/conectar.php" method="POST">
            <label class="prompt" for="url">PS C:\Escriba su url></label><input type="text" id="url" name="url" placeholder="|" size="20" min="8" max="20" required><br>
            <label class="prompt" for="tenant">PS C:\Escriba su tenant></label><input type="text" id="tenant" name="tenant" placeholder="|" size="20" min="8" max="20" required><br>
            <label class="prompt" for="tenantId">PS C:\Escriba su tenantId></label><input type="text" id="tenantId" name="tenantId" placeholder="|" size="20" min="8" max="20" required><br>
            <label class="prompt" for="clientId">PS C:\Escriba su clientId></label><input type="text" id="clientId" name="clientId" placeholder="|" size="20" min="8" max="20" required><br>
            <label class="prompt" for="clientSecret">PS C:\Escriba su clientSecret></label><input type="text" id="clientSecret" name="clientSecret" placeholder="|" size="20" min="8" max="20" required>
            <br>
            <label class="prompt">PS C:\Haga click en Aceptar para continuar></label><input type="submit" value="Aceptar"> / <input type="reset" value="Reiniciar">
        </form>
    </div>
<?php
    include "footer.php";
?>