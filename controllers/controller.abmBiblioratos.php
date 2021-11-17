<?php
    require_once "models/model.biblioratos.php";
    require_once "models/model.departamentos.php";

    class ABMBiblioratosController{
        public function init(){
            $model = new ABMBiblioratos();

            #Segun la orden es lo que va a mostrar
            if(isset($_GET['order'])){
                $order = $_GET['order'];
                #llena o vacia segun el caso
                if ($order =='llenar'){
                    $numeroBib = $_GET['numeroBib'];
                    #obtengo el bibliorato para obtener su estado y poder cambiarlo en "llenarBibliorato"
                    $bibliorato = $model->obtenerBibliorato($numeroBib);
                    $model->llenarBibliorato($numeroBib,$bibliorato['lleno_fisicamente']);
                    
                    #Permite mostrar solo el bibliorato recientemente llenado
                    $_SESSION['filtroBib'] = $numeroBib;

                    $this->listar($model);
                }
                elseif ($order =='filtrar'){
                    if(isset($_POST['id_departamento'])){
                        $_SESSION['filtroBib'] = $_POST['id_departamento'];                       
                    }
                    else{
                        $_SESSION['filtroBib'] = isset($_POST['filtroBib']) ?
                        trim($_POST['filtroBib']) : '';
                    }
                    $this->listar($model);
                }

            }
            #Si order no esta pedida, mostrar solo la lista
            else{
                $this->listar($model); 
            }

        }
        private function listar($model){
            $filas_biblioratos = $model->listarBibFiltrado();
            #lista_biblioratos es usado por el view.abmBiblioratos.lista.php
            $lista_biblioratos = $filas_biblioratos[0];
            $numero_paginas = $filas_biblioratos[1]; 

            #Departamentos para el select
            $modelDeptos = new Departamentos();
            $departamentos = $modelDeptos->list();

            include "views/modules/view.abmBiblioratos.lista.php";
        }
    }

?>