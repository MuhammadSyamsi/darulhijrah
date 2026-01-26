<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<div class="container-fluid">
  <!-- /// -->
  <div class="row">
    <div class="col-lg-3 d-flex align-items-stretch mb-2">
      <a href="psb" type="button" class="btn btn-light me-2">Total<br /><span class="badge bg-warning rounded-3 fw-semibold"><?php foreach ($total as $total) : echo $total['total'];
                                                                                                                              endforeach; ?></span></a>
      <a href="psbmts" type="button" class="btn btn-light me-2">MTs<br /><span class="badge bg-secondary rounded-3 fw-semibold"><?php foreach ($mts as $mts) : echo $mts['total'];
                                                                                                                                endforeach; ?></span></a>
      <a href="psbma" type="button" class="btn btn-light me-2">MA<br /><span class="badge bg-danger rounded-3 fw-semibold"><?php foreach ($ma as $ma) : echo $ma['total'];
                                                                                                                            endforeach; ?></span></a>
    </div>
    <div class="col-lg-9 d-flex align-items-stretch mb-2">
      <p type="button" class="btn me-2">Formulir<br /><span class="badge bg-dark rounded-3 fw-semibold"><?php foreach ($formulir as $formulir) : echo $formulir['total'];
                                                                                                        endforeach; ?></span></p>
      <p type="button" class="btn me-2">Observasi<br /><span class="badge bg-dark rounded-3 fw-semibold"><?php foreach ($observasi as $obs) : echo $obs['total'];
                                                                                                          endforeach; ?></span></p>
      <p type="button" class="btn me-2">Siap Komitmen<br /><span class="badge bg-dark rounded-3 fw-semibold"><?php foreach ($komitmen as $komitmen) : echo $komitmen['total'];
                                                                                                              endforeach; ?></span></p>
      <p type="button" class="btn me-2">Mengundurkan diri<br /><span class="badge bg-dark rounded-3 fw-semibold"><?php foreach ($mundur as $mundur) : echo $mundur['total'];
                                                                                                                  endforeach; ?></span></p>
      <p type="button" class="btn me-2">Fix Masuk<br /><span class="badge bg-dark rounded-3 fw-semibold"><?php foreach ($fix as $fix) : echo $fix['total'];
                                                                                                          endforeach; ?></span></p>
    </div>
  </div>
  <!-- row 1 -->
  <div class="row">
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Formulir</h3>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jenjang</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Formulir</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($stformulir as $form) : ?>
                    <form action="formulir<?= $form['id']; ?>" method="post">
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $form['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal mb-0"><?php echo $form['jenjang']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <input type="submit" class="btn btn-light mb-1" value="isi" />
                          <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-danger mb-1" value="mundur" formaction="mundur<?= $form['id']; ?>">
                        </td>
                      </tr>
                    </form>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Observasi</h3>
            <form action="daftarbaru_psb" method="post">
              <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle">
                  <thead class="text-dark fs-4">
                    <tr>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Nama</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Jenjang</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Check</h6>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($status as $show) : ?>
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $show['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal mb-0"><?php echo $show['jenjang']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <label class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" id="cek[]" name="cek[]" value="<?= $show['id'] ?>"> Checklist
                          </label>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <?php if ($status != null) { ?>
                  <div class="mb-1 col-lg-4 justify-content-center">
                    <button type="submit" class="form-control btn btn-success m-1">Terima</button>
                  </div>
                <?php } ?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Komitmen</h3>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jenjang</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Komitmen</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($hasil as $hasil) : ?>
                    <form action="komitmen<?= $hasil['id'] ?>" method="post">
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $hasil['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal mb-0"><?php echo $hasil['jenjang']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <input type="submit" class="mb-0 btn btn-primary mb-0" value="Buat" />
                          <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-danger mt-1" value="mundur" formaction="mundur<?= $hasil['id']; ?>">
                        </td>
                      </tr>
                    </form>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Diterima</h3>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jenjang</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Detail</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($diterima as $diterima) : ?>
                    <form action="komitmen<?= $diterima['id'] ?>" method="post">
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $diterima['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal mb-0"><?php echo $diterima['jenjang']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <input type="submit" class="btn btn-light mt-1" value="Edit" formaction="editformulir<?= $diterima['id']; ?>">
                          <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-danger mt-1" value="mundur" formaction="mundur<?= $diterima['id']; ?>">
                        </td>
                      </tr>
                    </form>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- row5 -->
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h1 class="card-title fw-semibold">Mengundurkan Diri</h1>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-0">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jenjang</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Tahun Ajaran</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <form method="post">
                    <?php foreach ($list as $list) : ?>
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-semibold mb-0"><?= $list['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <?= $list['jenjang']; ?>
                        </td>
                        <td class="border-bottom-0">
                          <?= $list['tahunmasuk']; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </form>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="py-6 px-6 text-center">
  <p class="mb-0 fs-4">Didesain dan Dibangun<br /> oleh dan untuk
  <h3>Pak Samsi</h3>
  </p>
</div>
<?= $this->endSection(); ?>