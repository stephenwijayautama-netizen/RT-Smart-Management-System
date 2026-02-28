@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-3xl font-bold">House</h2>
@endsection

@section('content2')
<div class="space-y-6 relative z-10">

    <!-- CARD UTAMA -->
    <div class="rounded-xl flex flex-col items-center justify-center text-center px-6">
        <div id="house-wrapper" class="flex items-center justify-center flex-col">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Empty Data"
                 class="w-32 mb-6 opacity-70">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                Saat ini belum ada data rumah yang tersedia.
            </h3>
            <p class="text-sm text-gray-500 max-w-md mb-6">
                Silakan tambahkan rumah baru untuk mulai mengelola data keluarga.
            </p>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal" class="fixed inset-0 bg-black/40 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 relative">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">✕</button>
            <h3 class="text-2xl font-bold mb-6">Detail Rumah</h3>
            <div id="detailContent" class="space-y-4"></div>
            <div class="flex justify-end gap-3 pt-6">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 rounded-lg border">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH RUMAH -->
    <div id="anggotaModal" class="fixed inset-0 bg-black/40 z-[9999] hidden flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">✕</button>
            <h3 class="text-xl font-bold mb-4">Tambah Data Rumah</h3>

            <!-- Alert pesan sukses/error -->
            <div id="formAlert" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">ID Rumah <span class="text-red-500">*</span></label>
                    <input type="text" id="input_house_id" placeholder="Contoh: RT001"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nomor Rumah <span class="text-red-500">*</span></label>
                    <input type="text" id="input_nomor_rumah" placeholder="Contoh: A-01"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status Rumah <span class="text-red-500">*</span></label>
                    <select id="input_status_rumah"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white">
                        <option value="TIDAK_DIHUNI">Tidak Dihuni</option>
                        <option value="DIHUNI">Dihuni</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan Rumah</label>
                    <textarea id="input_keterangan" placeholder="Contoh: Belakang taman, Gerbang hitam, dll"
                              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none h-20 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Batal</button>
                    <button type="button" id="btnSimpanRumah" onclick="submitTambahRumah()"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.getElementById("house-wrapper");

    Promise.all([
        fetch("http://127.0.0.1:8000/api/house").then(r => r.json()),
        fetch("http://127.0.0.1:8000/api/occupant").then(r => r.json()),
        fetch("http://127.0.0.1:8000/api/houseoccupanthistory").then(r => r.json()),
        fetch("http://127.0.0.1:8000/api/expense").then(r => r.json()),
        fetch("http://127.0.0.1:8000/api/expense-category").then(r => r.json())
    ]).then(([houseData, occupantData, historyData, expenseData, categoryData]) => {

        wrapper.innerHTML = "";

        if (!houseData || houseData.length === 0) {
            wrapper.innerHTML = `
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Empty Data" class="w-32 mb-6 opacity-70">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Saat ini belum ada data rumah yang tersedia.</h3>
                <p class="text-sm text-gray-500 max-w-md mb-6">Silakan tambahkan rumah baru untuk mulai mengelola data keluarga.</p>
                <button onclick="openModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">+ Tambah Rumah</button>
            `;
            return;
        }

        const container = document.createElement('div');
        container.className = "flex flex-wrap gap-6";
        wrapper.appendChild(container);

        houseData.forEach(house => {

            // Ambil SEMUA history untuk rumah ini (Aktif & Masa Lalu)
            const allHistories = historyData.filter(h => h.house_id === house.id);
            
            // Filter hanya yang Aktif untuk info di Kartu
            const activeHistories = allHistories.filter(h => h.status_aktif);

            // Ambil nama-nama penghuni aktif saja untuk kartu
            const occupantsInHouse = activeHistories.map(h => {
                const occupant = occupantData.find(o => o.id === h.occupant_id);
                return occupant ? occupant.nama_lengkap : "(Unknown)";
            });
            const occupantNames = occupantsInHouse.length > 0 ? occupantsInHouse.join(", ") : "Belum Ada";

            // Siapkan data history lengkap (Aktif + Masa Lalu) untuk modal
            const historyDetail = allHistories.map(h => {
                const occupant = occupantData.find(o => o.id === h.occupant_id);
                return {
                    nama:            occupant ? occupant.nama_lengkap    : "(Unknown)",
                    telepon:         occupant ? occupant.nomor_telepon   : "-",
                    status_penghuni: occupant ? occupant.status_penghuni : "-",
                    status_aktif:    h.status_aktif,
                    tanggal_masuk:   h.tanggal_masuk  ?? "-",
                    tanggal_keluar:  h.tanggal_keluar ?? "-",
                };
            }).reverse(); // Yang terbaru di atas

            // Filter pembayaran (expense) untuk rumah ini
            const houseExpenses = expenseData.filter(e => e.house_id === house.id);
            const unpaidExpenses = houseExpenses.filter(e => e.status === 'BELUM_BAYAR');
            const hasUnpaid = unpaidExpenses.length > 0;

            const paymentStatusHTML = hasUnpaid 
                ? `<span class="flex items-center gap-1.5 text-red-600 font-bold text-[11px] bg-red-50 px-2 py-0.5 rounded-full w-fit">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                    ADA TUNGGAKAN (${unpaidExpenses.length})
                   </span>`
                : `<span class="text-green-600 font-bold text-[11px] bg-green-50 px-2 py-0.5 rounded-full w-fit">✓ SEMUA LUNAS</span>`;

            // Siapkan payment history untuk detail
            const paymentHistory = houseExpenses.map(e => {
                const cat = categoryData.find(c => c.id === e.category_id);
                return {
                    kategori: cat ? cat.jenis_iuran : 'Iuran',
                    jumlah: e.jumlah,
                    status: e.status,
                    tanggal: e.tanggal_pembayaran || '-',
                    durasi: e.durasi || 1
                };
            }).reverse(); // terbaru di atas

            const card = document.createElement('div');
            card.className = "w-72 bg-white border border-gray-100 rounded-2xl shadow-lg p-6 hover:shadow-xl hover:-translate-y-1 transition duration-300";
            card.innerHTML = `
                <div class="flex justify-between items-center mb-4 gap-2">
                    <h3 class="text-lg font-bold text-gray-800">🏠 Rumah ${house.nomor_rumah}</h3>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full ${house.status_rumah === 'DIHUNI' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">
                        ${house.status_rumah}
                    </span>
                </div>
                <div class="text-sm text-gray-500 space-y-1">
                    <p><span class="font-medium text-gray-700">Nomor Rumah:</span> ${house.nomor_rumah}</p>
                    <p><span class="font-medium text-gray-700">Penghuni Aktif:</span> ${occupantNames}</p>
                    <div class="pt-2">
                        ${paymentStatusHTML}
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t flex justify-between items-center">
                    <button onclick="deleteHouse('${house.id}')" class="text-red-500 hover:text-red-700 transition" title="Hapus Rumah">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <button onclick="openDetailModal('${house.id}')" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Detail →</button>
                </div>
            `;

            // Simpan data ke dataset card
            card.dataset.nomor      = house.nomor_rumah;
            card.dataset.status     = house.status_rumah;
            card.dataset.keterangan = house.keterangan || "-";
            card.dataset.occupants  = occupantNames;
            card.dataset.history  = JSON.stringify(historyDetail); 
            card.dataset.payments = JSON.stringify(paymentHistory); 

            container.appendChild(card);
        });

        // Tombol tambah rumah
        const addButtonWrapper = document.createElement('div');
        addButtonWrapper.className = "w-full mt-8 flex justify-center";
        addButtonWrapper.innerHTML = `<button onclick="openModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">+ Tambah Rumah</button>`;
        wrapper.appendChild(addButtonWrapper);

    }).catch(err => console.error("Gagal memuat data:", err));
});

