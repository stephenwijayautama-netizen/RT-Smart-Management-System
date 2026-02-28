<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOKU Checkout - Simulation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f7fa] flex items-center justify-center min-h-screen p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl shadow-blue-100 overflow-hidden border border-gray-100">
        <!-- Header DOKU -->
        <div class="bg-[#003066] p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black">DOKU</h1>
                <p class="text-[10px] opacity-70 font-bold uppercase tracking-widest">Payment Checkout</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] opacity-70 uppercase font-black">Total Tagihan</p>
                <div id="display-total" class="text-xl font-black">Rp -</div>
            </div>
        </div>

        <div class="p-8 space-y-6">
            <!-- QRIS SECTION -->
            <div class="text-center space-y-4">
                <div class="inline-block p-4 bg-white border-2 border-gray-50 rounded-3xl shadow-sm">
                    <img id="qris-img" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=DOKU-SIMULATION" alt="QRIS" class="w-48 h-48 mx-auto">
                </div>
                <div>
                    <h2 class="text-lg font-black text-gray-800">Scan untuk Membayar</h2>
                    <p class="text-xs text-gray-400 font-medium">Gunakan aplikasi m-Banking atau E-Wallet apa saja</p>
                </div>
            </div>

            <!-- DETAIL -->
            <div class="bg-gray-50 rounded-2xl p-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Invoice ID</span>
                    <span id="display-id" class="font-bold text-gray-800">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Merchant</span>
                    <span class="font-bold text-gray-800 uppercase tracking-tighter">Pak RT Digital System</span>
                </div>
            </div>

            <!-- BUTTON SIMULATION -->
            <div class="space-y-3">
                <button onclick="simulateSuccess()" id="btn-pay" class="w-full py-4 bg-emerald-500 text-white font-black rounded-2xl shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition tracking-wide">
                    SIMULASI BAYAR SEKARANG
                </button>
                <p class="text-[10px] text-center text-gray-400 font-bold uppercase">Halaman ini adalah simulasi integrasi DOKU</p>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const expenseId = urlParams.get('id');
        const amount = urlParams.get('amount');
        const inviteId = urlParams.get('inv');

        document.getElementById('display-total').innerText = 'Rp ' + parseInt(amount).toLocaleString('id-ID');
        document.getElementById('display-id').innerText = inviteId;
        document.getElementById('qris-img').src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + inviteId;

        async function simulateSuccess() {
            const btn = document.getElementById('btn-pay');
            btn.disabled = true;
            btn.innerText = 'MEMPROSES...';
            btn.className = 'w-full py-4 bg-gray-400 text-white font-black rounded-2xl cursor-not-allowed';

            try {
                // Panggil API backend untuk update status jadi SUDAH_BAYAR
                const res = await fetch(`http://127.0.0.1:8000/api/expense/${expenseId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        status: 'SUDAH_BAYAR',
                        tanggal_pembayaran: new Date().toISOString().split('T')[0],
                        payment_status: 'PAID'
                    })
                });

                if(res.ok) {
                    alert('✅ Pembayaran Berhasil! Mengalihkan kembali ke aplikasi...');
                    window.close(); // Tutup tab ini
                } else {
                    alert('Gagal mengupdate status pembayaran.');
                    btn.disabled = false;
                    btn.innerText = 'SIMULASI BAYAR SEKARANG';
                }
            } catch (e) {
                alert('Koneksi ke server gagal.');
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
