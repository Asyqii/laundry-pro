<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Pesanan & Transaksi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="pesananList()" class="space-y-6">

  <!-- Filter Tabs / Select -->
  <div class="w-full">
    <!-- Mobile: Select -->
    <div class="md:hidden mb-3">
      <select x-model="selectedTab"
        class="w-full border border-outline rounded p-2 text-sm dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark">
        <template x-for="tab in tabs" :key="tab.key">
          <option :value="tab.key" x-text="tab.label"></option>
        </template>
      </select>
    </div>

    <!-- downloader -->
    <div class="flex justify-end gap-2">
      <button @click="exportCSV" class="px-3 py-1.5 text-sm rounded bg-blue-500 hover:bg-blue-600 text-white">
        <i class="fas fa-file-csv mr-1"></i> Export CSV
      </button>
      <button @click="exportXLSX" class="px-3 py-1.5 text-sm rounded bg-green-500 hover:bg-green-600 text-white">
        <i class="fas fa-file-excel mr-1"></i> Export Excel
      </button>
    </div>

    <!-- Desktop Tabs -->
    <div class="hidden md:flex gap-2 overflow-x-auto border-b border-outline dark:border-outline-dark" role="tablist">
      <template x-for="tab in tabs" :key="tab.key">
        <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm h-min" role="tab"
          :aria-selected="selectedTab === tab.key" :tabindex="selectedTab === tab.key ? '0' : '-1'"
          :class="selectedTab === tab.key
            ? 'font-bold text-primary border-b-2 border-primary dark:border-primary-dark dark:text-primary-dark'
            : 'text-on-surface font-medium dark:text-on-surface-dark hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong dark:hover:border-b-outline-dark-strong'"
          x-on:click="selectedTab = tab.key">
          <i :class="tab.icon + ' fa-sm'"></i>
          <span x-text="tab.label"></span>
        </button>
      </template>
    </div>
  </div>

  <!-- Table atau Kosong -->
  <div class="overflow-x-auto border border-outline rounded-xl dark:border-outline-dark mt-4">
    <!-- Search Bar -->
    <div class="p-4 border-b border-outline dark:border-outline-dark">
      <div class="flex items-center gap-2">
        <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            aria-hidden="true"
            class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <input type="search" x-model="searchQuery"
            class="w-full rounded-radius border border-outline bg-surface-alt py-2 pl-10 pr-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
            name="search" placeholder="Cari pesanan..." aria-label="search" />
        </div>
        <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
          class="px-3 py-2 text-xs rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
          Clear
        </button>
      </div>
      <div x-show="searchQuery.length > 0" class="mt-2 text-xs text-gray-600 dark:text-gray-400">
        <span x-text="`Menampilkan ${filteredPesanan.length} dari ${pesanan.length} pesanan`"></span>
      </div>
    </div>

    <template x-if="filteredPesanan.length > 0">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th class="p-4">#</th>
            <th class="p-4">Nama</th>
            <th class="p-4">Layanan</th>
            <th class="p-4">Total</th>
            <th class="p-4">Status</th>
            <th class="p-4">Bayar</th>
            <th class="p-4">Tgl Bayar</th>
            <th class="p-4">Masuk</th>
            <th class="p-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <template x-for="(row, i) in filteredPesanan" :key="row.id">
            <tr>
              <td class="p-4" x-text="i + 1"></td>
              <td class="p-4" x-text="row.nama"></td>
              <td class="p-4" x-text="row.layanan"></td>
              <td class="p-4" x-text="formatRupiah(row.total)"></td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="badgeClass(row.status)" x-text="row.status"></span>
              </td>
              <td class="p-4">
                <span class="inline-block text-xs px-2 py-1 rounded-full font-medium text-white"
                  :class="badgeBayar(row.status_bayar)" x-text="row.status_bayar"></span>
              </td>
              <td class="p-4" x-text="row.tgl_bayar ? formatTanggal(row.tgl_bayar) : '-'"></td>
              <td class="p-4" x-text="formatTanggal(row.created_at)"></td>
              <td class="p-4">
                <div x-data="{ isOpen: false, openedWithKeyboard: false }" class="relative w-fit"
                  x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false">

                  <!-- Toggle Button -->
                  <button type="button" x-on:click="isOpen = !isOpen"
                    x-bind:class="isOpen || openedWithKeyboard ? 'text-on-surface-strong dark:text-on-surface-dark-strong' : 'text-on-surface dark:text-on-surface-dark'"
                    class="inline-flex items-center gap-2 whitespace-nowrap rounded-radius border border-outline bg-surface-alt px-4 py-1.5 text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-outline-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:focus-visible:outline-outline-dark-strong"
                    aria-haspopup="true" x-on:keydown.space.prevent="openedWithKeyboard = true"
                    x-on:keydown.enter.prevent="openedWithKeyboard = true"
                    x-on:keydown.down.prevent="openedWithKeyboard = true"
                    x-bind:aria-expanded="isOpen || openedWithKeyboard">
                    Aksi
                    <svg aria-hidden="true" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" class="size-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                  </button>

                  <!-- Dropdown Menu -->
                  <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard"
                    x-on:click.outside="isOpen = false, openedWithKeyboard = false"
                    x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()"
                    class="absolute top-11 left-0 z-20 flex w-fit min-w-40 flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt shadow-lg"
                    role="menu">

                    <button @click="alert('Detail: ' + row.nama)"
                      class="text-left px-4 py-2 text-sm text-on-surface hover:bg-primary/10 hover:text-on-surface-strong focus-visible:bg-primary/20 focus-visible:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/10 dark:hover:text-on-surface-dark-strong"
                      role="menuitem">
                      <i class="fas fa-sync-alt fa-sm mr-2"></i> Detail
                    </button>

                    <button @click="alert('Update Status: ' + row.nama)"
                      class="text-left px-4 py-2 text-sm text-on-surface hover:bg-primary/10 hover:text-on-surface-strong focus-visible:bg-primary/20 focus-visible:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/10 dark:hover:text-on-surface-dark-strong"
                      role="menuitem">
                      <i class="fas fa-sync-alt fa-sm mr-2"></i> Update Status
                    </button>

                    <button @click="alert('Edit: ' + row.nama)"
                      class="text-left px-4 py-2 text-sm text-on-surface hover:bg-primary/10 hover:text-on-surface-strong focus-visible:bg-primary/20 focus-visible:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/10 dark:hover:text-on-surface-dark-strong"
                      role="menuitem">
                      <i class="fas fa-pen fa-sm mr-2"></i> Edit
                    </button>

                    <button @click="hapus(row.id)"
                      class="text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 focus-visible:bg-red-200 dark:text-red-400 dark:hover:bg-red-900/20 dark:focus-visible:bg-red-900/30"
                      role="menuitem">
                      <i class="fas fa-trash-alt fa-sm mr-2"></i> Delete
                    </button>

                  </div>
                </div>
              </td>

            </tr>
          </template>
        </tbody>
      </table>
    </template>

    <template x-if="filteredPesanan.length === 0">
      <div class="flex flex-col items-center justify-center text-center p-10 gap-4 text-muted dark:text-muted-dark">
        <i class="fas fa-inbox fa-3x opacity-30"></i>
        <p class="text-sm">Tidak ada pesanan untuk kategori ini.</p>
      </div>
    </template>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function pesananList() {
  return {
    selectedTab: 'Semua',
    searchQuery: '',
    tabs: [{
        key: 'Semua',
        label: 'Semua',
        icon: 'fas fa-list'
      },
      {
        key: 'Dijemput',
        label: 'Dijemput',
        icon: 'fas fa-truck'
      },
      {
        key: 'Diterima',
        label: 'Diterima',
        icon: 'fas fa-inbox'
      },
      {
        key: 'Dicuci',
        label: 'Dicuci',
        icon: 'fas fa-water'
      },
      {
        key: 'Dikeringkan',
        label: 'Dikeringkan',
        icon: 'fas fa-wind'
      },
      {
        key: 'Disetrika',
        label: 'Disetrika',
        icon: 'fas fa-fire'
      },
      {
        key: 'Dilipat',
        label: 'Dilipat',
        icon: 'fas fa-layer-group'
      },
      {
        key: 'Diantar',
        label: 'Diantar',
        icon: 'fas fa-motorcycle'
      },
      {
        key: 'Selesai',
        label: 'Selesai',
        icon: 'fas fa-check-circle'
      }
    ],
    pesanan: [{
        id: 1,
        nama: 'Andi',
        layanan: 'Cuci Setrika',
        total: 15000,
        status: 'Dicuci',
        status_bayar: 'Belum Bayar',
        metode_bayar: 'QRIS',
        tgl_bayar: '',
        created_at: '2025-06-23'
      },
      {
        id: 2,
        nama: 'Budi',
        layanan: 'Setrika Saja',
        total: 8000,
        status: 'Diterima',
        status_bayar: 'Lunas',
        metode_bayar: 'Cash',
        tgl_bayar: '2025-06-22',
        created_at: '2025-06-22'
      },
      {
        id: 3,
        nama: 'Citra',
        layanan: 'Full Service',
        total: 25000,
        status: 'Dijemput',
        status_bayar: 'Pending',
        metode_bayar: 'Transfer',
        tgl_bayar: '',
        created_at: '2025-06-22'
      },
    ],
    get filteredPesanan() {
      let data = this.pesanan;
      if (this.selectedTab !== 'Semua') {
        data = data.filter(p => p.status === this.selectedTab);
      }
      if (this.searchQuery && this.searchQuery.trim() !== '') {
        const q = this.searchQuery.trim().toLowerCase();
        data = data.filter(p => {
          return (
            (p.nama && p.nama.toLowerCase().includes(q)) ||
            (p.layanan && p.layanan.toLowerCase().includes(q)) ||
            (p.status && p.status.toLowerCase().includes(q)) ||
            (p.status_bayar && p.status_bayar.toLowerCase().includes(q)) ||
            (p.metode_bayar && p.metode_bayar.toLowerCase().includes(q)) ||
            (typeof p.total !== 'undefined' && String(p.total).toLowerCase().includes(q)) ||
            (p.tgl_bayar && p.tgl_bayar.toLowerCase().includes(q)) ||
            (p.created_at && p.created_at.toLowerCase().includes(q))
          );
        });
      }
      return data;
    },
    formatRupiah(n) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(n).replace(",00", "");
    },
    formatTanggal(t) {
      return new Date(t).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
    },
    badgeClass(status) {
      return {
        'Dijemput': 'bg-blue-400 dark:bg-blue-700',
        'Diterima': 'bg-sky-400 dark:bg-sky-700',
        'Dicuci': 'bg-indigo-400 dark:bg-indigo-700',
        'Dikeringkan': 'bg-yellow-400 dark:bg-yellow-700',
        'Disetrika': 'bg-orange-400 dark:bg-orange-700',
        'Dilipat': 'bg-pink-400 dark:bg-pink-700',
        'Diantar': 'bg-purple-400 dark:bg-purple-700',
        'Selesai': 'bg-green-400 dark:bg-green-700',
      } [status] || 'bg-gray-400 dark:bg-gray-700';
    },
    badgeBayar(status) {
      return {
        'Lunas': 'bg-green-500 dark:bg-green-700',
        'Belum Bayar': 'bg-red-500 dark:bg-red-700',
        'Pending': 'bg-yellow-400 dark:bg-yellow-700',
      } [status] || 'bg-gray-400 dark:bg-gray-700';
    },
    hapus(id) {
      this.pesanan = this.pesanan.filter(p => p.id !== id);
    },
    exportCSV() {
      const rows = this.pesanan.map(p => ({
        ID: p.id,
        Nama: p.nama,
        Layanan: p.layanan,
        Total: p.total,
        Status: p.status,
        Status_Bayar: p.status_bayar,
        Metode: p.metode_bayar,
        Tgl_Bayar: p.tgl_bayar,
        Tanggal_Masuk: p.created_at
      }));
      const csv = [
        Object.keys(rows[0]).join(','),
        ...rows.map(row => Object.values(row).join(','))
      ].join('\n');

      const blob = new Blob([csv], {
        type: 'text/csv;charset=utf-8;'
      });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.setAttribute('download', 'pesanan.csv');
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    exportXLSX() {
      const rows = this.pesanan.map(p => ({
        ID: p.id,
        Nama: p.nama,
        Layanan: p.layanan,
        Total: p.total,
        Status: p.status,
        Status_Bayar: p.status_bayar,
        Metode: p.metode_bayar,
        Tgl_Bayar: p.tgl_bayar,
        Tanggal_Masuk: p.created_at
      }));
      const worksheet = XLSX.utils.json_to_sheet(rows);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "Pesanan");
      XLSX.writeFile(workbook, "pesanan.xlsx");
    }
  }
}
</script>
<?= $this->endSection() ?>