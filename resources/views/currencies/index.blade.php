@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Курсы валют</h4>
                        <small class="text-muted">Актуальные курсы валют относительно доллара США (USD)</small>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Rates Table -->
                    <div id="rates-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Валюта</th>
                                        <th>Код</th>
                                        <th>Курс</th>
                                        <th>Дата</th>
                                    </tr>
                                </thead>
                                <tbody id="rates-body">
                                    @foreach($rates as $rate)
                                        <tr>
                                            <td>{{ $rate->currency->name }}</td>
                                            <td>{{ $rate->currency->code }}</td>
                                            <td>{{ number_format($rate->rate, 4) }}</td>
                                            <td>{{ $rate->date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $rates->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection