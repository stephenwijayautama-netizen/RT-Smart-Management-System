<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex">

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-64 bg-white shadow-xl rounded-r-3xl p-6">

            <div class="mb-10">
                <h1 class="text-2xl font-bold text-blue-600">
                    MyApp
                </h1>
            </div>

            <nav class="space-y-3">

                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 p-3 rounded-xl bg-blue-100 text-blue-600 font-medium rounded-xl">
                    Dashboard
                </a>
                <a href="{{ route('house') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition">
                    House
                </a>

                <a href="{{ route('registrasi') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition">
                    Registrasi Keluarga
                </a>
                <a href="{{ route('pembayaran') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition">
                    Pembayaran
                </a>

            </nav>

        </aside>

        <main class="flex-1 overflow-x-hidden">
            <div class="ml-64 p-10 flex flex-row gap-5 items-start">
                <div class="bg-white p-8 rounded-2xl shadow-sm w-[750px] overflow-y-auto max-h-[85vh]">
                    @yield('content')
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm w-[350px] h-[320px] overflow-y-auto">

                    <div class="flex justify-between items-center mb-4">
                        <button onclick="prevMonth()" class="text-gray-600 hover:text-black">
                            ←
                        </button>
                        <h2 id="monthYear" class="font-bold text-lg"></h2>
                        <button onclick="nextMonth()" class="text-gray-600 hover:text-black">
                            →
                        </button>
                    </div>

                    <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-500 mb-2">
                        <div>Su</div>
                        <div>Mo</div>
                        <div>Tu</div>
                        <div>We</div>
                        <div>Th</div>
                        <div>Fr</div>
                        <div>Sa</div>
                    </div>

                    <div id="calendar" class="grid grid-cols-7 gap-1 text-center text-sm"></div>

                </div>

                <script>
                    let currentDate = new Date();
                    let calendarEvents = [];

                    async function fetchEvents() {
                        const API = "http://127.0.0.1:8000/api";
                        try {
                            const [expenseRes, newsRes] = await Promise.all([
                                fetch(`${API}/expense-category`).then(r => r.json()),
                                fetch(`${API}/news`).then(r => r.json())
                            ]);

                            calendarEvents = [
                                ...expenseRes.map(e => ({ date: e.tanggal_pembayaran, type: 'payment', label: e.jenis_iuran })),
                                ...newsRes.map(n => ({ 
                                    date: n.tanggal || n.created_at.split('T')[0], 
                                    type: 'news', 
                                    label: n.caption 
                                }))
                            ];
                            
                            renderCalendar();
                        } catch (err) {
                            console.error("Gagal load calendar events:", err);
                        }
                    }

                    function renderCalendar() {
                        const calendar = document.getElementById("calendar");
                        const monthYear = document.getElementById("monthYear");

                        calendar.innerHTML = "";

                        const year = currentDate.getFullYear();
                        const month = currentDate.getMonth();

                        const firstDay = new Date(year, month, 1).getDay();
                        const daysInMonth = new Date(year, month + 1, 0).getDate();

                        monthYear.innerText = currentDate.toLocaleString('default', {
                            month: 'long',
                            year: 'numeric'
                        });

                        for (let i = 0; i < firstDay; i++) {
                            const emptyDiv = document.createElement("div");
                            calendar.appendChild(emptyDiv);
                        }

                        for (let day = 1; day <= daysInMonth; day++) {
                            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            const dayEvents = calendarEvents.filter(e => e.date === dateStr);

                            const today = new Date();
                            const isToday =
                                day === today.getDate() &&
                                month === today.getMonth() &&
                                year === today.getFullYear();

                            const dayDiv = document.createElement("div");
                            dayDiv.className = `p-1 h-10 flex flex-col items-center justify-center rounded-lg relative cursor-pointer ${isToday ? 'bg-blue-600 text-white font-bold' : 'hover:bg-gray-100 text-gray-700'}`;
                            
                            let indicators = "";
                            if (dayEvents.length > 0) {
                                const hasPayment = dayEvents.some(e => e.type === 'payment');
                                const hasNews = dayEvents.some(e => e.type === 'news');
                                
                                indicators = `<div class="flex gap-1 mt-0.5">
                                    ${hasPayment ? '<span class="w-1.5 h-1.5 bg-red-400 rounded-full shadow-sm"></span>' : ''}
                                    ${hasNews ? '<span class="w-1.5 h-1.5 bg-blue-300 rounded-full shadow-sm"></span>' : ''}
                                </div>`;
                                
                                // Simple tooltip
                                dayDiv.title = dayEvents.map(e => `[${e.type.toUpperCase()}] ${e.label}`).join('\n');
                            }

                            dayDiv.innerHTML = `<span>${day}</span>${indicators}`;
                            calendar.appendChild(dayDiv);
                        }
                    }

                    function prevMonth() {
                        currentDate.setMonth(currentDate.getMonth() - 1);
                        renderCalendar();
                    }

                    function nextMonth() {
                        currentDate.setMonth(currentDate.getMonth() + 1);
                        renderCalendar();
                    }

                    // Initial Load
                    fetchEvents();
                </script>

            </div>
            <div class="flex flex-row items-start gap-5">
                <div class="ml-64 px-10 pt-3 flex flex-row gap-5 mt-[-160px]">
                    <div class="flex flex-col bg-white p-8 rounded-2xl shadow-sm w-[750px] h-[600px] mt-[-50px] overflow-y-auto">
                        @yield('content2')
                    </div>
                   <div class="flex justify-center mt-10">
    <div class="flex gap-6">

        <!-- BOX UTAMA -->
        <div class="bg-white p-8 rounded-2xl shadow-md w-[350px] h-[400px] mt-[110px] pt-5 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-6">News</h2>

            <div id="news" class="space-y-4"></div>
        </div>

    </div>
</div>

<script>
fetch("http://127.0.0.1:8000/api/news") // sesuaikan port!
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById("news");

        data.forEach(item => {
            container.innerHTML += `
                <div class="border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        ${item.caption}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        ${item.description}
                    </p>
                </div>
            `;
        });
    })
    .catch(error => console.error(error));
</script>
            </div>



        </main>

    </div>

</body>

</html>
