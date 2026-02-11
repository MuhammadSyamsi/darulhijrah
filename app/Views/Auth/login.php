<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-400 px-4">

    <div class="w-full max-w-md" x-data="{ showPassword: false }">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <!-- Logo -->
            <div class="text-center mb-6">
                <img src="<?= base_url('assets/images/logo.png') ?>" 
                     alt="Logo" 
                     class="h-16 mx-auto mb-3">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?= lang('Auth.loginTitle') ?>
                </h2>
                <p class="text-sm text-gray-500">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Message -->
            <?= view('App\Views\Auth\_message_block') ?>

            <!-- Form -->
            <form action="<?= url_to('login') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>

                <?php if ($config->validFields === ['email']): ?>
                    <!-- Email -->
                    <div>
                        <label class="text-sm text-gray-600"><?= lang('Auth.email') ?></label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400">mail</span>
                            <input type="email"
                                name="login"
                                placeholder="<?= lang('Auth.email') ?>"
                                class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none
                                <?= session('errors.login') ? 'border-red-500' : 'border-gray-300' ?>">
                        </div>
                        <?php if (session('errors.login')) : ?>
                            <p class="text-xs text-red-500 mt-1"><?= session('errors.login') ?></p>
                        <?php endif ?>
                    </div>
                <?php else: ?>
                    <!-- Email / Username -->
                    <div>
                        <label class="text-sm text-gray-600"><?= lang('Auth.emailOrUsername') ?></label>
                        <div class="relative mt-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400">person</span>
                            <input type="text"
                                name="login"
                                placeholder="<?= lang('Auth.emailOrUsername') ?>"
                                class="w-full pl-10 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none
                                <?= session('errors.login') ? 'border-red-500' : 'border-gray-300' ?>">
                        </div>
                        <?php if (session('errors.login')) : ?>
                            <p class="text-xs text-red-500 mt-1"><?= session('errors.login') ?></p>
                        <?php endif ?>
                    </div>
                <?php endif; ?>

                <!-- Password -->
                <div>
                    <label class="text-sm text-gray-600"><?= lang('Auth.password') ?></label>
                    <div class="relative mt-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400">lock</span>

                        <input :type="showPassword ? 'text' : 'password'"
                            name="password"
                            placeholder="<?= lang('Auth.password') ?>"
                            class="w-full pl-10 pr-10 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none
                            <?= session('errors.password') ? 'border-red-500' : 'border-gray-300' ?>">

                        <span @click="showPassword = !showPassword"
                              class="material-symbols-outlined absolute right-3 top-2.5 text-gray-400 cursor-pointer">
                            <span x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                        </span>
                    </div>
                    <?php if (session('errors.password')) : ?>
                        <p class="text-xs text-red-500 mt-1"><?= session('errors.password') ?></p>
                    <?php endif ?>
                </div>

                <!-- Remember -->
                <?php if ($config->allowRemembering): ?>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300"
                            <?= old('remember') ? 'checked' : '' ?>>
                        <?= lang('Auth.rememberMe') ?>
                    </label>
                </div>
                <?php endif; ?>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600
                    text-white font-semibold py-2 rounded-lg shadow-md transition">
                    <?= lang('Auth.loginAction') ?>
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center text-sm text-gray-600 space-y-1">
                <?php if ($config->allowRegistration) : ?>
                    <p>
                        <a href="<?= url_to('register') ?>" class="text-blue-600 hover:underline">
                            <?= lang('Auth.needAnAccount') ?>
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ($config->activeResetter): ?>
                    <p>
                        <a href="<?= url_to('forgot') ?>" class="text-blue-600 hover:underline">
                            <?= lang('Auth.forgotYourPassword') ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-white text-xs mt-4 opacity-80">
            © 2024 <a href="https://syamsi.my.id">Muchammad Samsi</a>
        </p>

    </div>
</div>

<?= $this->endSection() ?>
