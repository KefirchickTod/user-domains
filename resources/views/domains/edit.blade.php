@extends('layouts.app')

@section('title', 'Редагування: ' . $domain->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('domains.show', $domain->id) }}" class="btn btn-link text-muted ps-0">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>

        {{-- Основні дані --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Редагування домену</h5>

                <form method="POST" action="{{ route('domains.update', $domain->id) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Назва</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $domain->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror"
                               value="{{ old('url', $domain->url) }}" required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               id="is_active" {{ $domain->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Активний моніторинг</label>
                    </div>

                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-check-lg"></i> Зберегти
                    </button>
                </form>
            </div>
        </div>

        {{-- Налаштування перевірок --}}
        @if($domain->settings)
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Налаштування перевірок</h5>

                <form method="POST" action="{{ route('domains.settings.update', $domain->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Інтервал (хв)</label>
                            <input type="number" name="interval_minutes" min="1" max="1440"
                                   class="form-control @error('interval_minutes') is-invalid @enderror"
                                   value="{{ old('interval_minutes', $domain->settings->interval_minutes) }}" required>
                            @error('interval_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Таймаут (сек)</label>
                            <input type="number" name="timeout_seconds" min="1" max="60"
                                   class="form-control @error('timeout_seconds') is-invalid @enderror"
                                   value="{{ old('timeout_seconds', $domain->settings->timeout_seconds) }}" required>
                            @error('timeout_seconds')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Метод</label>
                            <select name="method" class="form-select @error('method') is-invalid @enderror" required>
                                <option value="HEAD" {{ $domain->settings->method === 'HEAD' ? 'selected' : '' }}>HEAD</option>
                                <option value="GET"  {{ $domain->settings->method === 'GET'  ? 'selected' : '' }}>GET</option>
                            </select>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-dark">
                        <i class="bi bi-sliders"></i> Зберегти налаштування
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
