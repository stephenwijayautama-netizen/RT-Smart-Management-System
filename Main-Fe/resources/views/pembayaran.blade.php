@extends('layout.app')

@section('title', 'Pembayaran')

@section('content')
    <h2 class="text-3xl font-bold flex justify-start">Pembayaran</h2>
@endsection

@section('content2')
<div class="space-y-6 relative z-10">

    <!-- HEADER CARD -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mt-1">Kelola data iuran & pembayaran warga RT</p>
        </div>
        <button onclick="openModal()"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow">
            + Tambah Pembayaran
        </button>
    </div>

    <!-- RINGKASAN STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl">💰</div>
            <div>
                <p class="text-xs text-gray-500">Total Tagihan</p>
                <p id="stat-total" class="text-xl font-bold text-gray-800">–</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">✅</div>
            <div>
                <p class="text-xs text-gray-500">Sudah Bayar</p>
                <p id="stat-lunas" class="text-xl font-bold text-green-600">–</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">⏳</div>
            <div>
                <p class="text-xs text-gray-500">Belum Bayar</p>
                <p id="stat-belum" class="text-xl font-bold text-red-500">–</p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="bg-white rounded-xl shadow p-4 flex flex-wrap gap-3 items-center">
        <input type="text" id="searchInput" placeholder="🔍 Cari penghuni / rumah..."
               oninput="filterTable()"
               class="flex-1 min-w-[200px] border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        <select id="filterStatus" onchange="filterTable()"
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
            <option value="">Semua Status</option>
            <option value="SUDAH_BAYAR">Sudah Bayar</option>
            <option value="BELUM_BAYAR">Belum Bayar</option>
        </select>
    </div>

    <!-- TABEL PEMBAYARAN -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-5 py-4">#</th>
                    <th class="px-5 py-4">Penghuni</th>
                    <th class="px-5 py-4">Rumah</th>
                    <th class="px-5 py-4">Kategori Iuran</th>
                    <th class="px-5 py-4">Durasi</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="expense-table-body" class="divide-y divide-gray-100 text-gray-700">
                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-4xl">⏳</span>
                            <span>Memuat data...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- MODAL TAMBAH PEMBAYARAN -->
    <div id="expenseModal" class="fixed inset-0 bg-black/40 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">✕</button>
            <h3 class="text-xl font-bold mb-4">Tambah Data Pembayaran</h3>

            <!-- Alert -->
            <div id="formAlert" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>

            <div class="space-y-4">
                <!-- Pilih Rumah -->
                <div>
                    <label class="block text-sm font-medium mb-1">Rumah <span class="text-red-500">*</span></label>
                    <select id="input_house_id" onchange="onHouseChange()"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="">-- Pilih Rumah --</option>
                    </select>
                </div>
                <!-- Pilih Penghuni (Dinamis berdasarkan Rumah) -->
                <div>
                    <label class="block text-sm font-medium mb-1">Penghuni Aktif <span class="text-red-500">*</span></label>
                    <select id="input_occupant_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="">-- Pilih Rumah Terlebih Dahulu --</option>
                    </select>
                </div>
                <!-- Kategori Iuran -->
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori Iuran <span class="text-red-500">*</span></label>
                    <select id="input_category_id" onchange="onCategoryChange()"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="">-- Pilih Kategori --</option>
                    </select>
                </div>
                <!-- Durasi -->
                <div>
                    <label class="block text-sm font-medium mb-1">Lama Pembayaran <span class="text-red-500">*</span></label>
                    <select id="input_durasi" onchange="updateInvoiceSummary()"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">1 Tahun (12 Bulan)</option>
                    </select>
                </div>

                <!-- INVOICE SUMMARY -->
                <div id="invoiceSummary" class="hidden bg-blue-50/50 border-2 border-dashed border-blue-100 rounded-2xl p-4 space-y-3">
                    <div class="flex justify-between items-center text-[10px] font-black text-blue-500 uppercase tracking-widest">
                        <span>Invoice Summary</span>
                        <span>Pre-Payment</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Iuran / Kategori</span>
                            <span id="inv-category" class="font-bold text-gray-800">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga per Bulan</span>
                            <span id="inv-price" class="font-bold text-gray-800">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Durasi</span>
                            <span id="inv-duration" class="font-bold text-blue-600">-</span>
                        </div>
                        <div class="pt-2 border-t border-blue-100 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-800">Total Pembayaran</span>
                            <span id="inv-total" class="text-lg font-black text-blue-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">Batal</button>
                    <button type="button" id="btnNext" onclick="proceedToPayment()"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition flex items-center gap-2">
                        Lanjut Pembayaran
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>

            <!-- STEP 2: CONFIRMATION (HIDDEN BY DEFAULT) -->
            <div id="paymentStep2" class="hidden space-y-6 pt-2">
                <div class="text-center space-y-2">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto text-2xl animate-bounce">
                        💳
                    </div>
                    <h4 class="text-lg font-black text-gray-800">Konfirmasi Pembayaran</h4>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Silakan periksa kembali detail tagihan</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nama Penghuni</span>
                        <span id="conf-name" class="font-bold text-gray-800">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nomor Rumah</span>
                        <span id="conf-house" class="font-bold text-gray-800">-</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between items-center text-blue-600">
                        <span class="text-sm font-black uppercase">Total Tagihan</span>
                        <span id="conf-total" class="text-xl font-black">Rp 0</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="button" id="btnSimpan" onclick="submitTambahPembayaran()"
                            class="w-full py-3 rounded-xl bg-blue-600 text-white font-black shadow-lg shadow-blue-200 hover:bg-blue-700 transition transform active:scale-95">
                        BAYAR SEKARANG
                    </button>
                    <button type="button" onclick="cancelPayment()" class="w-full py-2 text-xs font-bold text-gray-400 hover:text-gray-600 transition">
                        Kembali & Ubah Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UPDATE STATUS -->
    <div id="updateModal" class="fixed inset-0 bg-black/40 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-sm p-6 relative">
            <button onclick="closeUpdateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">✕</button>
            <h3 class="text-lg font-bold mb-4">Update Status Pembayaran</h3>
            <div id="updateAlert" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>
            <input type="hidden" id="update_expense_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Status Baru</label>
                    <select id="update_status"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="BELUM_BAYAR">Belum Bayar</option>
                        <option value="SUDAH_BAYAR">Sudah Bayar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Pembayaran</label>
                    <input type="date" id="update_tanggal"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="closeUpdateModal()" class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50">Batal</button>
                    <button onclick="submitUpdateStatus()" id="btnUpdate"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-700 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
