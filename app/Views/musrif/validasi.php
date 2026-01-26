<?= $this->extend('template_musrif'); ?>
<?= $this->section('konten'); ?>

<div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Titipan Saku Santri MTs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Nominal Saku</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mts)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data MTs</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mts as $mt): ?>
                                <tr>
                                    <td><?= esc($mt['nama']); ?></td>
                                    <td><?= esc($mt['kelas']); ?></td>
                                    <td>
                                        <span class="badge badge-pill badge-danger">Rp <?= number_format($mt['saku'], 0, ',', '.'); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Titipan Saku Santri MA</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Nominal Saku</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ma)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data MA</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ma as $m): ?>
                                <tr>
                                    <td><?= esc($m['nama']); ?></td>
                                    <td><?= esc($m['kelas']); ?></td>
                                    <td>
                                        <span class="badge badge-pill badge-danger">Rp <?= number_format($m['saku'], 0, ',', '.'); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection('konten'); ?>
