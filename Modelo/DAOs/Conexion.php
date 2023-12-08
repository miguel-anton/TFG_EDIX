<?php

//Clase que me permite acceder a BBDD
class Conexion{

    //Definimos propiedades para establecer los parámetros de conexión    
    private $host = "localhost:3306";
    private $dbname = "shanghairestaurante";
    private $user = "u_shanghai";
    private $pass = "6Kg~iu61";

    protected $db;    //Propiedad para guardar el objeto PDO

    public $filas = array();   //Propiedad pública para guardar el resultado de la consultas de seleccion
    public $lastId;   //Propiedad pública para guardar el id del ultimo elemento agregado

    private function conectar(){

        try{

            //Creamos la conexion
            $this->db =new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user,$this->pass); 

            // Establecemos parametro básicos de configuaracion   
            // $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);     
            // $this->db->exec("set names utf8mb4");
            
        //Controlamos si se ha producido una excepción
        }catch(PDOException $e){   
            
            echo  "  <p>Error: No puede conectarse con la base de datos.</p>\n\n";
            echo  "  <p>Error: " . $e->getMessage() . "</p>\n";
            
        }

    }

    private function cerrar(){

        $this->db = NULL;

    }
        
    public function consultaSimple($consulta, $parametros){

        $this->conectar();
            
        $sta = $this->db->prepare($consulta);
        
        if (!$sta->execute($parametros)){

            echo "<p>Error al ejecutar la consulta</p>";  

        }
        
        $this->cerrar(); 
    } 

    public function insertar_y_pasar_id($consulta, $parametros){

        $this->conectar();
            
        $sta = $this->db->prepare($consulta);
        
        if (!$sta->execute($parametros)){

            echo "<p>Error al ejecutar la consulta</p>";  

        }

        $id = $this->db->lastInsertId();
        
        $this->cerrar(); 

        return $id;
    } 

    public function consultaDatos($consulta, $parametros){

        $this->Conectar();
           
        $sta=$this->db->prepare($consulta);
        
        if (!$sta->execute($parametros)){

            echo "<p>Error al ejecutar la consulta</p>";

        //Gurdamos el resultado de la consulta en la variable filas
        }else{

            //Inicializamos el array para borrar posibles filas de consultas anteriores
            $this->filas = array();  
            
            //Sacamos las filas del objeto statement y las guardamos en un array
            foreach($sta as $fila){

                $this->filas[] = $fila;

            }
            
        }
           
        $this->Cerrar();
    } 
        
        
}

?>