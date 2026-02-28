@extends('layout.app')

@section('title', 'Registrasi Keluarga')

@section('content')
    <h2 class="text-3xl font-bold flex justify-start">Registrasi Keluarga</h2>
@endsection

@section('content2')
<div class="space-y-6 relative z-10">

    <!-- HEADER & ACTION -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mt-1">Kelola data warga dan anggota keluarga</p>
        </div>
        <button onclick="openModal()"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow">
            + Tambah Anggota
        </button>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="bg-white rounded-xl shadow p-4 flex flex-wrap gap-3 items-center">
        <input type="text" id="searchInput" placeholder="🔍 Cari nama atau nomor telepon..."
               oninput="filterTable()"
               class="flex-1 min-w-[200px] border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
        <select id="filterStatus" onchange="filterTable()"
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
            <option value="">Semua Status</option>
            <option value="TETAP">Tetap</option>
            <option value="KONTRAK">Kontrak</option>
        </select>
    </div>

    <!-- TABLE AREA -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Rumah</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Nikah</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="occupant-table-body" class="divide-y divide-gray-100 text-gray-700">
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                             <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl animate-pulse">⏳</span>
                                <span>Memuat data keluarga...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL FORM -->
    <div id="occupantModal" class="fixed inset-0 bg-black/40 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 relative shadow-2xl animate-scaleUp">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">✕</button>
            <h3 class="text-xl font-bold mb-4">Tambah Anggota Keluarga</h3>
            
            <div id="formAlert" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>

            <form id="occupantForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                    
                    <!-- Rumah -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Pilih Rumah</label>
                        <select name="house_id" id="house_select"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                            <option value="">-- Pilih Rumah --</option>
                        </select>
                    </div>

                    <!-- User (Static for now or map to user id) -->
                    <div>
                        <label class="block text-sm font-medium mb-1">User ID <span class="text-red-500">*</span></label>
                        <input type="number" name="user_id" value="1" readonly
                               class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed">
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="nomor_telepon" placeholder="08xxxx" required
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Status Menikah -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Status Nikah <span class="text-red-500">*</span></label>
                        <select name="status_menikah" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="SUDAH">Sudah Menikah</option>
                            <option value="BELUM">Belum Menikah</option>
                        </select>
                    </div>

                    <!-- Status Penghuni -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Status Penghuni <span class="text-red-500">*</span></label>
                        <select name="status_penghuni" required
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="TETAP">Warga Tetap</option>
                            <option value="KONTRAK">Warga Kontrak</option>
                        </select>
                    </div>

                    <!-- Foto KTP -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Foto KTP</label>
                        <input type="file" name="foto_ktp" accept="image/*"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">Batal</button>
                    <button type="submit" id="btnSimpan"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const API = 'http://127.0.0.1:8000/api';

// State
let allOccupants = [];

document.addEventListener('DOMContentLoaded', () => {
    loadData();
    loadHouses();
});

function loadData() {
    fetch(`${API}/occupant`)
        .then(res => res.json())
        .then(data => {
            allOccupants = data;
            renderTable(data);
        })
        .catch(err => {
            console.error(err);
            document.getElementById('occupant-table-body').innerHTML = `
                <tr><td colspan="7" class="text-center py-10 text-red-500">❌ Gagal memuat data anggota.</td></tr>
            `;
        });
}

function loadHouses() {
    fetch(`${API}/house`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('house_select');
            data.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h.id;
                opt.textContent = `Rumah ${h.nomor_rumah} (${h.status_rumah})`;
                select.appendChild(opt);
            });
        });
}

function renderTable(data) {
    const tbody = document.getElementById('occupant-table-body');
    
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-16 text-gray-400">
                    <div class="flex flex-col items-center gap-3">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" class="w-24 opacity-30">
                        <p class="text-lg font-medium">Belum ada anggota keluarga terdaftar</p>
                        <button onclick="openModal()" class="text-blue-500 hover:underline">Tambah anggota sekarang</button>
                    </div>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = data.map((o, i) => {
        const houseLabel = o.house ? `🏠 Rumah ${o.house.nomor_rumah}` : '<span class="text-gray-300 italic">Belum setting</span>';
        const statusClass = o.status_penghuni === 'TETAP' ? 'bg-indigo-100 text-indigo-700' : 'bg-orange-100 text-orange-700';
        const nikahClass = o.status_menikah === 'SUDAH' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600';

        return `
            <tr class="hover:bg-blue-50/30 transition-colors" data-name="${o.nama_lengkap.toLowerCase()}" data-status="${o.status_penghuni}">
                <td class="px-6 py-4 text-gray-400 font-mono text-xs">${i + 1}</td>
                <td class="px-6 py-4 font-semibold text-gray-800">${o.nama_lengkap}</td>
                <td class="px-6 py-4 text-gray-600 text-sm">${houseLabel}</td>
                <td class="px-6 py-4 text-gray-500 font-mono text-xs">${o.nomor_telepon}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full ${statusClass}">${o.status_penghuni}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full ${nikahClass}">${o.status_menikah === 'SUDAH' ? '💍 SUDAH' : 'BELUM'}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button onclick="deleteOccupant(${o.id})" class="text-red-400 hover:text-red-600 transition p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function filterTable() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#occupant-table-body tr[data-name]');
    
    rows.forEach(row => {
        const matchName = row.dataset.name.includes(keyword);
        const matchStatus = !status || row.dataset.status === status;
        row.style.display = (matchName && matchStatus) ? '' : 'none';
    });
}

function openModal() {
    document.getElementById('occupantForm').reset();
    document.getElementById('formAlert').classList.add('hidden');
    document.getElementById('occupantModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('occupantModal').classList.add('hidden');
}

document.getElementById('occupantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const alertEl = document.getElementById('formAlert');
    const btn = document.getElementById('btnSimpan');
    const formData = new FormData(this);

    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    fetch(`${API}/occupant`, {
        method: "POST",
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json();
        if(!res.ok) throw new Error(data.message || 'Gagal menyimpan data');
        return data;
    })
    .then(() => {
        alertEl.textContent = '✅ Berhasil menambahkan anggota keluarga!';
        alertEl.className = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-green-100 text-green-700';
        alertEl.classList.remove('hidden');
        
        setTimeout(() => {
            closeModal();
            loadData();
        }, 1500);
    })
    .catch(err => {
        alertEl.textContent = '❌ ' + err.message;
        alertEl.className = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        alertEl.classList.remove('hidden');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Simpan Data';
    });
});

function deleteOccupant(id) {
    if(!confirm('Apakah Anda yakin ingin menghapus data anggota ini?')) return;

    fetch(`${API}/occupant/${id}`, { method: 'DELETE' })
        .then(() => {
            loadData();
        })
        .catch(err => alert('Gagal menghapus: ' + err.message));
}
</script>

<style>
@keyframes scaleUp {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-scaleUp { animation: scaleUp 0.15s ease-out; }
</style>
@endsection