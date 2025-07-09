

<div class="container mt-3">
    <?php if (isset($_SESSION['pesan'])) : ?>
        <div class="alert alert-info"><?= $_SESSION['pesan'] ?></div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">Data Order</div>
        <div class="card-body">
            <a href="index.php?home" class="btn btn-primary btn-sm mb-3">Entri Order</a>
            
            <!-- Tabel Utama -->
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr> 
                        <th>No</th>
                        <th>No Order</th>
                        <th>No Meja</th>
                        <th>Tanggal Order</th>
                        <th>Total Bayar</th>
                        <th>Keterangan</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $query = mysqli_query($kon, "SELECT * FROM tb_order WHERE status_order = 0 ORDER BY id_order DESC");
                    while($order = mysqli_fetch_assoc($query)) :
                        $total = mysqli_fetch_assoc(mysqli_query($kon, 
                            "SELECT SUM(hartot_dorder) AS total 
                            FROM tb_detail_order 
                            WHERE id_order = '".mysqli_real_escape_string($kon, $order['id_order'])."'"));
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($order['id_order']) ?></td>
                            <td><?= htmlspecialchars($order['meja_order']) ?></td>
                            <td><?= date('d-m-Y H:i', $order['tanggal_order']) ?></td>
                            <td>Rp. <?= rupiah($total['total'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($order['keterangan_order']) ?></td>
                            <td>
                                <!-- Tombol Action -->
                                <button data-target="#modalDetail_<?= $order['id_order'] ?>" 
                                    data-toggle="modal" 
                                    class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<?php 
$query = mysqli_query($kon, "SELECT * FROM tb_order WHERE status_order = 0");
while($order = mysqli_fetch_assoc($query)) : ?>
<div class="modal fade" id="modalDetail_<?= $order['id_order'] ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Order <?= $order['id_order'] ?></h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Masakan</th>
                            <th>Keterangan</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $detail = mysqli_query($kon, 
                            "SELECT d.*, m.nama_masakan, m.harga_masakan 
                            FROM tb_detail_order d
                            JOIN tb_masakan m ON d.id_masakan = m.id_masakan 
                            WHERE d.id_order = '".mysqli_real_escape_string($kon, $order['id_order'])."'");
                        
                        $j = 1;
                        while($row = mysqli_fetch_assoc($detail)) : ?>
                        
                            <tr>
                                <td><?= $j++ ?></td>
                                <td><?= $row['nama_masakan'] ?></td>
                                <td><?= $row[ 'keterangan_dorder'] ?></td>
                                <td>Rp. <?= rupiah($row['harga_masakan']) ?></td>
                                <td><?= $row['jumlah_dorder'] ?></td>
                                <td>Rp. <?= rupiah($row['harga_masakan'] * $row['jumlah_dorder']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>