@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Конвертер валют</h4>
                </div>
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif

                    @if(isset($result))
                        <div class="alert alert-success">
                            <strong>Результат:</strong> {{ number_format($result, 2) }} {{ $request->to }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('converter.handle') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="amount" class="form-label">Сумма</label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', '') }}" step="0.01" min="0.01" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="from" class="form-label">Из валюты</label>
                                <select class="form-select @error('from') is-invalid @enderror" id="from" name="from" required>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}" {{ old('from') == $currency->code ? 'selected' : '' }}>
                                            {{ $currency->code }} ({{ $currency->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="to" class="form-label">В валюту</label>
                                <select class="form-select @error('to') is-invalid @enderror" id="to" name="to" required>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}" {{ old('to') == $currency->code ? 'selected' : '' }}>
                                            {{ $currency->code }} ({{ $currency->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Конвертировать</button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <p class="text-muted small">
                            Курсы обновлены на последнюю доступную дату. Базовая валюта: USD.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection