<?php
    class LoginModel{
        public function login($usuario,$clave){
            #Genero el hash sha1 con la clave
            $clave_codificada = sha1($clave);
            
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('SELECT `usuario`,`password`,`foto`, `rol`,`nombre_apellido`
                                        FROM `usuarios`
                                        INNER JOIN `roles`
                                        USING (`id_rol`)
                                        WHERE `usuario` = :usuario AND `password` = :clave');
            $sentenciaSQL->execute(array(':usuario'=>$usuario,
                                        ':clave'=>$clave_codificada));
            $usuario = $sentenciaSQL->fetch();
            #Si no esta el usuario retorna Falso al hacer el fetch()
            return $usuario;
        }
        public function logout(){
            #session_unset — Libera todas las variables de sesión
            session_unset();
        }
    }

?>