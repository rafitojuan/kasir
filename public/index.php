<?php
require '../private/config/function.php';


$produk = mysqli_query($conn, "SELECT * FROM produk");
$cart = mysqli_query($conn, "SELECT * FROM cart JOIN produk USING (id_produk) WHERE status = 'pesanan'");
$i = 1;
$pesanan = mysqli_query($conn, "SELECT * FROM cart ORDER BY id_cart DESC LIMIT 1");
$arr = mysqli_fetch_array($pesanan);
$empty = end($arr);
$hartot = query("SELECT SUM(total) AS Total FROM cart WHERE status = 'pesanan'")[0];
$subtotal = query("SELECT SUM(total) AS Subtot FROM cart ")[0];

if (isset($_POST['pesan'])) {
  if (addPesanan($_POST)) {
    echo '<script> document.location = "index.php" </script>';
  }
}

if (isset($_POST['send'])) {
  if (addProduk($_POST)) {
    echo '<script> document.location = "index.php" </script>';
  }
}

if (isset($_POST['bayar'])) {
  if (bayarNow($_POST)) {
    echo '<script> alert("Pembayaran Berhasil, Terimakasih!!"); document.location = "../public/invoice.php"; </script>';
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Dashboard</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../private/asset/css/style.css">

</head>

<body oncontextmenu="return false">
  <div class="container-fluid">
    <div class="row">
      <div class="kiri col-md-8">
        <h3 class="mt-3"><span class="text-danger fs-2 fw-bold">|</span>Menu</h3>
        <button class="btn badge bg-success mb-3" data-bs-toggle="modal" data-bs-target="#modalProduk">+ Tambah Produk</button>
        <div class="row">
          <?php foreach ($produk as $items) : ?>
            <div class="col-md-4 mb-3">
              <div class="card">
                <img class="card-img-top" src="../private/asset/temp/<?= $items['gambar'] ?>" alt="a">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                      <thead>
                        <tr>
                          <td scope="col">Nama</td>
                          <td scope="col">:</td>
                          <td scope="col"><?= $items['nama_produk'] ?></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="">
                          <td scope="row">Jenis</td>
                          <td>:</td>
                          <td><?= $items['tipe'] ?></td>
                        </tr>
                        <tr class="">
                          <td scope="row">Harga</td>
                          <td>:</td>
                          <td>Rp.<?= number_format($items['harga']) ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
                <div class="card-footer text-center ">
                  <button class="btn" data-bs-toggle="modal" data-bs-target="#modalPesanan<?= $items['id_produk'] ?>"><i class='bx bx-plus-circle bx-spin-hover' style='color:#1ba010'></i></i></button>
                  <a href="../private/config/delProduk.php?p=<?= $items['id_produk'] ?>" class="btn" onclick="return confirm('Hapus?')"><i class='bx bx-trash bx-tada-hover' style='color:#d11b1b'></i></a>
                </div>
              </div>
            </div>

            <!-- Modal Pesanan -->
            <div class="modal fade" id="modalPesanan<?= $items['id_produk'] ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Pesan Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post">
                      <div class="mb-3">
                        <label for="namapesanan" class="form-label">Nama :</label>
                        <input type="text" class="form-control" disabled value="<?= $items['nama_produk'] ?>">
                        <input type="hidden" name="pesanan" class="form-control" value="<?= $items['id_produk'] ?>">
                      </div>
                      <div class="mb-3">
                        <label for="qty" class="form-label">Quantitas :</label>
                        <input type="number" class="form-control" name="qty" min="1" oninvalid="this.setCustomValidity('Tidak bisa menghutang')" oninput="this.setCustomValidity('')">
                      </div>
                      <div class="mb-3">
                        <input type="hidden" name="harga" class="form-control" value="<?= $items['harga'] ?> ">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="pesan" class="btn btn-success">Save!</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-md-4 shadow-lg min-vh-100">
        <div class="row">
          <div class="col">
            <h3 class="mt-3"><span class="text-danger fs-2 fw-bold">|</span>Pesanan</h3>
            <div class="table-responsive mt-3">
              <table class="table table-hover table-striped table-secondary">
                <thead>
                  <tr>
                    <th scope="col" style="width: 10px;">No</th>
                    <th scope="col">Produk</th>
                    <th scope="col">qty</th>
                    <th scope="col">harga</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($cart as $data) : ?>
                    <tr class="">
                      <td scope="row"><?= $i++ ?>.</td>
                      <td><?= $data['nama_produk'] ?></td>
                      <td><?= $data['qty'] ?>x</td>
                      <td>Rp.<?= number_format($data['total']) ?></td>
                      <td><a href="../private/config/delPesanan.php?c=<?= $data['id_cart'] ?>" class="btn p-0 text-danger"><i class="bx bxs-trash bx-tada-hover"></i></a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <div class="border-top border-3 border-danger mb-2"></div>
              <h3 class="">Total <span class="ms-4">:</span> <span class="float-end">Rp.<?= $hartot['Total'] == NULL ? '-' : number_format($hartot['Total']) ?></span></h3>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col border-top border-3 border-dark" style="margin-top: 13vh;">
            <h3 class="mt-2"><span class="text-danger fs-2 fw-bold ">|</span>Pembayaran</h3>
            <div class="">
              <p>Pilih metode pembayaran :</p>
              <form action="" method="post">
                <select name="payment" id="payment" class="form-select w-50">
                  <option selected hidden value="tunai">-</option>
                  <option value="tunai">Tunai</option>
                  <option value="gopay">GoPay</option>
                  <option value="dana">Dana</option>
                  <option value="qris">Qris</option>
                  <option value="shopeepay">ShopeePay</option>
                </select>
                <input type="hidden" name="subtot" id="" value="<?= $subtotal['Subtot'] ?>">
                <button name="bayar" onclick="return confirm('Bayar sekarang?')" class="btn btn-success mt-3 mb-5 <?= $empty == 'dibayar' ? 'disabled ' : '' ?>"><i class='bx bx-money bx-tada-hover'></i> Bayar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>






  <!-- MODAL PRODUK -->
  <!-- Modal Body -->
  <div class="modal fade" id="modalProduk" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalProduk" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitleId">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nama" class="form-label ">Nama Produk :</label>
              <input type="text" autocomplete="off" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="jenis" class="form-label ">Jenis Produk :</label>
              <input type="text" autocomplete="off" name="jenis" id="jenis" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="Harga" class="form-label ">Harga :</label>
              <input type="number" name="harga" id="harga" class="form-control" min="1" oninvalid="this.setCustomValidity('tidak boleh menghutang!!')" oninput="this.setCustomValidity('')" required>
            </div>
            <div class="mb-3">
              <label for="Gambar" class="form-label ">Gambar :</label>
              <input type="file" accept="image/*" name="foto" id="gambar" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="send" class="btn btn-success">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Optional: Place to the bottom of scripts -->
  <script>
    const myModal = new bootstrap.Modal(document.getElementById('modalProduk'), options)
  </script>











  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
  </script>
</body>

</html>