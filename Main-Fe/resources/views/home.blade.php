@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<!-- Section 1: Stats & Overview -->
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">System Overview</h2>
        <div id="current-time-val" class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"></div>
    </div>

    <!-- STATS GRID - Dioptimalkan untuk lebar 750px -->
    <div class="grid grid-cols-3 gap-3">
        <!-- Total Rumah -->
        <div class="bg-blue-50/50 border border-blue-100 p-3 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-xl shadow-sm flex-shrink-0">🏠</div>
            <div class="min-w-0">
                <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest">Rumah</p>
                <h3 id="dash-houses-count" class="text-lg font-black text-gray-800 truncate">...</h3>
            </div>
        </div>

        <!-- Total Warga -->
        <div class="bg-indigo-50/50 border border-indigo-100 p-3 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-xl shadow-sm flex-shrink-0">👥</div>
            <div class="min-w-0">
                <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">Warga</p>
                <h3 id="dash-occupants-count" class="text-lg font-black text-gray-800 truncate">...</h3>
            </div>
        </div>

        <!-- Terkumpul -->
        <div class="bg-emerald-50/50 border border-emerald-100 p-3 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-xl shadow-sm flex-shrink-0">💰</div>
            <div class="min-w-0">
                <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Kas RT</p>
                <h3 id="dash-cash-count" class="text-sm font-black text-gray-800 truncate">...</h3>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const now = new Date();
        document.getElementById('current-time-val').innerText = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).toUpperCase();
    });
</script>
@endsection

@section('content2')
<!-- Section 2: Financial Diagram -->
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Diagram Keuangan</h2>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Laporan Pemasukan & Tunggakan</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                <span class="text-[9px] font-black text-gray-400 uppercase">Sukses</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                <span class="text-[9px] font-black text-gray-400 uppercase">Tunggakan</span>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative h-[480px] w-full bg-gray-50/30 rounded-3xl p-4">
        <canvas id="dashboardTrendChart"></canvas>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
async function initDashboardLogic() {
    const API = 'http://127.0.0.1:8000/api';
    
    try {
        const [houses, occupants, expenses] = await Promise.all([
            fetch(`${API}/house`).then(r => r.json()),
            fetch(`${API}/occupant`).then(r => r.json()),
            fetch(`${API}/expense`).then(r => r.json())
        ]);

        // Update Stats in Section 1
        document.getElementById('dash-houses-count').innerText = houses.length;
        document.getElementById('dash-occupants-count').innerText = occupants.length;
        
        const paidTotal = expenses.filter(e => e.status === 'SUDAH_BAYAR').reduce((s, e) => s + parseFloat(e.jumlah), 0);
        document.getElementById('dash-cash-count').innerText = 'Rp ' + paidTotal.toLocaleString('id-ID');

        // Render Chart in Section 2
        renderMainChart(expenses);

    } catch (err) {
        console.error("Dashboard Sync Error:", err);
    }
}

function renderMainChart(expenses) {
    const ctx = document.getElementById('dashboardTrendChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const currentM = new Date().getMonth();
    
    // Process Data
    const paidData = new Array(12).fill(0);
    const unpaidData = new Array(12).fill(0);

    expenses.forEach(e => {
        const d = e.tanggal_pembayaran ? new Date(e.tanggal_pembayaran) : new Date(e.created_at);
        const m = d.getMonth();
        if (e.status === 'SUDAH_BAYAR') paidData[m] += parseFloat(e.jumlah);
        else unpaidData[m] += parseFloat(e.jumlah);
    });

    // Last 6 months labels/data
    const labels = months.slice(Math.max(0, currentM - 5), currentM + 1);
    const setPaid = paidData.slice(Math.max(0, currentM - 5), currentM + 1);
    const setUnpaid = unpaidData.slice(Math.max(0, currentM - 5), currentM + 1);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: setPaid,
                    backgroundColor: '#10b981',
                    borderRadius: 12,
                    borderSkipped: false,
                    barThickness: 25
                },
                {
                    label: 'Tunggakan',
                    data: setUnpaid,
                    backgroundColor: '#f87171',
                    borderRadius: 12,
                    borderSkipped: false,
                    barThickness: 25
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: {
                        font: { size: 10, weight: '700' },
                        callback: v => v >= 1000 ? (v / 1000) + 'k' : v
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 10, weight: '700' } }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', initDashboardLogic);
</script>
@endsection
