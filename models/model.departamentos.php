<?php
    class Departamentos{
        public function list(){
            $conn = conexion();
            $sentencia = $conn->prepare("SELECT * FROM departamentos");
            $sentencia->execute();
            $resultado = $sentencia->fetchAll();
            #json_encode convierte el resultado en un objeto JSON
            return json_encode($resultado);
        }
    }


?>