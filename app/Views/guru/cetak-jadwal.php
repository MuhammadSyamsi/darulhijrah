<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        h4 { margin-top: 40px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Jadwal Pelajaran</h2>

    <?php foreach ($jadwalGrouped as $hari => $jamData): ?>
        <h4>Hari: <?= esc($hari) ?></h4>
        <table>
            <thead>
                <tr>
                    <th>Jam Ke</th>
                    <?php foreach ($kelasList as $kelas): ?>
                        <th><?= esc($kelas) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jamData as $jam => $kelasRow): ?>
                    <tr>
                        <td><?= $jam ?></td>
                        <?php foreach ($kelasList as $kelas): ?>
                            <td>
                                <?php if (isset($kelasRow[$kelas])): ?>
                                    <div><?= esc($kelasRow[$kelas]['nama_mapel']) ?></div>
                                    <small><?= esc($kelasRow[$kelas]['nama_guru']) ?></small>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
    
    <script>
  window.onload = function () {
    window.print();
  }
</script>

</body>
</html>
