<?php 
require 'function.php';

$idc = $_GET["c"];

if (delPesanan($idc)) {
  echo '<script> document.location = "../../public/index.php" </script>';
}

?>