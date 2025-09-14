@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Управление валютами</h4>
                    <form method="POST" action="{{ route('rates.update') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Обновить курсы
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <h5>Валюты</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Код</th>
                                    <th>Название</th>
                                    <th>Символ</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currencies as $currency)
                                    <tr>
                                        <td>{{ $currency->code }}</td>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->symbol }}</td>
                                        <td>
                                            <span class="badge {{ $currency->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $currency->is_active ? 'Активна' : 'Неактивна' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('currencies.toggle', $currency->id) }}" class="btn btn-sm {{ $currency->is_active ? 'btn-warning' : 'btn-success' }}">
                                                {{ $currency->is_active ? 'Деактивировать' : 'Активировать' }}
                                            </a>
                                        </td>
                                    </tr>
    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection