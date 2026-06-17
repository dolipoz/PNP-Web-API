    <footer>
        <div>
            <span>© 2026 Daniel Olivares Pozo</span>
        </div>
    </footer>
    <script>
        let monitor = null;
        let permisos = false;
        let mod_perfil = false;
        // Función para mostrar u ocultar en el perfil de usuario los permisos
        function mostrarPermisos() {
            if (permisos === true) {
                document.getElementById('mostrar_permisos').innerHTML = " > Mostrar Permisos";
                document.getElementById("p_permisos").style.display = 'none';
                permisos = false;
            } else {
                document.getElementById('mostrar_permisos').innerHTML = " V Mostrar Permisos";
                document.getElementById("p_permisos").style.display = 'block';
                permisos = true;
            }
        }
        // Función para mostrar u ocultar en el perfil de usuario la edición de perfil
        function mostrarEditarPerfil() {
            if (mod_perfil === true) {
                document.getElementById('modificar_mi_perfil').innerHTML = " > Modificar Perfil";
                document.getElementById("mod_perfil").style.display = 'none';
                mod_perfil = false;
            } else {
                document.getElementById('modificar_mi_perfil').innerHTML = " V Modificar Perfil";
                document.getElementById("mod_perfil").style.display = 'block';
                mod_perfil = true;
            }
        }
        // Función en Index para mostrar las consolas con los formularios
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
        // Función en Tareas para mostrar los monitores con los formularios
        function mostrarMonitor(idp,idc) {
            // Cambia el background de todos los elementos con clase "pestanias"
            const pestaniasM = document.querySelectorAll('.pestanias');
            pestaniasM.forEach(function (pm) {
                pm.style.backgroundColor = '#d8d8d8';
            });
            document.getElementById(idp).style.backgroundColor = '#fefefe';

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
        // Función para mostrar en tiempo real los cambios en la tabla tareas de la base de datos
        function actualizarMonitor() {
            fetch('Scripts/monitor.php')
                .then(response => response.json())
                .then(datos => {
                    let tareas = `
                        <div id='tiempo_real'>
                            <div>
                                <i class='i_iconos i_pendiente'></i>
                                <div>
                                    <h3>Pendientes</h3>
                                    <div style='height:30px;
                                                width:${datos.pendientes*5}px;
                                                background:#02fcef;
                                                border-radius:0 6px 6px 0;'>
                                        <p><b>${datos.pendientes}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class='i_iconos i_ejecutando'></i>
                                <div>
                                    <h3>Ejecutando</h3>
                                    <div style='height:30px;
                                                width:${datos.ejecutando*5}px;
                                                background:#fc7202;
                                                border-radius:0 6px 6px 0;'>
                                        <p><b>${datos.ejecutando}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class='i_iconos i_completada'></i>
                                <div>
                                    <h3>Completadas</h3>
                                    <div style='height:30px;
                                                width:${datos.completadas*5}px;
                                                background:#0aa317;
                                                border-radius:0 6px 6px 0;'>
                                        <p><b>${datos.completadas}</b></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class='i_iconos i_fallida'></i>
                                <div>
                                    <h3>Fallidas</h3>
                                    <div style='height:30px;
                                                width:${datos.fallidas*5}px;
                                                background:#8a0f0f;
                                                border-radius:0 6px 6px 0;'>
                                        <p><b>${datos.fallidas}</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id='monitor_ejecucion'>
                            <h1>Tareas en Ejecución</h1>
                            <table class='cabecera-tabla'>
                                <tr>
                                    <th>ID</th>
                                    <th>ID API</th>
                                    <th>Nombre Contenedor</th>
                                    <th>ID Tarea</th>
                                    <th>Estado</th>
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
                                    <td>${t.estado}</td>
                                    <td>${t.progreso}</td>
                                    <td>${t.f_finalizacion}</td>
                                </tr>
                        `;
                    });
                    
                    tareas += '</table></div>';
                    document.getElementById('monitor_tareas').innerHTML = tareas;
                })
                .catch(error => {
                    document.getElementById('monitor_tareas').innerHTML = 'Error al cargar datos';
                    console.error(error);
                });
        }
        // Función que usa el php de certificados-de-api para mostrar los certificados de cada api
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
        // Función para que oculte o muestre el input file del CSV en el Select script
        function cambiarFormulario() {
            let script = document.getElementById("script").value;
            if (script === 'cambiar-permisos.ps1') {
                document.getElementById("etiqueta_csv").style.display = 'inline';
                document.getElementById("csv").style.display = 'inline';
                document.getElementById("lanzar_tareas_form").action = "Scripts/cambiar-permisos.php";
            } else {
                document.getElementById("etiqueta_csv").style.display = 'none';
                document.getElementById("csv").style.display = 'none';
                document.getElementById("lanzar_tareas_form").action = "Scripts/lanzar-tarea.php";
            }
        }

        // Añadimos el evento change con la función cargarCertificados para que cuando cambie el valor del SELECT apis los valores de los certificados se rellenen
        const apis = document.getElementById("apis");
        if (apis) {
            apis.addEventListener("change", cargarCertificados);
            apis.dispatchEvent(new Event("change"));
        }
        // Añadimos el evento change con la función cambiarFormulario para que cuando cambie el valor del SELECT script oculte o muestre el input file del CSV
        const script = document.getElementById("script");
        if (script) {
            script.addEventListener("change", cambiarFormulario);
            script.dispatchEvent(new Event("change"));
        }
    </script>
</body>
</html>