// Modal detail rumah
function openDetailModal(houseId) {
    const button = document.querySelector(`[onclick="openDetailModal('${houseId}')"]`);
    const card   = button.closest('div.w-72');

    const history = JSON.parse(card.dataset.history || "[]");
    const payments = JSON.parse(card.dataset.payments || "[]");

    // Histori Penghuni (Lengkap)
    let historyHTML = "<p class='text-xs text-gray-400'>Belum ada catatan penghuni.</p>";
    if (history.length > 0) {
        historyHTML = history.map(h => `
            <div class="border rounded-xl p-3 text-[13px] space-y-2 bg-gray-50/50 relative">
                <div class="flex justify-between items-start">
                    <p class="font-bold text-gray-800">${h.nama}</p>
                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full ${h.status_aktif ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-500'}">
                        ${h.status_aktif ? 'AKTIF' : 'SUDAH KELUAR'}
                    </span>
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-500">
                    <span>📞 ${h.telepon}</span>
                    <span>🏠 ${h.status_penghuni}</span>
                </div>
                <div class="pt-1 border-t border-gray-100 flex gap-4 text-[10px] text-gray-400">
                    <span>In: ${h.tanggal_masuk}</span>
                    ${!h.status_aktif ? `<span>Out: ${h.tanggal_keluar}</span>` : ''}
                </div>
            </div>
        `).join("");
    }

    // Histori Pembayaran
    let paymentHTML = "<p class='text-xs text-gray-400'>Belum ada catatan pembayaran.</p>";
    if (payments.length > 0) {
        paymentHTML = payments.map(p => `
            <div class="flex items-center justify-between p-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                <div class="space-y-0.5">
                    <p class="text-sm font-bold text-gray-800">${p.kategori}</p>
                    <p class="text-[11px] text-gray-400">${p.durasi} Bulan • ${p.tanggal}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-blue-600">Rp ${parseFloat(p.jumlah).toLocaleString('id-ID')}</p>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md ${p.status === 'SUDAH_BAYAR' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">
                        ${p.status === 'SUDAH_BAYAR' ? 'LUNAS' : 'BELUM BAYAR'}
                    </span>
                </div>
            </div>
        `).join("");
    }

    const detailContent = document.getElementById('detailContent');
    detailContent.innerHTML = `
        <div class="max-h-[60vh] overflow-y-auto pr-2 space-y-6">
            <!-- Header Ringkas -->
            <div class="flex justify-between items-start">
                <div>
                   <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Informasi Rumah</h4>
                   <p class="text-xl font-black text-gray-800">Rumah ${card.dataset.nomor}</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-lg ${card.dataset.status === 'DIHUNI' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">
                    ${card.dataset.status}
                </span>
            </div>

            <!-- Bagian Keterangan -->
            <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Keterangan / Catatan</h4>
                <p class="text-sm text-gray-600 leading-relaxed">${card.dataset.keterangan}</p>
            </div>

            <!-- Bagian Penghuni -->
            <div>
                <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Histori Penghuni (Aktif & Terpindah)</h4>
                <div class="space-y-2">${historyHTML}</div>
            </div>

            <!-- Bagian Pembayaran -->
            <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-4 py-2 border-b">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Histori Pembayaran</h4>
                </div>
                <div class="divide-y divide-gray-50">${paymentHTML}</div>
            </div>
        </div>
    `;

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function deleteHouse(houseId) {
    if (!confirm('Apakah kamu yakin ingin menghapus data rumah ini? Semua history yang terkait juga mungkin akan terhapus.')) {
        return;
    }

    fetch(`http://127.0.0.1:8000/api/house/${houseId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Gagal menghapus rumah.');
        alert('✅ Rumah berhasil dihapus!');
        location.reload();
    })
    .catch(err => {
        alert('❌ Error: ' + err.message);
    });
}

function openModal() {
    // Reset form setiap kali modal dibuka
    document.getElementById('input_house_id').value = '';
    document.getElementById('input_nomor_rumah').value = '';
    document.getElementById('input_status_rumah').value = 'TIDAK_DIHUNI';
    document.getElementById('input_keterangan').value = '';
    const alert = document.getElementById('formAlert');
    alert.classList.add('hidden');
    alert.textContent = '';
    document.getElementById('anggotaModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('anggotaModal').classList.add('hidden');
}

function submitTambahRumah() {
    const houseId    = document.getElementById('input_house_id').value.trim();
    const nomorRumah = document.getElementById('input_nomor_rumah').value.trim();
    const statusRumah = document.getElementById('input_status_rumah').value;
    const keterangan  = document.getElementById('input_keterangan').value.trim();
    const alert      = document.getElementById('formAlert');
    const btn        = document.getElementById('btnSimpanRumah');

    // Validasi sederhana di sisi client
    if (!houseId || !nomorRumah || !statusRumah) {
        alert.textContent = '❗ Semua field wajib diisi!';
        alert.className = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
        return;
    }

    // Loading state
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    fetch('http://127.0.0.1:8000/api/house', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            house_id:     houseId,
            nomor_rumah:  nomorRumah,
            status_rumah: statusRumah,
            keterangan:   keterangan,
        })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            // Tampilkan pesan error dari Laravel
            const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Gagal menyimpan data.');
            throw new Error(errors);
        }
        return data;
    })
    .then(() => {
        alert.textContent = '✅ Rumah berhasil ditambahkan!';
        alert.className = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-green-100 text-green-700';

        // Tutup modal & reload kartu setelah 1.2 detik
        setTimeout(() => {
            closeModal();
            location.reload();
        }, 1200);
    })
    .catch(err => {
        alert.textContent = '❌ ' + err.message;
        alert.className = 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-100 text-red-700';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Simpan';
    });
}
</script>
@endsection