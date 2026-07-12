<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-3">
            Countries
        </h2>
    </x-slot>

    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Country List</h3>

            <div>
                <a href="{{ route('countries.import') }}" class="btn btn-primary">
                    Import Countries
                </a>

                <a href="{{ route('weather.import') }}" class="btn btn-success">
                    Import Weather
                </a>

                <a href="{{ route('economy.import') }}" class="btn btn-warning">
                    Import Economy
                </a>
            </div>
        </div>

        <div class="card shadow">

            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Flag</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Capital</th>
                            <th>Region</th>
                            <th>Population</th>
                            <th>Currency</th>
                            <th>Risk Score</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($countries as $country)

                        <tr>

                            <td width="80">
                                @if($country->code)
                                    <img src="https://flagcdn.com/w80/{{ strtolower($country->code) }}.png"
                                         width="50"
                                         alt="{{ $country->name }}">
                                @endif
                            </td>

                            <td>{{ $country->name }}</td>

                            <td>{{ $country->code }}</td>

                            <td>{{ $country->capital }}</td>

                            <td>{{ $country->region }}</td>

                            <td>{{ number_format($country->population) }}</td>

                            <td>{{ $country->currency }}</td>

                            <td>{{ $country->risk_score }}</td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada data negara.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</x-app-layout>