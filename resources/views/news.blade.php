<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Intelligence Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Load Chart.js untuk Data Visualization Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        
        body { 
            background-color: #191a1c; 
            color: #d1d5db; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image: radial-gradient(#2d2e32 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .text-orange { color: #ff6a00 !important; }

        .card-floating {
            background-color: #f4f3ec;
            color: #1a1a1a;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border: none;
            margin-bottom: 24px;
        }

        .nav-pill-container {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .input-pill {
            background-color: #ffffff !important; 
            border: none; 
            color: #1a1a1a !important; 
            padding: 8px 16px; 
            font-size: 14px;
            border-radius: 30px;
            margin-right: 8px;
        }
        .input-pill:focus { 
            outline: none; 
            box-shadow: 0 0 0 2px #ff6a00; 
            background-color: #ffffff !important;
        }

        .btn-pill {
            background-color: #f4f3ec; color: #1a1a1a; border: none; border-radius: 30px; padding: 8px 24px; font-weight: 600; font-size: 14px; transition: all 0.2s;
        }
        .btn-pill:hover { background-color: #ff6a00; color: #fff; }

        .table-premium {
            width: 100%; margin-bottom: 0; color: #1a1a1a; vertical-align: middle;
        }
        .table-premium th {
            font-size: 11px; text-transform: uppercase; font-weight: 700; color: #6b7280; border-bottom: 2px solid #e5e7eb; padding: 12px 8px;
        }
        .table-premium td { padding: 14px 8px; font-size: 13px; border-bottom: 1px solid #e5e7eb; }

        .status-dot { height: 6px; width: 6px; border-radius: 50%; display: inline-block; margin-right: 6px; }
        .dot-pos { background-color: #10b981; }
        .dot-neg { background-color: #ef4444; }
        .dot-neu { background-color: #3b82f6; }
    </style>
</head>
<body class="py-4">

    <!-- TOP NAVIGATION -->
    <div class="container d-flex justify-content-center mb-5">
        <form action="" method="GET" class="nav-pill-container d-flex align-items-center">
            <div class="d-flex align-items-center px-3 border-end border-secondary border-opacity-50 me-2">
                <span class="text-orange fw-bold fs-5 me-2">⛗</span>
                <span class="text-white fw-bold tracking-wide">RiskTrack Pro</span>
            </div>
            <input type="text" name="country" value="{{ request('country', $country) }}" placeholder="Enter location..." class="form-control input-pill" style="width: 200px;">
            <button type="submit" class="btn btn-pill">Search</button>
        </form>
    </div>

    <div class="container">
        <!-- ROW UTAMA: RISK MATRIX ENGINE & REAL-TIME CHART -->
        <div class="row g-4 mb-4">
            
            <!-- FEATURE 2: RISK SCORING ENGINE CARD -->
            <div class="col-lg-4">
                <div class="card-floating p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">Risk Scoring Engine</h5>
                        <span class="badge font-monospace text-uppercase {{ $riskScore > 60 ? 'bg-danger text-white' : ($riskScore > 35 ? 'bg-warning text-dark' : 'bg-success text-white') }}">
                            {{ $riskStatus }}
                        </span>
                    </div>
                    <div class="text-center my-4">
                        <h1 class="display-2 fw-bold text-dark mb-0">{{ $riskScore }}</h1>
                        <p class="text-muted small">Weighted Risk Index / 100 Max</p>
                    </div>
                    <div class="small fw-medium text-secondary">
                        <div class="d-flex justify-content-between mb-2"><span>Weather Hazard (30%)</span><strong>{{ $breakdown['weather'] }}%</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Inflation Pressures (20%)</span><strong>{{ $breakdown['inflation'] }}%</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Geopolitical News (40%)</span><strong>{{ $breakdown['news'] }}%</strong></div>
                        <div class="d-flex justify-content-between"><span>Currency Volatility (10%)</span><strong>{{ $breakdown['currency'] }}%</strong></div>
                    </div>
                </div>
            </div>

            <!-- FEATURE 7: DATA VISUALIZATION CHART -->
            <div class="col-lg-4">
                <div class="card-floating p-4 h-100">
                    <h5 class="fw-bold mb-3 text-dark">Risk Vectors Matrix</h5>
                    <div style="height: 220px; position: relative;">
                        <canvas id="riskRadarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Panel Live Feed Kanan -->
            <div class="col-lg-4">
                <div class="card-floating p-4 h-100" style="max-height: 320px; overflow-y: auto;">
                    <h5 class="fw-bold mb-3 text-dark">Live Intel Stream</h5>
                    <div class="d-flex flex-column gap-2">
                        @if(empty($data))
                            <p class="text-muted small my-2">No active feed metrics cached.</p>
                        @else
                            @foreach(collect($data)->take(3) as $article)
                                @php $item = (array) $article; @endphp
                                <div class="p-2 rounded bg-white border border-opacity-10">
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 11px;">{{ Str::limit($item['title'] ?? 'No Title', 45) }}</h6>
                                    <span class="badge bg-dark text-white font-monospace" style="font-size: 9px;">{{ $item['sentiment'] ?? 'Neutral' }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW DATA INDIKATOR GLOBAL -->
        <div class="row g-4">
            <!-- Tabel Indikator Ekonomi & Supply Chain -->
            <div class="col-lg-6">
                <div class="card-floating p-4">
                    <h5 class="fw-bold text-dark mb-3">🌍 Active Target Matrix: {{ strtoupper($country) }}</h5>
                    <table class="table-premium">
                        <thead>
                            <tr><th>Indicator Metric</th><th>Current Value</th><th>Status Assesment</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>GDP Index Baseline</td><td>+4.2%</td><td><span class="status-dot dot-pos"></span> Stable</td></tr>
                            <tr><td>Core Inflation</td><td class="text-danger fw-bold">{{ $breakdown['inflation'] }}%</td><td><span class="status-dot dot-neg"></span> Volatile</td></tr>
                            <tr><td>Geopolitical Risk Vectors</td><td>{{ $breakdown['news'] }}% Index</td><td><span class="status-dot dot-neu"></span> Monitored</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Lokasi Watchlist Global -->
            <div class="col-lg-6">
                <div class="card-floating p-4">
                    <h5 class="fw-bold text-dark mb-3">📋 Global Watchlist Monitoring Matrix</h5>
                    <table class="table-premium">
                        <thead>
                            <tr><th>Code</th><th>Country</th><th>Threat Matrix</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr class="{{ strtolower($country) == 'indonesia' ? 'bg-white font-weight-bold' : '' }}">
                                <td>IDN</td><td>Indonesia</td><td><span class="badge bg-success bg-opacity-10 text-success">Low Threat</span></td>
                                <td><a href="?country=indonesia" class="btn btn-sm btn-dark px-2 rounded-pill" style="font-size: 10px;">Monitor</a></td>
                            </tr>
                            <tr class="{{ strtolower($country) == 'germany' ? 'bg-white font-weight-bold' : '' }}">
                                <td>DEU</td><td>Germany</td><td><span class="badge bg-danger bg-opacity-10 text-danger">High Risk</span></td>
                                <td><a href="?country=germany" class="btn btn-sm btn-dark px-2 rounded-pill" style="font-size: 10px;">Monitor</a></td>
                            </tr>
                            <tr class="{{ strtolower($country) == 'usa' ? 'bg-white font-weight-bold' : '' }}">
                                <td>USA</td><td>United States</td><td><span class="badge bg-warning bg-opacity-10 text-dark">Moderate</span></td>
                                <td><a href="?country=usa" class="btn btn-sm btn-dark px-2 rounded-pill" style="font-size: 10px;">Monitor</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT UTAMA UNTUK INTEGRASI CHART VISUALIZATION -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('riskRadarChart').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Weather (30%)', 'Inflation (20%)', 'Geopolitical (40%)', 'Currency (10%)'],
                    datasets: [{
                        label: 'Risk Vector Metrics',
                        data: [
                            {{ $breakdown['weather'] }},
                            {{ $breakdown['inflation'] }},
                            {{ $breakdown['news'] }},
                            {{ $breakdown['currency'] }}
                        ],
                        backgroundColor: 'rgba(255, 106, 0, 0.2)',
                        borderColor: '#ff6a00',
                        borderWidth: 2,
                        pointBackgroundColor: '#1a1a1a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: { display: false }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</body>
</html>