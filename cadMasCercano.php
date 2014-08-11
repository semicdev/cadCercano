<?php
require("Carretera.php");

$carretera = new Estaciones();
echo json_encode(iterator_to_array($carretera->cadMasCercano($_GET['lng'], $_GET['lat'])));