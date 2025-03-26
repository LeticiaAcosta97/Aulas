function verEquipos(aula_id) {
    fetch('obtener_equipos.php?aula_id=' + aula_id)
        .then(response => response.text())
        .then(data => {
            document.getElementById('equiposLista').innerHTML = data;
            var modal = new bootstrap.Modal(document.getElementById('modalEquipos'));
            modal.show();
        });
}

function editarEquipo(equipo_id) {
    document.getElementById("desc_" + equipo_id).style.display = "none";
    document.getElementById("edit_form_" + equipo_id).style.display = "block";
}

function cancelarEdicion(equipo_id) {
    document.getElementById("desc_" + equipo_id).style.display = "block";
    document.getElementById("edit_form_" + equipo_id).style.display = "none";
}

function guardarEquipo(equipo_id) {
    let descripcion = document.getElementById("new_desc_" + equipo_id).value;
    let cantidad = document.getElementById("new_cant_" + equipo_id).value;
    let marca = document.getElementById("new_marca_" + equipo_id).value;

    fetch("modificar_equipo.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `equipo_id=${equipo_id}&descripcion=${encodeURIComponent(descripcion)}&cantidad=${cantidad}&marca=${encodeURIComponent(marca)}`
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            document.getElementById("desc_" + equipo_id).innerText = descripcion;
            document.getElementById("cant_" + equipo_id).innerText = cantidad;
            document.getElementById("marca_" + equipo_id).innerText = marca;
            document.getElementById("desc_" + equipo_id).style.display = "block";
            document.getElementById("edit_form_" + equipo_id).style.display = "none";
        } else {
            alert("Error al actualizar el equipo.");
        }
    })
    .catch(error => console.error("Error:", error));
}
