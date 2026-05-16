    <footer>
        <div>
            <span>© 2026 Daniel Olivares Pozo</span>
        </div>
    </footer>
    <script>
        function mostrarConsola(idp,idc) {
            // Cambia el background de todos los elementos con clase "pestanias"
            const pestanias = document.querySelectorAll('.pestanias');
            pestanias.forEach(function (p) {
                p.style.backgroundColor = '#1e1e1e';
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
    </script>
</body>
</html>