const API = 'http://127.0.0.1:8000/api';

let allExpenses = [];
let categories  = [];
let occupants   = [];
let houses      = [];
let histories   = [];

// ============================
// LOAD DATA SAAT HALAMAN DIBUKA
// ============================
document.addEventListener('DOMContentLoaded', () => {
    loadAll();
});

function loadAll() {
    Promise.all([
        fetch(`${API}/expense`).then(r => r.json()),
        fetch(`${API}/expense-category`).then(r => r.json()),
        fetch(`${API}/occupant`).then(r => r.json()),
        fetch(`${API}/house`).then(r => r.json()),
        fetch(`${API}/houseoccupanthistory`).then(r => r.json()),
    ]).then(([expenseData, categoryData, occupantData, houseData, historyData]) => {
        allExpenses = expenseData;
        categories  = categoryData;
        occupants   = occupantData;
        houses      = houseData;
        histories   = historyData;

        renderTable(allExpenses);
        renderStats(allExpenses);
        populateDropdowns();
    }).catch(err => {
        document.getElementById('expense-table-body').innerHTML = `
            <tr><td colspan="8" class="text-center py-10 text-red-500">❌ Gagal memuat data: ${err.message}</td></tr>
        `;
    });
}

// ============================
// RENDER TABEL
// ============================
function renderTable(data) {
    const tbody = document.getElementById('expense-table-body');
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-12 text-gray-400">
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-4xl">💸</span>
                        <span>Belum ada data pembayaran.</span>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = data.map((e, i) => {
        const occupant = e.occupant ? e.occupant.nama_lengkap : (occupants.find(o => o.id === e.occupant_id)?.nama_lengkap ?? '-');
        const house    = e.house    ? `Rumah ${e.house.nomor_rumah}` : (houses.find(h => h.id === e.house_id) ? `Rumah ${houses.find(h => h.id === e.house_id).nomor_rumah}` : '-');
        const category = e.category ? e.category.jenis_iuran : (categories.find(c => c.id === e.category_id)?.jenis_iuran ?? '-');

        return `
            <tr class="hover:bg-gray-50 transition" data-status="${e.status}" data-occupant="${occupant.toLowerCase()}" data-house="${house.toLowerCase()}">
                <td class="px-5 py-4 text-gray-500">${i + 1}</td>
                <td class="px-5 py-4 font-medium">${occupant}</td>
                <td class="px-5 py-4">${house}</td>
                <td class="px-5 py-4">${category}</td>
                <td class="px-5 py-4 font-bold text-blue-600">${e.durasi || 1} bln</td>
                <td class="px-5 py-4 text-center">
                    <button onclick="openUpdateModal('${e.id}', '${e.status}', '${e.tanggal_pembayaran ?? ''}')"
                            class="text-blue-600 text-xs font-semibold hover:underline">Edit Status</button>
                </td>
            </tr>
        `;
    }).join('');
}

