    <footer>
        <div>
            <span>© 2026 Daniel Olivares Pozo</span>
        </div>
    </footer>
    <script>
        let monitor = null;
        function mostrarConsola(idp,idc) {
            // Cambia el background de todos los elementos con clase "pestanias"
            const pestaniasC = document.querySelectorAll('.pestanias');
            pestaniasC.forEach(function (pc) {
                pc.style.backgroundColor = '#1e1e1e';
            });
            document.getElementById(idp).style.backgroundColor = '#012456';

            // Oculta todos los elementos con clase "consolas"
            const consolas = document.querySelectorAll('.consolas');
            consolas.forEach(function (c) {
                c.style.display = 'none';
            });
            // Muestra el elemento seleccionado
            document.getElementById(idc).style.display = 'block';
        }
        function mostrarMonitor(idp,idc) {
            // Cambia el background de todos los elementos con clase "pestanias"
            const pestaniasM = document.querySelectorAll('.pestanias');
            pestaniasM.forEach(function (pm) {
                pm.style.backgroundColor = '#e5e5e5';
            });
            document.getElementById(idp).style.backgroundColor = '#d9efd0';

            // Oculta todos los elementos con clase "monitor"
            const monitores = document.querySelectorAll('.monitor');
            monitores.forEach(function (m) {
                m.style.display = 'none';
            });
            // Muestra el elemento seleccionado
            document.getElementById(idc).style.display = 'block';

            // Parte añadida para el monitor a tiempo real de tareas
            // Detenemos el monitor si está activado
            clearInterval(monitor);
            // si se abre la pestaña de APIs
            if (idc === 'monitor_tareas') {
                actualizarMonitor();
                monitor = setInterval( actualizarMonitor, 5000 );
            }
        }
        function actualizarMonitor() {
            fetch('Scripts/monitor.php')
                .then(response => response.json())
                .then(datos => {
                    let tareas = `
                        <table class='cabecera-tabla'>
                            <tr>
                                <th>Cantidad Pendientes</th>
                                <th>Cantidad Corriendo</th>
                                <th>Cantidad Completadas</th>
                                <th>Cantidad Fallidas</th>
                            </tr>
                            <tr>
                                <td>${datos.pendientes}</td>
                                <td>${datos.corriendo}</td>
                                <td>${datos.completadas}</td>
                                <td>${datos.fallidas}</td>
                            </tr>
                        </table>
                        <table class='cabecera-tabla'>
                            <tr>
                                <th>ID</th>
                                <th>ID API</th>
                                <th>Nombre Contenedor</th>
                                <th>ID Tarea</th>
                                <th>Comando</th>
                                <th>Estado</th>
                                <th>Salida</th>
                                <th>Error</th>
                                <th>Progreso</th>
                                <th>Fecha de Finalización</th>
                            </tr>
                    `;
                    datos.tareas.forEach(function(t) {
                        tareas += `
                            <tr>
                                <td>${t.id}</td>
                                <td>${t.id_api}</td>
                                <td>${t.nombre_contenedor}</td>
                                <td>${t.id_tarea}</td>
                                <td>${t.comando}</td>
                                <td>${t.estado}</td>
                                <td>${t.salida}</td>
                                <td>${t.error}</td>
                                <td>${t.progreso}</td>
                                <td>${t.f_finalizacion}</td>
                            </tr>
                        `;
                    });
                    tareas += '</table>';
                    document.getElementById('monitor_tareas').innerHTML = tareas;
                })
                .catch(error => {
                    document.getElementById('monitor_tareas').innerHTML = 'Error al cargar datos';
                    console.error(error);
                });
        }
        function cargarCertificados() {
            fetch("Scripts/certificados-de-api.php?id=" + this.value)
                .then(response => response.json())
                .then(datos => {
                    const select = document.getElementById("certificado");
                    select.innerHTML = "";
                    datos.forEach(certificados => {
                        const option = document.createElement("option");
                        option.value = certificados;
                        option.innerHTML = certificados;
                        select.appendChild(option);
                    });
                });
        }
        document.getElementById("apis").addEventListener("change", cargarCertificados);
        const apis = document.getElementById("apis");
        apis.dispatchEvent(new Event("change"));
        // Función para cambiar el Action del formulario para Cambiar Permisos
        function cambiarFormulario() {
            let script = document.getElementById("script").value;
            if (script === 'cambiar-permisos.ps1') {
                document.getElementById("etiqueta_csv").style.display = 'inline';
                document.getElementById("csv").style.display = 'inline';
                document.getElementById("lanzar_tareas").action = "Scripts/cambiar-permisos.php";
            } else {
                document.getElementById("etiqueta_csv").style.display = 'none';
                document.getElementById("csv").style.display = 'none';
                document.getElementById("lanzar_tareas").action = "Scripts/lanzar-tarea.php";
            }
        }
        document.getElementById("script").addEventListener("change", cambiarFormulario);
        const script = document.getElementById("script");
        script.dispatchEvent(new Event("change"));
    </script>
</body>
</html>