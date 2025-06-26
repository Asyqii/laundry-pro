<?= $this->extend('layouts/user') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto py-8">
  <h1 class="text-2xl font-bold mb-2">Halo, <span x-text="$store.user.name">User</span>!</h1>
  <p class="text-on-surface/70 mb-6">Selamat datang di dashboard Laundry Online.</p>

  <div class="bg-surface-alt dark:bg-surface-dark-alt rounded-radius p-4 mb-6 shadow">
    <h2 class="font-semibold mb-2 text-lg">Status Pesanan Terakhir</h2>
    <div class="flex items-center gap-3">
      <i class="fas fa-tshirt text-primary text-2xl"></i>
      <div>
        <div class="font-medium">Cuci Kering Lipat</div>
        <div class="text-sm text-on-surface/60">Status: <span class="font-semibold text-primary">Sedang Diproses</span></div>
        <div class="text-xs text-on-surface/40">Pesanan #123456, 12 Juni 2024</div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <a href="/user/order" class="flex flex-col items-center justify-center bg-primary/10 hover:bg-primary/20 rounded-radius p-4 transition">
      <i class="fas fa-clipboard-list text-2xl mb-2 text-primary"></i>
      <span class="font-medium">Lihat Pesanan</span>
    </a>
    <a href="/user/akun" class="flex flex-col items-center justify-center bg-primary/10 hover:bg-primary/20 rounded-radius p-4 transition">
      <i class="fa-solid fa-user-tie text-2xl mb-2 text-primary"></i>
      <span class="font-medium">Akun Saya</span>
    </a>
  </div>
</div>
<?= $this->endSection() ?>