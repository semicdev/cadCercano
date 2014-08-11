<?php
class ConexionMongodb 
{			
		protected $conexion;
		const DB='inventario';
		const SERVER='mongodb://localhost:27017';
		protected $db;

		function __construct() 
		{
			$this->conexion  = new MongoClient(self::SERVER);
			$this->db = $this->conexion->selectDB(self::DB);
		}

		function close(){
			return $this->db->close();	
		}
}