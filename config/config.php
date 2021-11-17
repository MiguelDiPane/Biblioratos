<?php
    #Inicio la sesión
    session_start();
    $tiempo_sesion = 120;
    #Creo usuario base, admin
    define('ROOT_PATH','http://localhost:8080/ProgramacionWebI/Biblioratos');

    $AdminUser = array(
        'usuario' => 'admin',
        'clave' => 'admin',
    );

    $bd_config = array(
        'base'=>'biblioratos',
        'usuario'=>'root',
        'pass'=>'');
    
    $carpetas_config = array(
        'registros_por_pagina' => '10');
    function conexion(){
        global $bd_config;
        $conn = new PDO('mysql:host=localhost;dbname=biblioratos;charset=utf8','root','');
        #Establecer el modo de error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    # Función para obtener la página actual
    # Return: El número de la página si está seteado, sino retorna 1.
    function pagina_actual(){
        return isset($_GET['p']) ? (int)$_GET['p']: 1; 
    }
?>