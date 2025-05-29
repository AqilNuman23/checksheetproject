<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @push('styles')
        @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    @section('content')
        <div class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-4">Checksheet Status</h2>
                <canvas id="checksheetChart" height="100" style="max-height: 400px; display: block;"></canvas>
            </div>
        </div>
    @endsection
    
    @push('scripts')
        <script>
            // Use window.onload to ensure DOM is fully rendered
            window.onload = function () {
                const canvas = document.getElementById('checksheetChart');
                if (!canvas) {
                    console.error('Canvas element with id "checksheetChart" still not found after window.onload.');
                    return;
                }

                const ctx = canvas.getContext('2d');
                const labels = @json($statusLabels);
                const data = @json($statusData);

                if (labels.length === 0 || data.length === 0) {
                    console.warn('No data available for the chart.');
                    return;
                }

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Checksheet Status',
                            data: data,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(42, 196, 80, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(42, 196, 80, 1)',
                                'rgba(255, 206, 86, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Checksheet Status Distribution' }
                        }
                    }
                });
            };
        </script>
    @endpush
</x-app-layout>