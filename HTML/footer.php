    <footer>
        <div>
            <span>© 2026 Daniel Olivares Pozo</span>
        </div>
    </footer>
    <script>
        function mostrarConsola(id) {
            // Oculta todos los elementos con clase "consolas"
            const consolas = document.querySelectorAll('.consolas');
            consolas.forEach(function(c) {
                c.style.display = 'none';
            });
            // Muestra el elemento seleccionado
            document.getElementById(id).style.display = 'block';
        }
    </script>
</body>
</html>