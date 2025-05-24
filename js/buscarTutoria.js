document.getElementById('buscarTutoria').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tutoriasBody tr');

    filas.forEach(fila => {
        const titulo = fila.cells[1]?.textContent.toLowerCase(); // Celda de "Título"
        if (titulo && titulo.includes(query)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});
