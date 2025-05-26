@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>📥 Import CSV Permintaan ATK dari Formester</h4>
    <form action="{{ route('atk.import.process') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="period">Periode Permintaan (misal: Mei 2025)</label>
            <input type="month" name="period" id="period" class="form-control" required value="{{ now()->format('Y-m') }}">
        </div>

        <div class="mb-3">
            <label for="csv_file">Upload File CSV</label>
            <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv" required>
            <small class="text-muted">Pastikan file hasil export dari Formester</small>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-upload me-1"></i> Upload & Lihat Preview
        </button>
    </form>
</div>
@endsection
