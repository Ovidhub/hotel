<x-layouts.admin title="Dashboard">

    {{-- ── Stat cards ── --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Revenue --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Revenue</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#7C0E52]/10">
                    <svg class="h-4 w-4 text-[#7C0E52]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900">
                ₦{{ number_format($totalRevenue) }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                Today: <span class="font-medium text-gray-700">₦{{ number_format($todayRevenue) }}</span>
            </p>
        </div>

        {{-- Occupancy --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Occupancy</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#C79A46]/10">
                    <svg class="h-4 w-4 text-[#C79A46]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900">{{ $occupancyPct }}%</p>
            <p class="mt-1 text-xs text-gray-500">
                Active units checked-in today vs total
            </p>
        </div>

        {{-- Open Bookings --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Open Bookings</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50">
                    <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900">{{ $openBookings }}</p>
            <p class="mt-1 text-xs text-gray-500">
                Pending payment — {{ $totalBookings }} total
            </p>
        </div>

        {{-- Available Units --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Available Units</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900">{{ $availableUnits }}</p>
            <p class="mt-1 text-xs text-gray-500">Active rooms + available apartments</p>
        </div>

    </div>

    {{-- ── Chart + Recent Bookings ── --}}
    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Chart (last 7 days) --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Revenue — Last 7 Days</h2>
                    <p class="mt-0.5 text-xs text-gray-400">Daily commitment fees &amp; booking count</p>
                </div>
                <span class="shrink-0 rounded-full bg-[#7C0E52]/10 px-3 py-1 text-xs font-semibold text-[#7C0E52]">
                    ₦{{ number_format(array_sum($chartRevenue)) }}
                </span>
            </div>

            @if(array_sum($chartRevenue) > 0 || array_sum($chartCounts) > 0)
                {{-- Fixed-height wrapper: keeps Chart.js (maintainAspectRatio:false)
                     from growing the canvas on every resize. --}}
                <div class="relative h-64 w-full sm:h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            @else
                <div class="flex h-64 items-center justify-center rounded-lg bg-gray-50 text-sm text-gray-400">
                    No booking data for the last 7 days.
                </div>
            @endif
        </div>

        {{-- Quick stats sidebar --}}
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
            <h2 class="mb-4 text-sm font-semibold text-gray-700">7-Day Summary</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-gray-500">Total Bookings</dt>
                    <dd class="text-xl font-bold text-gray-900">{{ array_sum($chartCounts) }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Total Revenue</dt>
                    <dd class="text-xl font-bold text-gray-900">₦{{ number_format(array_sum($chartRevenue)) }}</dd>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <dt class="text-xs text-gray-500">Avg. per Booking</dt>
                    <dd class="text-xl font-bold text-gray-900">
                        @php
                            $bookingsCount = array_sum($chartCounts);
                            $revenueSum    = array_sum($chartRevenue);
                        @endphp
                        ₦{{ $bookingsCount > 0 ? number_format($revenueSum / $bookingsCount) : '0' }}
                    </dd>
                </div>
            </dl>
        </div>

    </div>

    {{-- ── Recent Bookings Table ── --}}
    <div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-gray-700">Recent Bookings</h2>
        </div>

        @if($recentBookings->isEmpty())
            <div class="py-12 text-center text-sm text-gray-400">No bookings yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Ref</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Guest</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Property</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Check-in</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentBookings as $booking)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs font-medium text-gray-700">{{ $booking->ref }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->guest_email }}</div>
                            </td>
                            <td class="px-5 py-3.5 hidden md:table-cell text-gray-600">
                                {{ $booking->bookable?->name ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 hidden lg:table-cell text-gray-600">
                                {{ $booking->check_in?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-gray-900">
                                ₦{{ number_format($booking->commitment_fee) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $statusClasses = match($booking->status) {
                                        'Confirmed'       => 'bg-emerald-100 text-emerald-700',
                                        'Checked In'      => 'bg-blue-100 text-blue-700',
                                        'Checked Out'     => 'bg-gray-100 text-gray-600',
                                        'Cancelled'       => 'bg-red-100 text-red-600',
                                        'Pending Payment' => 'bg-amber-100 text-amber-700',
                                        default           => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Chart.js ── --}}
    @if(array_sum($chartRevenue) > 0 || array_sum($chartCounts) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        var ctx = document.getElementById('revenueChart');
        if (!ctx) return;

        var labels  = @json($chartLabels);
        var revenue = @json($chartRevenue);
        var counts  = @json($chartCounts);

        // Guard: ensure arrays are non-empty
        if (!labels.length || !revenue.length) return;

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue (₦)',
                        data: revenue,
                        backgroundColor: 'rgba(29,92,82,0.75)',
                        borderColor: '#7C0E52',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Bookings',
                        data: counts,
                        type: 'line',
                        borderColor: '#C79A46',
                        backgroundColor: 'rgba(200,146,42,0.15)',
                        borderWidth: 2,
                        pointBackgroundColor: '#C79A46',
                        pointRadius: 4,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y2',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) {
                                return '₦' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v);
                            },
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y2: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 11 } },
                        grid: { drawOnChartArea: false }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 12 }, usePointStyle: true }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.yAxisID === 'y') {
                                    return ' Revenue: ₦' + ctx.parsed.y.toLocaleString();
                                }
                                return ' Bookings: ' + ctx.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    })();
    </script>
    @endif

</x-layouts.admin>