// ============================
// RENDER STATISTIK
// ============================
function renderStats(data) {
    const total  = data.length;
    const lunas  = data.filter(e => e.status === 'SUDAH_BAYAR').length;
    const belum  = data.filter(e => e.status === 'BELUM_BAYAR').length;
    document.getElementById('stat-total').textContent = total + ' tagihan';
    document.getElementById('stat-lunas').textContent = lunas + ' lunas';
    document.getElementById('stat-belum').textContent = belum + ' belum';
}

// ============================
// POPULATE DROPDOWNS MODAL
// ============================
function populateDropdowns() {
    // Reset selections
    const selOccupant = document.getElementById('input_occupant_id');
    selOccupant.innerHTML = '<option value="">-- Pilih Rumah Terlebih Dahulu --</option>';

    const selHouse = document.getElementById('input_house_id');
    selHouse.innerHTML = '<option value="">-- Pilih Rumah --</option>';
    houses.forEach(h => {
        const opt = document.createElement('option');
        opt.value = h.id;
        opt.textContent = `Rumah ${h.nomor_rumah}`;
        selHouse.appendChild(opt);
    });

    const selCat = document.getElementById('input_category_id');
    selCat.innerHTML = '<option value="">-- Pilih Kategori --</option>';
    categories.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = `${c.jenis_iuran} – Rp ${Number(c.jumlah).toLocaleString('id-ID')}`;
        opt.dataset.jumlah = c.jumlah;
        selCat.appendChild(opt);
    });
}

// Filter penghuni berdasarkan rumah yang dipilih (Hanya yang AKTIF)
function onHouseChange() {
    const houseId = document.getElementById('input_house_id').value;
    const selOccupant = document.getElementById('input_occupant_id');
    
    // Reset dropdown penghuni
    selOccupant.innerHTML = '<option value="">-- Pilih Penghuni --</option>';

    if (!houseId) {
        selOccupant.innerHTML = '<option value="">-- Pilih Rumah Terlebih Dahulu --</option>';
        return;
    }

    // Filter history untuk rumah ini yang ladi "AKTIF"
    const activeHistories = histories.filter(h => h.house_id == houseId && h.status_aktif == 1);
    
    // Ambil data detail occupant dari master occupants
    const filteredOccupants = occupants.filter(occ => {
        return activeHistories.some(hist => hist.occupant_id == occ.id);
    });

    if (filteredOccupants.length === 0) {
        selOccupant.innerHTML = '<option value="">- Tidak ada penghuni aktif -</option>';
    } else {
        filteredOccupants.forEach(o => {
            const opt = document.createElement('option');
            opt.value = o.id;
            opt.textContent = o.nama_lengkap;
            selOccupant.appendChild(opt);
        });
    }
}

// Auto-isi jumlah & Update Invoice
function onCategoryChange() {
    updateInvoiceSummary();
}

function updateInvoiceSummary() {
    const catId = document.getElementById('input_category_id').value;
    const durasi = document.getElementById('input_durasi').value;
    const invSection = document.getElementById('invoiceSummary');
    
    if (!catId) {
        invSection.classList.add('hidden');
        return;
    }

    const catData = categories.find(c => c.id == catId);
    if (catData) {
        invSection.classList.remove('hidden');
        
        const price = parseFloat(catData.jumlah);
        const total = price * parseInt(durasi);

        document.getElementById('inv-category').innerText = catData.jenis_iuran;
        document.getElementById('inv-price').innerText = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('inv-duration').innerText = durasi + ' Bulan';
        document.getElementById('inv-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
    } else {
        invSection.classList.add('hidden');
    }
}

// ============================
// FILTER TABEL
// ============================
function filterTable() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const status  = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#expense-table-body tr[data-status]');
    rows.forEach(row => {
        const matchKeyword = !keyword
            || row.dataset.occupant.includes(keyword)
            || row.dataset.house.includes(keyword);
        const matchStatus = !status || row.dataset.status === status;
        row.style.display = (matchKeyword && matchStatus) ? '' : 'none';
    });
}

// ============================
// MODAL TAMBAH
// ============================
function openModal() {
    document.getElementById('input_occupant_id').value   = '';
    document.getElementById('input_house_id').value      = '';
    document.getElementById('input_category_id').value   = '';
    document.getElementById('input_durasi').value        = '1';
    document.getElementById('invoiceSummary').classList.add('hidden');
    
    // Reset View
    document.querySelector('.space-y-4').classList.remove('hidden');
    document.getElementById('paymentStep2').classList.add('hidden');

    const alert = document.getElementById('formAlert');
    alert.classList.add('hidden');
    alert.textContent = '';
    document.getElementById('expenseModal').classList.remove('hidden');
}

