@extends('layouts.app')

@section('title', 'Мої домени')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Мої домени</h4>
    <div class="d-flex gap-2">
        @if(!empty($domains))
        <form method="POST" action="{{ route('domains.check-all') }}">
            @csrf
            <button type="submit" class="btn btn-outline-dark">
                <i class="bi bi-arrow-repeat"></i> Перевірити всі
            </button>
        </form>
        @endif
        <a href="{{ route('domains.create') }}" class="btn btn-dark">
            <i class="bi bi-plus-lg"></i> Додати домен
        </a>
    </div>
</div>

@if(empty($domains))
    <div class="card shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-globe2 fs-1 text-muted"></i>
            <p class="mt-3 text-muted">Ви ще не додали жодного домену.</p>
            <a href="{{ route('domains.create') }}" class="btn btn-dark">Додати перший домен</a>
        </div>
    </div>
@else
    <div class="card shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Статус</th>
                    <th>Назва</th>
                    <th>URL</th>
                    <th>Додано</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td class="align-middle">
                        @if(!$domain->is_active)
                            <span class="badge bg-secondary">Вимкнено</span>
                        @elseif($domain->last_is_successful === true)
                            <span class="status-dot up" title="Online"></span>
                            <span class="text-success small ms-1">Online</span>
                        @elseif($domain->last_is_successful === false)
                            <span class="status-dot down" title="Offline"></span>
                            <span class="text-danger small ms-1">Offline</span>
                        @else
                            <span class="status-dot unknown" title="Ще не перевірявся"></span>
                            <span class="text-muted small ms-1">—</span>
                        @endif
                    </td>
                    <td class="align-middle">
                        <a href="{{ route('domains.show', $domain->id) }}" class="text-decoration-none fw-semibold">
                            {{ $domain->name }}
                        </a>
                    </td>
                    <td class="align-middle">
                        <a href="{{ $domain->url }}" target="_blank" class="text-muted small">
                            {{ $domain->url }} <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </td>
                    <td class="align-middle text-muted small">{{ $domain->created_at }}</td>
                    <td class="align-middle text-end">
                        <a href="{{ route('domains.edit', $domain->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('domains.destroy', $domain->id) }}" class="d-inline"
                              onsubmit="return confirm('Видалити домен та всю його історію?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
