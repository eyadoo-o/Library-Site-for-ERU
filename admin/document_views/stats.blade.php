<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Document Statistics') }}: {{ $document->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-blue-700 dark:text-blue-300 mb-2">Total Views</h3>
                        <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">{{ $viewsCount }}</p>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-purple-700 dark:text-purple-300 mb-2">Unique Viewers</h3>
                        <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">{{ $uniqueViewers }}</p>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900 rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-green-700 dark:text-green-300 mb-2">Created</h3>
                        <p class="text-xl font-bold text-green-800 dark:text-green-200">{{ $document->created_at->format('M d, Y') }}</p>
                        <p class="text-sm text-green-600 dark:text-green-400">{{ $document->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Views Over Time</h3>

                    @if(count($viewsByDate) > 0)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div id="chart" style="height: 300px;"></div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No view data available for charting.
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.documents.show', $document) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">
                        &larr; Back to Document
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(count($viewsByDate) > 0)
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewData = @json($viewsByDate);

            const dates = viewData.map(item => item.date);
            const counts = viewData.map(item => item.count);

            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Views',
                    data: counts
                }],
                xaxis: {
                    categories: dates,
                    labels: {
                        style: {
                            colors: getComputedStyle(document.documentElement).getPropertyValue('--text-gray-500').trim() || '#718096'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: getComputedStyle(document.documentElement).getPropertyValue('--text-gray-500').trim() || '#718096'
                        }
                    }
                },
                colors: [getComputedStyle(document.documentElement).getPropertyValue('--text-blue-600').trim() || '#3182ce'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.2,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
    @endif
</x-app-layout>
