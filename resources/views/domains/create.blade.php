@extends('layouts.app')

@section('title', 'Додати домен')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('domains.index') }}" class="btn btn-link text-muted ps-0">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Новий домен</h5>

                <form method="POST" action="{{ route('domains.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Назва <span class="text-muted">(для зручності)</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Мій сайт" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror"
                               value="{{ old('url') }}" placeholder="https://example.com" required>
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-plus-lg"></i> Додати домен
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
