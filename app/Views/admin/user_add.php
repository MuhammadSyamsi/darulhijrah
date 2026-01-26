<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h4 class="mb-4 font-weight-bold text-primary">
            <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
          </h4>

          <?php if (session('message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= session('message') ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <?php if (session('errors')): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                  <li><?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('/admin/user/store') ?>" method="post" novalidate>
            <?= csrf_field(); ?>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                  value="<?= old('email') ?>" 
                  required 
                />
              </div>
              <div class="form-group col-md-6">
                <label for="username">Username <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  id="username" 
                  name="username" 
                  class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                  value="<?= old('username') ?>" 
                  required
                  autocomplete="off"
                />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                  required 
                  autocomplete="off"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="group">Grup <span class="text-danger">*</span></label>
                <select 
                  id="group" 
                  name="group" 
                  class="form-control <?= session('errors.group') ? 'is-invalid' : '' ?>" 
                  required
                >
                  <option value="" disabled <?= old('group') ? '' : 'selected' ?>>Pilih grup</option>
                  <?php foreach ($groups as $group): ?>
                    <option value="<?= esc($group->name) ?>" <?= old('group') === $group->name ? 'selected' : '' ?>>
                      <?= esc($group->name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="text-right mt-4">
              <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save mr-1"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>