function proceedToPayment() {
    const occupant_id = document.getElementById('input_occupant_id').value;
    const house_id    = document.getElementById('input_house_id').value;
    const category_id = document.getElementById('input_category_id').value;
    const alertEl     = document.getElementById('formAlert');

    if (!occupant_id || !house_id || !category_id) {
        alertEl.textContent = '❗ Harap lengkapi semua data sebelum lanjut!';
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        alertEl.classList.remove('hidden');
        return;
    }

    // Switch View
    document.querySelector('.space-y-4').classList.add('hidden');
    const step2 = document.getElementById('paymentStep2');
    step2.classList.remove('hidden');

    // Fill confirmation data
    const occ = occupants.find(o => o.id == occupant_id);
    const house = houses.find(h => h.id == house_id);
    const cat = categories.find(c => c.id == category_id);
    const durasi = document.getElementById('input_durasi').value;
    const total = parseFloat(cat.jumlah) * parseInt(durasi);

    document.getElementById('conf-name').innerText = occ ? occ.nama_lengkap : '-';
    document.getElementById('conf-house').innerText = house ? 'Rumah ' + house.nomor_rumah : '-';
    document.getElementById('conf-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

function cancelPayment() {
    document.querySelector('.space-y-4').classList.remove('hidden');
    document.getElementById('paymentStep2').classList.add('hidden');
}

function closeModal() {
    document.getElementById('expenseModal').classList.add('hidden');
}

function submitTambahPembayaran() {
    const occupant_id        = document.getElementById('input_occupant_id').value;
    const house_id           = document.getElementById('input_house_id').value;
    const category_id        = document.getElementById('input_category_id').value;
    const durasi             = document.getElementById('input_durasi').value;
    const alertEl            = document.getElementById('formAlert');
    const btn                = document.getElementById('btnSimpan');

    // Ambil jumlah otomatis dari data categories (dikali durasi)
    const catData = categories.find(c => c.id == category_id);
    const jumlah  = catData ? (parseFloat(catData.jumlah) * parseInt(durasi)) : 0;
    const status  = 'BELUM_BAYAR'; 

    if (!occupant_id || !house_id || !category_id || !durasi) {
        alertEl.textContent = '❗ Semua field wajib diisi!';
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    fetch(`${API}/expense`, {
        method : 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body   : JSON.stringify({ house_id, occupant_id, category_id, durasi, jumlah, status })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Gagal menyimpan.');
            throw new Error(errors);
        }
        return data;
    })
    .then(data => {
        alertEl.textContent = '✅ Tagihan dibuat! Membuka halaman pembayaran DOKU...';
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-green-100 text-green-700 text-center';
        alertEl.classList.remove('hidden');
        
        // Buka URL Pembayaran DOKU di tab baru
        if (data.payment_url) {
            setTimeout(() => {
                window.open(data.payment_url, '_blank');
                closeModal(); 
                loadAll(); 
            }, 1000);
        } else {
            setTimeout(() => { closeModal(); loadAll(); }, 1500);
        }
    })
    .catch(err => {
        alertEl.textContent = '❌ ' + err.message;
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Simpan';
    });
}

// ============================
// MODAL UPDATE STATUS
// ============================
function openUpdateModal(id, status, tgl) {
    document.getElementById('update_expense_id').value = id;
    document.getElementById('update_status').value     = status;
    document.getElementById('update_tanggal').value    = tgl !== '-' ? tgl : '';
    const alertEl = document.getElementById('updateAlert');
    alertEl.classList.add('hidden');
    document.getElementById('updateModal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

function submitUpdateStatus() {
    const id     = document.getElementById('update_expense_id').value;
    const status = document.getElementById('update_status').value;
    const tgl    = document.getElementById('update_tanggal').value;
    const alertEl = document.getElementById('updateAlert');
    const btn    = document.getElementById('btnUpdate');

    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    fetch(`${API}/expense/${id}`, {
        method : 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body   : JSON.stringify({ status, tanggal_pembayaran: tgl || null })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Gagal update');
        return data;
    })
    .then(() => {
        alertEl.textContent = '✅ Status berhasil diperbarui!';
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-green-100 text-green-700';
        setTimeout(() => { closeUpdateModal(); loadAll(); }, 1000);
    })
    .catch(err => {
        alertEl.textContent = '❌ ' + err.message;
        alertEl.className   = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Simpan';
    });
}
</script>
@endsection