document.addEventListener('DOMContentLoaded', function() {
    // Obtener el formulario
    const form = document.getElementById('crearTutoriaForm');

    // Validación al enviar el formulario
    form.addEventListener('submit', function(event) {
        // Obtener los valores de los campos
        const titulo = document.getElementById('titulo').value;
        const descripcion = document.getElementById('descripcion').value;
        const fechaHora = document.getElementById('fecha_hora').value;
        const estado = document.getElementById('estado').value;

        // Comprobar si los campos están vacíos
        if (titulo === '' || descripcion === '' || fechaHora === '' || estado === '') {
            // Evitar que el formulario se envíe
            event.preventDefault();

            // Mostrar un mensaje de error
            alert('Por favor, completa todos los campos antes de enviar el formulario.');
        }
    });
});
