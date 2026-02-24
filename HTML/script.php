<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
</head>
<body>
    <div id="centro">
	<?php
		shell_exec('mkdir caca');
		$output = shell_exec('ls -lart');
		echo "<pre>$output</pre>";
	?>
	<a href="index.php">Volver</a>
    </div>
</body>
</html>
