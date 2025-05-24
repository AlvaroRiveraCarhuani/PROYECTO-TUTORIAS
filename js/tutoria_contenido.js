document.addEventListener('DOMContentLoaded', () => {
    const tabContent = document.getElementById('tabContent');
    const tabMenu = document.getElementById('tabMenu');
    const addTabButton = document.getElementById('addTab');
    const newSectionForm = document.getElementById('newSectionForm');
    const formNewSection = document.getElementById('formNewSection');
    const cancelNewSection = document.getElementById('cancelNewSection');

    // Mostrar formulario para agregar nueva sección
    addTabButton.addEventListener('click', () => {
        newSectionForm.style.display = 'block';
        addTabButton.style.display = 'none';
    });

    // Cancelar creación de nueva sección
    cancelNewSection.addEventListener('click', () => {
        newSectionForm.style.display = 'none';
        addTabButton.style.display = 'block';
    });

    // Agregar nueva sección al enviar formulario
    formNewSection.addEventListener('submit', (e) => {
        e.preventDefault();
        const idTutoria = new URLSearchParams(window.location.search).get('id');
        const tipo = document.getElementById('newTipo').value;
        const contenido = document.getElementById('newContenido').value;

        fetch('guardar_contenido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id_tutoria=${idTutoria}&tipo=${encodeURIComponent(tipo)}&contenido=${encodeURIComponent(contenido)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                agregarNuevaPestana(data.id, tipo, contenido);
                alert('Nueva sección creada con éxito.');
            } else {
                alert('Error al agregar la nueva sección: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Manejar acciones en las pestañas
    tabContent.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-modificar')) {
            const section = e.target.closest('.tab-pane');
            activarEdicion(section);
        }

        if (e.target.classList.contains('btn-guardar-cambios')) {
            const section = e.target.closest('.tab-pane');
            guardarCambios(section, e.target);
        }

        if (e.target.classList.contains('btn-eliminar')) {
            const idContenido = e.target.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar esta sección?')) {
                eliminarSeccion(idContenido, e.target);
            }
        }
    });

    function eliminarSeccion(idContenido, button) {
        fetch('eliminar_contenido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${idContenido}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const section = button.closest('.tab-pane');
                const tabId = section.id.replace('content', 'tab');
                document.getElementById(tabId).remove(); // Eliminar pestaña asociada
                section.remove(); // Eliminar contenido asociado
                actualizarIndices(); // Actualizar indices de tabs restantes
                alert('Sección eliminada con éxito.');
            } else {
                alert('Error al eliminar la sección: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    function actualizarIndices() {
        const tabs = tabMenu.querySelectorAll('.nav-item .nav-link');
        const sections = tabContent.querySelectorAll('.tab-pane');
    
        tabs.forEach((tab, index) => {
            tab.id = `tab${index}`;
            tab.href = `#content${index}`;
            tab.textContent = `Sección ${index + 1}`;
        });
    
        sections.forEach((section, index) => {
            section.id = `content${index}`;
            if (index === 0) {
                section.classList.add('show', 'active');
            } else {
                section.classList.remove('show', 'active');
            }
        });
    
        if (tabs.length > 0) {
            tabs[0].classList.add('active');
        }
    }
    
    function agregarNuevaPestana(id, tipo, contenido) {
        const index = tabMenu.querySelectorAll('.nav-item').length;

        const newTab = document.createElement('li');
        newTab.className = 'nav-item';
        newTab.innerHTML = `
            <a class="nav-link" id="tab${index}" data-bs-toggle="tab" href="#content${index}" role="tab">
                Sección ${index + 1}
            </a>`;
        tabMenu.appendChild(newTab);

        const newContent = document.createElement('div');
        newContent.className = 'tab-pane fade';
        newContent.id = `content${index}`;
        newContent.innerHTML = `
            <p><strong>Tipo:</strong> <span class="tipo">${tipo}</span></p>
            <p><strong>Contenido:</strong> <span class="contenido">${contenido}</span></p>
            <button class="btn btn-warning btn-modificar" data-id="${id}">Modificar</button>
            <button class="btn btn-primary btn-guardar-cambios" data-id="${id}" style="display:none;">Guardar Cambios</button>
            <button class="btn btn-danger btn-eliminar" data-id="${id}">Eliminar</button>`;
        tabContent.appendChild(newContent);
    }

    function activarEdicion(section) {
        const tipoSpan = section.querySelector('.tipo');
        const contenidoSpan = section.querySelector('.contenido');
        const saveButton = section.querySelector('.btn-guardar-cambios');

        tipoSpan.innerHTML = `
            <select class="form-select">
                <option value="diapositiva" ${tipoSpan.textContent === 'diapositiva' ? 'selected' : ''}>Diapositiva</option>
                <option value="imagen" ${tipoSpan.textContent === 'imagen' ? 'selected' : ''}>Imagen</option>
                <option value="video" ${tipoSpan.textContent === 'video' ? 'selected' : ''}>Video</option>
                <option value="link" ${tipoSpan.textContent === 'link' ? 'selected' : ''}>Link</option>
            </select>`;
        contenidoSpan.innerHTML = `<textarea class="form-control">${contenidoSpan.textContent}</textarea>`;
        saveButton.style.display = 'inline-block';
        section.querySelector('.btn-modificar').style.display = 'none';
    }

    function guardarCambios(section, saveButton) {
        const tipo = section.querySelector('.tipo select').value;
        const contenido = section.querySelector('.contenido textarea').value;
        const idContenido = saveButton.getAttribute('data-id');

        fetch('actualizar_contenido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${idContenido}&tipo=${encodeURIComponent(tipo)}&contenido=${encodeURIComponent(contenido)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                section.querySelector('.tipo').textContent = tipo;
                section.querySelector('.contenido').textContent = contenido;
                saveButton.style.display = 'none';
                section.querySelector('.btn-modificar').style.display = 'inline-block';
                alert('Contenido actualizado exitosamente.');
            } else {
                alert('Error al actualizar el contenido: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
