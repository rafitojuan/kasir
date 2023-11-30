<?php
require '../private/config/function.php';
error_reporting(0);

$random = ['Juan', 'Pirman', 'Setyo', 'Ipul', 'Torip', 'Kagezumi'];
$undi = random_int(0, 5);
$nama = $random[$undi];

$inv = mysqli_query($conn, "SELECT * FROM cart JOIN produk USING(id_produk) WHERE status = 'dibayar'");
$metode = query("SELECT * FROM cart JOIN produk USING(id_produk) WHERE status = 'dibayar'")[0];
$hartot = query("SELECT SUM(total) AS hartot FROM cart WHERE status = 'dibayar'")[0];
?>

<!doctype html>
<html lang="en">

<head>
  <title>Invoice</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <meta http-equiv="refresh" content="0; url=index.php"> -->

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
  <style>
    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
  <div class="d-flex align-items-center justify-content-center flex-column vh-100">
    <div class="card shadow-lg p-3" style="width: 80vh;">
      <div class="card-body">
        <h4 class="text-center">Invoice Kasir</h4>
        <hr>
        <div class="d-flex align-items-center justify-content-between">
          <span class="fw-bold">Kepada :</span>
          <span class="fw-bold">Tanggal :</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <span><?= $nama ?></span>
          <span><?= date('d F Y') ?></span>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <span class="fw-bold">No Invoice : </span>
        </div>
        <div class="d-flex justify-content-end mb-3">
          <span>2023/INV/<?= rand(100, 999) ?></span>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr class="text-center">
                <th scope="col">Produk</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Harga</th>
              </tr>
            </thead>
            <tbody class="table-secondary">
              <?php foreach ($inv as $items) : ?>
                <tr class="text-center">
                  <td scope="row"><?= $items['nama_produk'] ?></td>
                  <td><?= $items['qty'] ?>x</td>
                  <td>Rp.<?= number_format($items['total']) ?></td>
                <?php endforeach; ?>
                </tr>
                <tr class="fw-bold">
                  <td scope="row" colspan="2">Total <span class="ms-4">:</span></td>
                  <td class="text-center">Rp.<?= number_format($hartot['hartot']) ?></td>
                </tr>
            </tbody>
          </table>
          <div class="d-flex justify-content-between">
            <span class="text-center fw-bold">Metode :</span>
            <span class="text-center fw-bold">Status :</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-center"><?= ucwords($metode['pembayaran']) ?></span>
            <span class="text-center"><?= ucwords($metode['status']) ?></span>
          </div>
          <div class="<?= $metode['pembayaran'] == 'tunai' ? 'd-none ' : '' ?>">
            <div class="d-flex justify-content-start mt-3">
              <span class="text-center fw-bold">Rekening :</span>
            </div>
            <div class="d-flex justify-content-start">
              <span class="text-center"><?= rand(100, 800) ?>-<?= rand(000, 500) ?>-<?= rand(1000, 9999) ?>-<?= rand(10, 99) ?></span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
  </script>
  <script>
    // window.print()
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
  </script>
</body>

</html>