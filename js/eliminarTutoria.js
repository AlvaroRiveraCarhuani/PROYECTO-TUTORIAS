document.addEventListener('DOMContentLoaded', function () {
    let tutoriaIdToDelete = null;

    window.confirmarEliminacion = function (idTutoria) {
        tutoriaIdToDelete = idTutoria;
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    };

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn.addEventListener('click', function () {
        if (tutoriaIdToDelete) {
            window.location.href = 'eliminar_tutoria.php?id=' + tutoriaIdToDelete;
        }
    });
});
