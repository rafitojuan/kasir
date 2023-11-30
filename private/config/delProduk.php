<?php
require 'function.php';

$idp = $_GET["p"];

if (delProduk($idp)) {
  echo '<script> document.location = "../../public/index.php" </script>';
}
