<?php 
  require 'connection.php';
  checkLogin();
  if (isset($_POST['btnLaporanPemasukan'])) {
  	$dari_tanggal_date = htmlspecialchars($_POST['dari_tanggal']);
  	$sampai_tanggal_date = htmlspecialchars($_POST['sampai_tanggal']);
  	$dari_tanggal = strtotime(htmlspecialchars($_POST['dari_tanggal'] . " 00:00:00"));
  	$sampai_tanggal = strtotime(htmlspecialchars($_POST['sampai_tanggal'] . " 23:59:59"));
  	$sql = mysqli_query($conn, "SELECT * FROM pemasukan INNER JOIN user ON pemasukan.id_user = user.id_user WHERE tanggal_pemasukan BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
  	$fetch_sql = mysqli_fetch_assoc($sql);
  	$btnClick = true;
  	$titleLaporan = "Pemasukan";
  }
  if (isset($_POST['btnLaporanPengeluaran'])) {
  	$dari_tanggal_date = htmlspecialchars($_POST['dari_tanggal']);
  	$sampai_tanggal_date = htmlspecialchars($_POST['sampai_tanggal']);
  	$dari_tanggal = strtotime(htmlspecialchars($_POST['dari_tanggal'] . " 00:00:00"));
  	$sampai_tanggal = strtotime(htmlspecialchars($_POST['sampai_tanggal'] . " 23:59:59"));
  	$sql = mysqli_query($conn, "SELECT * FROM pengeluaran INNER JOIN user ON pengeluaran.id_user = user.id_user WHERE tanggal_pengeluaran BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
  	$fetch_sql = mysqli_fetch_assoc($sql);
  	$btnClick = true;
  	$titleLaporan = "Pengeluaran";
  }
  if (isset($_POST['btnLaporanPemasukanDanPengeluaran'])) {
  	$dari_tanggal_date = htmlspecialchars($_POST['dari_tanggal']);
    $sampai_tanggal_date = htmlspecialchars($_POST['sampai_tanggal']);
    $dari_tanggal = strtotime(htmlspecialchars($_POST['dari_tanggal'] . " 00:00:00"));
    $sampai_tanggal = strtotime(htmlspecialchars($_POST['sampai_tanggal'] . " 23:59:59"));
    
    // Fetch both income and expense data from the database
    $sql_pemasukan = mysqli_query($conn, "SELECT *, 'Pemasukan' as jenis_transaksi FROM pemasukan INNER JOIN user ON pemasukan.id_user = user.id_user WHERE tanggal_pemasukan BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
    $sql_pengeluaran = mysqli_query($conn, "SELECT *, 'Pengeluaran' as jenis_transaksi FROM pengeluaran INNER JOIN user ON pengeluaran.id_user = user.id_user WHERE tanggal_pengeluaran BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
    
    // Merge both result sets
    $fetch_sql = array();
    while ($row = mysqli_fetch_assoc($sql_pemasukan)) {
        $fetch_sql[] = $row;
    }
    while ($row = mysqli_fetch_assoc($sql_pengeluaran)) {
        $fetch_sql[] = $row;
    }
    
    
    $btnClick = true;
    $titleLaporan = "Pemasukan dan Pengeluaran";
  }
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Laporan</title>
  <style>
  	@media print {
	  	.not-printed {
	  		display: none;
	  	}
	  	.total {
	  		color: black !important;
	  	}
  	}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include 'include/navbar.php'; ?>

  <?php include 'include/sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Laporan</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="not-printed row justify-content-center">
        	<div class="col-lg-10 ml-4">
        		<form method="post">
        			<div class="row">
        				<div class="col-lg">
        					<div class="form-group">
		        				<label for="dari_tanggal">Dari Tanggal</label>
		        				<?php if (isset($_POST['btnLaporanPengeluaran'])): ?>
			        				<input type="date" name="dari_tanggal" class="form-control" id="dari_tanggal" value="<?= $_POST['dari_tanggal']; ?>">
	        					<?php else: ?>
			        				<input type="date" name="dari_tanggal" class="form-control" id="dari_tanggal" value="<?= date('Y-m-01'); ?>">
		        				<?php endif ?>
		        			</div>
        				</div>
        				<div class="col-lg">
        					<div class="form-group">
		        				<label for="sampai_tanggal">Sampai Tanggal</label>
		        				<?php if (isset($_POST['btnLaporanPengeluaran'])): ?>
			        				<input type="date" name="sampai_tanggal" class="form-control" id="sampai_tanggal" value="<?= $_POST['sampai_tanggal']; ?>">
	        					<?php else: ?>
			        				<input type="date" name="sampai_tanggal" class="form-control" id="sampai_tanggal" value="<?= date('Y-m-d'); ?>">
		        				<?php endif ?>
		        			</div>
        				</div>
        			</div>
        			<div class="form-group">
        				<button type="submit" name="btnLaporanPemasukan" class="btn btn-primary">Laporan Pemasukan</button>
        				<button type="submit" name="btnLaporanPengeluaran" class="btn btn-primary">Laporan Pengeluaran</button>
        				<button type="submit" name="btnLaporanPemasukanDanPengeluaran" class="btn btn-primary">Laporan Pemasukan dan Pengeluaran</button>
        			</div>
        		</form>
        	</div>
        </div>
        <?php if (isset($btnClick)): ?>
        	<hr class="not-printed">
        	<button onclick="return print()" class="not-printed btn btn-success"><i class="fas fa-fw fa-print"></i> Print</button>
        	<div class="row m-1 mb-0">
	        	<div class="col-lg m-1">
	        		<h2 class="text-center mb-3 mt-2">Laporan <?= $titleLaporan; ?></h2>
	        		<h3 class="text-left mb-3">Laporan Dari Tanggal: <?= $dari_tanggal_date; ?> Sampai Tanggal: <?= $sampai_tanggal_date; ?></h3>
	        		<div class="table-responsive">
	        			<table class="table table-bordered table-hover">
	        				<thead>
	        					<tr>
	        						<th>No.</th>
	        						<th><?= $titleLaporan; ?></th>
	        						<th>Keterangan</th>
	        						<?php if ($titleLaporan == "Pemasukan dan Pengeluaran"): ?>
	        							<th>Jenis Transaksi</th>
	        						<?php endif ?>
	        						<th>Tanggal <?= $titleLaporan; ?></th>
	        						<th>Username</th>
	        					</tr>
	        				</thead>
	        				<tbody>
	        					<?php $i = 1; ?>
        						<?php $total = '0'; ?>
	        					<?php if ($titleLaporan == "Pemasukan"): ?>
		        					<?php foreach ($sql as $ds): ?>
		        						<tr>
		        							<td><?= $i++; ?></td>
		        							<td><?= number_format($ds['jumlah_pemasukan']); ?></td>
		        							<td><?= $ds['keterangan']; ?></td>
		        							<td><?= date('d-m-Y, H:i:s', $ds['tanggal_pemasukan']); ?></td>
		        							<td><?= $ds['username']; ?></td>
		        						</tr>
		        						<?php 
		        							$total += $ds['jumlah_pemasukan'];
		        						?>
		        					<?php endforeach ?>
		        				<?php elseif($titleLaporan == "Pemasukan dan Pengeluaran"): ?>
		        					<?php foreach ($fetch_sql as $ds): ?>
										    <tr>
										        <td><?= $i++; ?></td>
										        <?php if ($ds['jenis_transaksi'] == 'Pemasukan'): ?>
										            <td><?= number_format($ds['jumlah_pemasukan']); ?></td>
										            <td><?= $ds['keterangan']; ?></td>
										            <td><?= $ds['jenis_transaksi']; ?></td>
										            <td><?= date('d-m-Y, H:i:s', $ds['tanggal_pemasukan']); ?></td>
										        <?php else: ?>
										            <td><?= number_format($ds['jumlah_pengeluaran']); ?></td>
										            <td><?= $ds['keterangan']; ?></td>
										            <td><?= $ds['jenis_transaksi']; ?></td>
										            <td><?= date('d-m-Y, H:i:s', $ds['tanggal_pengeluaran']); ?></td>
										        <?php endif ?>
										        <td><?= $ds['username']; ?></td>
										    </tr>
										    <?php 
										        if ($ds['jenis_transaksi'] == 'Pemasukan') {
										            $total += $ds['jumlah_pemasukan'];
										        } else {
										            $total -= $ds['jumlah_pengeluaran'];
										        }
										    ?>
										<?php endforeach ?>
        						<?php else: ?>
		        					<?php foreach ($sql as $ds): ?>
		        						<tr>
		        							<td><?= $i++; ?></td>
		        							<td><?= number_format($ds['jumlah_pengeluaran']); ?></td>
		        							<td><?= $ds['keterangan']; ?></td>
		        							<td><?= date('d-m-Y, H:i:s', $ds['tanggal_pengeluaran']); ?></td>
		        							<td><?= $ds['username']; ?></td>
		        						</tr>
		        						<?php 
		        							$total += $ds['jumlah_pengeluaran'];
		        						?>
		        					<?php endforeach ?>
	        					<?php endif ?>
	        				</tbody>
	        			</table>
	        		</div>
	        	</div>
	        </div>
    		<div class="row mx-1 mb-1 mt-0">
    			<div class="col-lg-4">
		    		<div class="p-3 rounded bg-success total">Total <?= $titleLaporan; ?>: Rp. <?= number_format($total); ?></div>
    			</div>
    		</div>
        <?php endif ?>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
	<script>
		$(document).ready(function() {
			function print() {
				window.print();
			}
		});
	</script>
</div>
</body>
</html>
