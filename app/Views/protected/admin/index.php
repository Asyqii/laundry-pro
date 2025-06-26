<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">

  <!-- Title -->
  <div>
    <h1 class="text-2xl font-semibold text-on-surface dark:text-on-surface-dark">Selamat datang di Dashboard Admin</h1>
    <p class="text-sm text-muted dark:text-muted-dark">Pantau aktivitas laundry Anda di sini.</p>
  </div>

  <!-- Stats Cards -->
  <div x-data="dashboardStats()" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <template x-for="item in stats" :key="item.label">
      <article
        class="group grid rounded-radius max-w-2xl overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <div class="flex items-center justify-between w-full p-6">
          <div class="flex flex-col justify-center w-full">
            <p class="text-sm mb-2 text-muted" x-text="item.label"></p>
            <h2 class="text-2xl font-bold" x-text="item.value"></h2>
          </div>
          <i :class="item.icon + ' fa-2x ' + item.color"></i>
        </div>
      </article>
    </template>
  </div>


  <!-- Grafik -->
  <div class="bg-surface rounded-xl dark:bg-surface-dark">
    <h3 class="text-lg font-semibold mb-2 text-on-surface dark:text-on-surface-dark">Grafik Pesanan 7 Hari Terakhir</h3>
    <div
      class="h-64 w-full bg-gray-100 dark:bg-gray-800 rounded flex items-center justify-center text-muted border border-outline dark:border-outline-dark">
      <canvas id="ordersChart" style="width: 100%;" class="w-full"></canvas>
    </div>
  </div>

  <!-- Tabel Antrian -->
  <div class="bg-surface rounded-xl dark:bg-surface-dark">
    <h3 class="text-lg font-semibold mb-2 text-on-surface dark:text-on-surface-dark">Transaksi terakhir
    </h3>
    <div class="border border-outline rounded-radius overflow-x-auto dark:border-outline-dark">
      <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead
          class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
          <tr>
            <th scope="col" class="p-4">Nama</th>
            <th scope="col" class="p-4">Layanan</th>
            <th scope="col" class="p-4">Status</th>
            <th scope="col" class="p-4">Masuk</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
          <tr>
            <td class="p-4">2335</td>
            <td class="p-4">Alice Brown</td>
            <td class="p-4">alice.brown@gmail.com</td>
            <td class="p-4">Silver</td>
          </tr>
          <tr>
            <td class="p-4">2338</td>
            <td class="p-4">Bob Johnson</td>
            <td class="p-4">johnson.bob@outlook.com</td>
            <td class="p-4">Gold</td>
          </tr>
          <tr>
            <td class="p-4">2342</td>
            <td class="p-4">Sarah Adams</td>
            <td class="p-4">s.adams@gmail.com</td>
            <td class="p-4">Gold</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


</div>
<script>
function dashboardStats() {
  return {
    stats: [{
        label: "Pesanan Hari Ini",
        value: 80,
        icon: "fas fa-clipboard-list",
        color: "text-primary"
      },
      {
        label: "Dalam Proses",
        value: 24,
        icon: "fas fa-sync-alt",
        color: "text-yellow-500"
      },
      {
        label: "Total Pelanggan",
        value: 125,
        icon: "fas fa-users",
        color: "text-blue-500"
      },
      {
        label: "Pendapatan Hari Ini",
        value: "Rp 450.000",
        icon: "fas fa-money-bill-wave",
        color: "text-green-600"
      }
    ]
  };
}
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    datasets: [{
        label: 'Pesanan',
        data: [12, 19, 7, 15, 10, 17, 14],
        borderColor: 'rgba(59, 130, 246, 1)',
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
        pointBorderColor: '#fff',
        yAxisID: 'y'
      },
      {
        label: 'Pendapatan',
        data: [11000, 189000, 87000, 17000, 14000, 16000, 15000],
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34, 197, 94, 0.2)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#22c55e',
        pointBorderColor: '#fff',
        yAxisID: 'y1'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: 'index',
      intersect: false
    },
    plugins: {
      legend: {
        display: true,
        labels: {
          color: '#64748b',
          font: {
            size: 14
          }
        }
      },
      tooltip: {
        mode: 'index',
        intersect: false,
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            let value = context.parsed.y;
            if (context.dataset.yAxisID === 'y1') {
              return label + ': Rp ' + value.toLocaleString();
            }
            return label + ': ' + value;
          }
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: '#64748b'
        },
        grid: {
          color: '#e5e7eb'
        }
      },
      y: {
        beginAtZero: true,
        position: 'left',
        ticks: {
          display: false,
          color: '#64748b'
        },
        grid: {
          color: '#e5e7eb'
        },
        title: {
          display: true,
          text: 'Jumlah Pesanan',
          color: '#64748b',
          font: {
            size: 12
          }
        }
      },
      y1: {
        beginAtZero: true,
        position: 'right',
        ticks: {
          display: false,
          color: '#64748b',
          callback: function(value) {
            return 'Rp ' + value / 1000 + 'k';
          }
        },
        grid: {
          drawOnChartArea: false
        },
        title: {
          display: true,
          text: 'Pendapatan (Rp)',
          color: '#64748b',
          font: {
            size: 12
          }
        }
      }
    }
  }
});
</script>

<?= $this->endSection() ?>