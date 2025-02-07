<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        {{ __("You're logged in!")  }}<br>
    </div>

    <!-- Chart Container -->
    <div class="p-6 mt-6 bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <canvas id="myChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "{{ url('/laporan/laba-rugi') }}",
                method: "GET",
                success: function (data) {
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Total Penjualan', 'Total Pembelian', 'Laba Kotor', 'Modal Awal', 'Modal Akhir'],
                            datasets: [{
                                label: 'Laporan Laba Rugi',
                                data: [
                                    data.total_penjualan, 
                                    data.total_pembelian, 
                                    data.laba_kotor, 
                                    data.modal_awal, 
                                    data.modal_akhir
                                ],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.2)', // Biru (Penjualan)
                                    'rgba(255, 99, 132, 0.2)', // Merah (Pembelian)
                                    'rgba(255, 206, 86, 0.2)', // Kuning (Laba Kotor)
                                    'rgba(75, 192, 192, 0.2)', // Hijau (Modal Awal)
                                    'rgba(153, 102, 255, 0.2)' // Ungu (Modal Akhir)
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Terjadi kesalahan:", error);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
