<?php

class clsLoguin {

    private $con;
    private $objCon;

    function clsLoguin() {
        require 'clsConexion.php';
        $this->objCon = new clsConexion();
        $this->con = $this->objCon->conectar();
    }

    public function loguear(loguinDAO $obj) {
        $sql = "SELECT usuario,password from usuario where usuario='" . $obj->getUsuario() . "' and password=" . $obj->getPassword() . "";        
        $resultado = $this->objCon->Ejecutar($sql);
        return $this->objCon->validarLoguin($resultado);        
    }

}

?>
