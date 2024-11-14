document.addEventListener('DOMContentLoaded', function () {
    // Seleccionamos todos los formularios de solicitud de tutoría
    const solicitarForms = document.querySelectorAll('.solicitar-form');

    solicitarForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Evitar que el formulario recargue la página

            const tutoriaId = form.getAttribute('data-tutoria-id');
            const statusElement = document.querySelector(`.solicitud-status-${tutoriaId}`);

            // Mostrar un mensaje mientras se envía la solicitud
            statusElement.textContent = "Enviando solicitud...";

            const formData = new FormData();
            formData.append('tutoria_id', tutoriaId);

            fetch('solicitar_tutoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar un mensaje de éxito
                    statusElement.textContent = "Solicitud enviada con éxito";
                    form.style.display = 'none';  // Ocultar el botón de solicitud
                } else {
                    // Mostrar mensaje de error
                    statusElement.textContent = "Error al enviar la solicitud. Intente nuevamente.";
                }
            })
            .catch(error => {
                statusElement.textContent = "Hubo un error al enviar la solicitud.";
            });
        });
    });
});
