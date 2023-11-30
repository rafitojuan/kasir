<?php
$conn = mysqli_connect("localhost", "root", "", "kasir");

function query($query)
{
  global $conn;

  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function addPesanan($data)
{
  global $conn;

  $idp = $data['pesanan'];
  $qty = htmlspecialchars($data['qty']);
  $harga = $data['harga'];
  $total = $harga * $qty;

  $query = "INSERT INTO cart VALUES (NULL,$idp,$qty,$total,'','pesanan')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function delPesanan($idc)
{
  global $conn;

  $query = "DELETE FROM cart WHERE id_cart = $idc";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function delProduk($idp)
{
  global $conn;

  $query = "DELETE FROM produk WHERE id_produk = $idp";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function addProduk($data)
{
  global $conn;

  $nama = ucwords(htmlspecialchars($data['nama']));
  $jenis = ucwords(htmlspecialchars($data['jenis']));
  $harga = htmlspecialchars($data['harga']);
  $foto = upload();

  $query = "INSERT INTO produk VALUES ('2023', '/INV/', NULL, '$nama', '$jenis', $harga, '$foto')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function upload()
{
  global $conn;

  $filename = $_FILES['foto']['name'];
  $size = $_FILES['foto']['size'];
  $error = $_FILES['foto']['error'];
  $temp = $_FILES['foto']['tmp_name'];

  $validExtension = ['png', 'jpg', 'jpeg'];
  $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

  if ($error == 4) {
    echo '<script> alert("Upload Foto!") </script>';
    return false;
  } elseif (!in_array($fileExtension, $validExtension)) {
    echo '<script> alert("File tidak didukung!") </script>';
    return false;
  } elseif ($size > 10000000) {
    echo '<script> alert("Foto terlalu besar!") </script>';
    return false;
  }

  $filename = pathinfo($filename, PATHINFO_FILENAME) . '_' . uniqid() . "." . $fileExtension;
  move_uploaded_file($temp, '../private/asset/temp/' . $filename);

  return $filename;
}

function bayarNow($data)
{
  global $conn;

  $payment = $data['payment'];

  $pesanan = mysqli_query($conn, "SELECT * FROM cart");
  $jmlh_pesanan = mysqli_num_rows($pesanan);
  $subtot = $data['subtot'];
  mysqli_query($conn, "INSERT INTO report VALUES(NULL,$jmlh_pesanan,$subtot,now())");
  mysqli_query($conn, "DELETE FROM cart WHERE status = 'dibayar'");

  $query = "UPDATE cart SET pembayaran = '$payment', status = 'dibayar'";
  mysqli_query($conn,$query);

  return mysqli_affected_rows($conn);
}
