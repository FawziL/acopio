/**
 * Carga municipios al cambiar el estado (filtro y formularios).
 */
document.addEventListener('DOMContentLoaded', function () {
    // --- Botón difundir (copiar link) ---
    const btnDifundir = document.getElementById('btn-difundir');
    if (btnDifundir) {
        btnDifundir.addEventListener('click', function() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                const originalHTML = btnDifundir.innerHTML;
                btnDifundir.innerHTML = '<i class="bi bi-check"></i> Enlace copiado';
                btnDifundir.classList.remove('btn-av-outline-yellow');
                btnDifundir.classList.add('btn-av-green');
                setTimeout(function() {
                    btnDifundir.innerHTML = originalHTML;
                    btnDifundir.classList.remove('btn-av-green');
                    btnDifundir.classList.add('btn-av-outline-yellow');
                }, 2000);
            }).catch(function() {
                alert('No se pudo copiar el enlace. Copia manualmente: ' + url);
            });
        });
    }

    // --- Tabs de catálogo ---
    const tabs = document.querySelectorAll('.av-tab');
    const panels = document.querySelectorAll('.av-tab-panel');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            tabs.forEach(function(t) { t.classList.remove('active'); });
            panels.forEach(function(p) { p.classList.remove('active'); });

            this.classList.add('active');
            const targetPanel = document.getElementById('panel-' + targetTab);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    const estadoSelect = document.getElementById('filtro-estado');
    const municipioSelect = document.getElementById('filtro-municipio');

    if (estadoSelect && municipioSelect) {
        estadoSelect.addEventListener('change', function () {
            const estadoId = this.value;

            municipioSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!estadoId) {
                municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                return;
            }

            fetch('/api/municipios.php?estado_id=' + estadoId)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    municipioSelect.innerHTML = '<option value="">Todos los municipios</option>';
                    if (data.data) {
                        data.data.forEach(function (m) {
                            var opt = document.createElement('option');
                            opt.value = m.id;
                            opt.textContent = m.nombre;
                            municipioSelect.appendChild(opt);
                        });
                    }
                })
                .catch(function () {
                    municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        });

        // Si ya hay un estado seleccionado, cargar municipios
        if (estadoSelect.value) {
            var event = new Event('change');
            estadoSelect.dispatchEvent(event);
        }
    }

    // --- Cascada estado -> municipio -> parroquia ---
    const paso2Estado = document.getElementById('paso2-estado');
    const paso2Municipio = document.getElementById('paso2-municipio');
    const paso2Parroquia = document.getElementById('paso2-parroquia');

    function cargarMunicipiosPaso2() {
        const estadoId = paso2Estado.value;
        paso2Municipio.innerHTML = '<option value="">Cargando...</option>';
        paso2Parroquia.innerHTML = '<option value="">Selecciona un municipio primero</option>';

        if (!estadoId) {
            paso2Municipio.innerHTML = '<option value="">Selecciona un estado primero</option>';
            return;
        }

        fetch('/api/municipios.php?estado_id=' + estadoId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                paso2Municipio.innerHTML = '<option value="">Selecciona un municipio</option>';
                if (data.data) {
                    data.data.forEach(function (m) {
                        var opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.nombre;
                        paso2Municipio.appendChild(opt);
                    });
                }
            })
            .catch(function () {
                paso2Municipio.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    function cargarParroquiasPaso2() {
        const municipioId = paso2Municipio.value;
        paso2Parroquia.innerHTML = '<option value="">Cargando...</option>';

        if (!municipioId) {
            paso2Parroquia.innerHTML = '<option value="">Selecciona un municipio primero</option>';
            return;
        }

        fetch('/api/parroquias.php?municipio_id=' + municipioId)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                paso2Parroquia.innerHTML = '<option value="">Sin parroquia</option>';
                if (data.data) {
                    data.data.forEach(function (p) {
                        var opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.nombre;
                        paso2Parroquia.appendChild(opt);
                    });
                }
            })
            .catch(function () {
                paso2Parroquia.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    if (paso2Estado) {
        paso2Estado.addEventListener('change', cargarMunicipiosPaso2);
    }
    if (paso2Municipio) {
        paso2Municipio.addEventListener('change', cargarParroquiasPaso2);
    }

    // --- Previsualización de foto ---
    const fotoInput = document.getElementById('paso2-foto');
    const fotoPreview = document.getElementById('paso2-foto-preview');
    if (fotoInput && fotoPreview) {
        fotoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    fotoPreview.src = e.target.result;
                    fotoPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                fotoPreview.classList.add('d-none');
                fotoPreview.src = '';
            }
        });
    }

    // --- Inventario: agregar item (centros y refugios) ---
    const formItem = document.getElementById('form-agregar-item');
    if (formItem) {
        formItem.addEventListener('submit', function (e) {
            e.preventDefault();

            const tokenInput = document.querySelector('#form-agregar-item [name="turnstile_token"]');
            const token = tokenInput ? tokenInput.value : '';
            if (!token) {
                alert('Completa la verificacion de seguridad.');
                return;
            }

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>...';

            const formData = new FormData(this);
            const data = {};
            formData.forEach(function (value, key) {
                data[key] = value;
            });
            data.turnstile_token = token;

            const apiUrl = this.dataset.api || '/api/inventario.php';

            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.error) {
                    alert(res.error);
                } else {
                    location.reload();
                }
            })
            .catch(function () {
                alert('Error al guardar. Intenta de nuevo.');
            })
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-plus-circle"></i> Agregar';
            });
        });
    }
});
