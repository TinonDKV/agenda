<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tarea_agenda
 *
 * @author Administrador
 */
class tarea_agenda extends fs_model
{
   public $id;
   public $fecha;
   public $tarea;
   public $usuario;
   public $completado;
   public $exists;
 
   
public function __construct($t = FALSE)
    {
        parent::__construct('agenda', '/plugins/mi_agenda/');
        
        if ($t)
        {
            $this->id =$t['id'];
            $this->fecha =Date('d-m-Y H:i',  strtotime($t['fecha']));
            $this->usuario = $t['usuario'];
            $this->tarea = $t['tarea'];
            $this->completado = $this->str2bool($t['completado']);
        }
        else
        {
            $this->id = NULL;
            $this->fecha= Date('d-m-Y H:i');
            $this->usuario = NULL;
            $this->tarea = NULL;
            $this->completado = NULL;
        }
    }
    
    protected function install() {
        return '';
    }
     public function get($id) {
         
         $data = $this->db->select("SELECT * FROM agenda WHERE id= ".$this->var2str($id).";");
         
         if ($data)

         {
             return new tarea_agenda($data[0]);
         }
         else
         {
             return FALSE;
         }    
             
     }
    
    public function exists()
   {
      if (!$this->exists)
      {
         if ($this->db->select("SELECT * FROM " . $this->table_name . " WHERE id = " . $this->var2str($this->id) . ";"))
         {
            $this->exists = TRUE;
         }
      }

      return $this->exists;
   }
     
     public function save() {
     
         if ($this->exists()){
             
             $this->fecha =Date ('Y-m-d H:i', strtotime($this->fecha));
             
         $sql ="UPDATE agenda SET fecha = ".$this->var2str($this->fecha).
               ", tarea = ".$this->var2str($this->tarea).
               ", usuario = ".$this->var2str($this->usuario).
               ", completado = ".$this->var2str($this->completado).
               " WHERE id = ".$this->var2str($this->id).";";
         
       return $this->db->exec($sql);
       
         }
         else
         {
             
    $sql ="INSERT INTO agenda (fecha,usuario,tarea) VALUES
                     (".$this->var2str($this->fecha).
                     ",".$this->var2str($this->usuario).
                     ",".$this->var2str($this->tarea).");";
    
    if ($this->db->exec($sql))
        
    {
        /// $this->id = $this->db->lastval();
         return TRUE;       
    }
 else
    {
        return FALSE;
    }    
    
         }
      }
     
     public function delete() {
         return $this->db->exec("DELETE FROM agenda WHERE id = "
                 .$this->var2str($this->id).";");
     }
     
     public function all() {
         
         $lista = array();
         
         $data= $this->db->select("SELECT * FROM agenda ORDER BY fecha DESC;");
         
         if ($data)
         { 
             foreach ($data as $d)
                 
                 $lista[] = new tarea_agenda ($d); 
         
         return $lista;
         }
     }
     
    
      public function separa_fecha()
    {
        $data = explode(' ', $this->fecha);
        return $data=Date('d-m-Y', strtotime($data[0]));       
    }
    
     public function separa_hora()
    {
        $data = explode(' ',$this->fecha);
        return $data[1];       
    }
   
}
