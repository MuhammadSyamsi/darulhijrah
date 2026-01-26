<?php if ($result): ?>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Jenjang</th>
      <th>Kelas</th>
      <th>Status</th>
      <th>Tanggal</th>
      <th>Formulir</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($result as $row): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['nama'] ?></td>
      <td><?= $row['jenjang'] ?></td>
      <td><?= $row['kelas'] ?></td>
      <td><?= $row['status'] ?></td>
      <td><?= $row['tanggal'] ?></td>
      <td><?= $row['formulir'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="text-muted">Tidak ada data ditemukan.</p>
<?php endif; ?>
