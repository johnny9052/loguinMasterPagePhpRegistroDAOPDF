function validarEstudiante(f1, tipo) {

    f1.type.value = tipo;

    if (tipo == "save") {
        if (f1.codigo.value != "" && f1.nombre.value != "" && f1.apellido.value != "" && f1.cedula.value != "" && f1.edad.value != "" && f1.semestre.value != "") {
            f1.submit();
        } else {
            alert("Ingrese todos los datos");
        }
    }

    if (tipo == "update" || tipo == "delete") {
        if (f1.id.value != "") {
            f1.submit();
        } else {
            alert("Ingrese el dato de busqueda");
        }
    }

    if (tipo == "search") {
        if (f1.codigo.value != "") {
            f1.submit();
        } else {
            alert("Ingrese el codigo de busqueda");
        }
    }

    if (tipo == "list") {
        f1.submit();
    }
}


