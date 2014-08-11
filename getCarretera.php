<?php 
require("Carretera.php");

$carretera = new Estaciones();

echo json_encode($carretera->getCarretera());