<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_model('tarea_agenda.php');
/**
 * Description of agenda_inicio
 *
 * @author Administrador
 */
class agenda_inicio extends fs_controller
{
    public $listado;
    public $editar;
    public $tarea_agenda;
      
    
    public function enviar_telegram($mensaje){
        if (isset($mensaje)){
            $mensaje = $mensaje;
        }else{
           
            $mensaje = "Tienes una tarea en la agenda";
        }
       $envio = Array (
            'chat_id'=> CHAT_ID,
            'text'   => $mensaje,
        );
        $url ='https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?'
                .http_build_query($envio);
        $result = file_get_contents($url);
        
    }


    public function __construct() {
        parent::__construct(__CLASS__, 'Agenda', 'Utilidades');
    }
    
    protected function private_core() {
       
        $this->tarea_agenda = new tarea_agenda();
        $completado = FALSE;
        $this->editar= FALSE;
        $this->listado= $this->tarea_agenda->all();
        $this->bandera = "1968";
        
        if (isset($_POST['modificar'])) /// guardar Editar agenda
        {
         $this->tarea_agenda->id = $_POST['modificar'];
         $this->editar = $this->tarea_agenda->get($_POST['modificar']); 
         if ($this->editar)
         {
          $this->tarea_agenda->fecha= $_POST['fecha'].' '.$_POST['hora'];
          $this->tarea_agenda->completado = isset($_POST['completado']);
          $this->tarea_agenda->tarea= $_POST['tarea'];
          $this->tarea_agenda->usuario= $_POST['usuario']; 
          
            if ($this->tarea_agenda->save())
                {
                    $this->new_message('Datos modificados correctamante');
                    $this->enviar_telegram('Para '.$_POST['usuario'].':'.$_POST['tarea'].' -esto es una modicacion.');
                }
                else
                {
                    $this->new_error_msg('Error al Guardar');
                }  
         }
        }
        else if (isset($_POST['fecha']))///nueva tarea
            {
            $this->tarea_agenda->fecha = $_POST['fecha'].' '.$_POST['hora'];
            $this->tarea_agenda->tarea = $_POST['tarea'];
            $this->tarea_agenda->usuario = $_POST['usuario'];
            
            if($this->tarea_agenda->save())
            
                {
                    $this->new_message('Datos guardados correctamante');
                    $this->enviar_telegram('Para '.$_POST['usuario'].':'.$_POST['tarea'].' -esto es una tarea nueva.');
                }
                else
                {
                    $this->new_error_msg('Error al Guardar');
                }
            }
        else if (isset ($_GET['id'])) ///editar desde listado
            {
             $this->editar = $this->tarea_agenda->get($_GET['id']);
            }
            else if (isset ($_GET['delete'])) ///eliminar tarea
            {
             $aux = $this->tarea_agenda->get($_GET['delete']);
             if ($aux)
             {
             if ($aux->delete())
                {
                $this->new_message('Datos eliminados correctamante');
                }
                else
                {
                $this->new_error_msg('Error al eliminar');
                }
             }
            else
            {
            $this->new_error_msg('Tarea no encontrada');
            }    
              
              
            }  
    }
    
   
}
