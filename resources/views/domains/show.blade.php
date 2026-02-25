@extends('layouts.app')

@section('title', $domain->name)

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('domains.index') }}" class="btn btn-link text-muted ps-0">
        <i class="bi bi-arrow-left"></i> Всі домени
    </a>
</div>

@php
    $last_log      = $domain->logs[0] ?? null;
    $success_count = count(array_filter($domain->logs, fn($l) => $l->is_successful));
    $fail_count    = count(array_filter($domain->logs, fn($l) => !$l->is_successful));
    $times         = array_filter(array_map(fn($l) => $l->response_time_ms, $domain->logs), fn($t) => $t !== null);
    $avg_time      = count($times) > 0 ? round(array_sum($times) / count($times)) . ' мс' : '—';
@endphp

<div class="row g-4">

    {{-- Заголовок + статус --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $domain->name }}</h4>
                    <a href="{{ $domain->url }}" target="_blank" class="text-muted">
                        {{ $domain->url }} <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
                <div class="text-end">
                    @if($last_log)
                        @if($last_log->is_successful)
                            <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Online</span>
                        @else
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Offline</span>
                        @endif
                        <div class="text-muted small mt-1">
                            Перевірено: {{ \Carbon\Carbon::parse($last_log->checked_at)->diffForHumans() }}
                        </div>
                    @else
                        <span class="badge bg-secondary">Ще не перевірявся</span>
                    @endif
                    <div class="mt-2 d-flex gap-2 justify-content-end">
                        <form method="POST" action="{{ route('domains.check-now', $domain->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-arrow-repeat"></i> Перевірити зараз
                            </button>
                        </form>
                        <a href="{{ route('domains.edit', $domain->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Редагувати
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Статистика --}}
    @if(count($domain->logs) > 0)
    <div class="col-md-4">
        <div class="card shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-success">{{ $success_count }}</div>
            <div class="text-muted small">Успішних</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-danger">{{ $fail_count }}</div>
            <div class="text-muted small">Помилок</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center py-3">
            <div class="fs-2 fw-bold">{{ $avg_time }}</div>
            <div class="text-muted small">Сер. час відповіді</div>
        </div>
    </div>
    @endif

    {{-- Налаштування перевірок --}}
    @if($domain->settings)
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body px-4 py-3">
                <span class="text-muted small">
                    <i class="bi bi-sliders"></i>
                    Перевірка кожні <strong>{{ $domain->settings->interval_minutes }} хв</strong> &bull;
                    Таймаут: <strong>{{ $domain->settings->timeout_seconds }} сек</strong> &bull;
                    Метод: <strong>{{ $domain->settings->method }}</strong>
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- Історія перевірок --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-clock-history"></i> Історія перевірок
            </div>
            @if(empty($domain->logs))
                <div class="card-body text-center text-muted py-4">
                    Перевірок ще не було.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Дата</th>
                                <th>Результат</th>
                                <th>Код</th>
                                <th>Час відповіді</th>
                                <th>Помилка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($domain->logs as $log)
                            <tr>
                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::parse($log->checked_at)->format('d.m.Y H:i:s') }}
                                </td>
                                <td>
                                    @if($log->is_successful)
                                        <span class="badge bg-success">OK</span>
                                    @else
                                        <span class="badge bg-danger">FAIL</span>
                                    @endif
                                </td>
                                <td>{{ $log->status_code ?? '—' }}</td>
                                <td>{{ $log->response_time_ms !== null ? $log->response_time_ms . ' мс' : '—' }}</td>
                                <td class="text-danger small">{{ $log->error_message ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
