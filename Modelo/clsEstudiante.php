<?php

class clsEstudiante {

    private $con;
    private $objCon;

    function clsEstudiante() {
        require 'clsConexion.php';
        $this->objCon = new clsConexion();
        $this->con = $this->objCon->conectar();
    }

    public function guardar(estudianteDAO $obj) {
        $sql = "INSERT INTO estudiante(codigo,nombre,apellido,cedula,edad,semestre) VALUES (" . $obj->getCodigo() . ",'" . $obj->getNombre() . "','" . $obj->getApellido() . "'," . $obj->getCedula() . "," . $obj->getEdad() . "," . $obj->getSemestre() . ")";
        $resultado = $this->objCon->Ejecutar($sql);
        $this->objCon->respuesta($resultado);
    }

    public function buscar(estudianteDAO $obj) {
        $sql = "SELECT id,codigo,nombre,apellido,cedula,edad,semestre from estudiante where codigo=" . $obj->getCodigo() . "";
        $resultado = $this->objCon->Ejecutar($sql);
        $this->construirBusqueda($resultado);
    }

    public function eliminar(estudianteDAO $obj) {
        $sql = "delete from estudiante where id=" . $obj->getId() . "";
        $resultado = $this->objCon->Ejecutar($sql);
        $this->objCon->respuesta($resultado);
    }

    public function modificar(estudianteDAO $obj) {
        $sql = "UPDATE estudiante set codigo=" . $obj->getCodigo() . ",nombre='" . $obj->getNombre() . "',apellido='" . $obj->getApellido() . "',cedula=" . $obj->getCedula() . ",edad=" . $obj->getEdad() . ",semestre=" . $obj->getSemestre() . " where id=" . $obj->getId() . "";
        $resultado = $this->objCon->Ejecutar($sql);
        $this->objCon->respuesta($resultado);
    }

    public function listar() {
        $sql = "SELECT codigo,nombre,apellido,cedula,edad,semestre from estudiante";
        $resultado = $this->objCon->Ejecutar($sql);
        $this->construirListado($resultado);
    }

    function construirBusqueda($resultado) {

        $vec = pg_fetch_row($resultado);

        if (isset($vec) && $vec[0] != "") {
            $lista = $lista . " document.getElementById('txtId').value='" . $vec[0] . "';";
            $lista = $lista . " document.getElementById('txtCodigo').value='" . $vec[1] . "';";
            $lista = $lista . " document.getElementById('txtNombre').value='" . $vec[2] . "';";
            $lista = $lista . " document.getElementById('txtApellido').value='" . $vec[3] . "';";
            $lista = $lista . " document.getElementById('txtCedula').value='" . $vec[4] . "';";
            $lista = $lista . " document.getElementById('txtEdad').value='" . $vec[5] . "';";
            $lista = $lista . " document.getElementById('txtSemestre').value='" . $vec[6] . "';";
            header('location:../Index.php?page=estudiantes&&datos=' . $lista);
        } else {
            $mensaje = "no se encontro informacion";
            header('location:../Index.php?page=estudiantes&&dato=' . $mensaje);
        }
    }

    function construirListado($resultado) {
        if ($resultado && pg_numrows($resultado) > 0) {

            $cadenaHTML = "<table border='1'>";

            $cadenaHTML .= "<tr>";
            $cadenaHTML .= "<th>Codigo</th>";
            $cadenaHTML .= "<th>Nombre</th>";
            $cadenaHTML .= "<th>Apellido</th>";
            $cadenaHTML .= "<th>Cedula</th>";
            $cadenaHTML .= "<th>Edad</th>";
            $cadenaHTML .= "<th>Semestre</th>";
            $cadenaHTML .= "</tr>";

            for ($cont = 0; $cont < pg_numrows($resultado); $cont++) {

                $cadenaHTML.="<tr>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 0) . "</td>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 1) . "</td>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 2) . "</td>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 3) . "</td>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 4) . "</td>";
                $cadenaHTML.="<td>" . pg_result($resultado, $cont, 5) . "</td>";
                $cadenaHTML.="</tr>";
            }

            $cadenaHTML.="</table>";
        } else {
            $cadenaHTML = "<b>No hay registros en la base de datos</b>";
        }

        header('location:../Index.php?page=estudiantes&&tabla=' . $cadenaHTML);
    }

    public function generarPDF() {
        $sql = "SELECT codigo,nombre,apellido,cedula,edad,semestre from estudiante";
        $resultado = $this->objCon->Ejecutar($sql);

        if ($resultado && pg_numrows($resultado) > 0) {

            require_once('../Recursos/html2pdf/html2pdf.class.php'); // Se carga la libreria

            ob_start(); //Habilita el buffer para la salida de datos 
            ob_get_clean(); //Limpia lo que actualmente tenga el buffer
            //En la variable content entre las etiquetas <page></page> va todo el contenido del pdf en formato html
            $content = "<page>";

            $content.="<h1>ESTE ES EL REPORTE</h1>";
            $content.='<link href="../Recursos/css/estilosPDF.css" type="text/css" rel="stylesheet">';
            $content.= "<table border='1'>";

            $content .= "<tr>";
            $content .= "<th>Codigo</th>";
            $content .= "<th>Nombre</th>";
            $content .= "<th>Apellido</th>";
            $content .= "<th>Cedula</th>";
            $content .= "<th>Edad</th>";
            $content .= "<th>Semestre</th>";
            $content .= "</tr>";

            for ($cont = 0; $cont < pg_numrows($resultado); $cont++) {
                $content.="<tr>";
                $content.="<td>" . pg_result($resultado, $cont, 0) . "</td>";
                $content.="<td>" . pg_result($resultado, $cont, 1) . "</td>";
                $content.="<td>" . pg_result($resultado, $cont, 2) . "</td>";
                $content.="<td>" . pg_result($resultado, $cont, 3) . "</td>";
                $content.="<td>" . pg_result($resultado, $cont, 4) . "</td>";
                $content.="<td>" . pg_result($resultado, $cont, 5) . "</td>";
                $content.="</tr>";
            }

            $content.="</table>";


            $content.= "</page>";
        } else {
            $content = "<b>No hay registros en la base de datos</b>";
        }

        $html2pdf = new HTML2PDF('P', 'A4', 'es'); //formato del pdf (posicion (P=vertical L=horizontal), tamaño del pdf, lenguaje)
        $html2pdf->WriteHTML($content); //Lo que tenga content lo pasa a pdf
        ob_end_clean(); // se limpia nuevamente el buffer
        $html2pdf->Output('exemple.pdf'); //se genera el pdf, generando por defecto el nombre indicado para guardar
    }

}

?>
