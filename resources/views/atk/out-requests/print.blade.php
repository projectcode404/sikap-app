@extends('layouts.print')

@section('title', 'Tanda Terima Permintaan ATK')
@php
    use Illuminate\Support\Carbon;
@endphp
@section('content')
    <h4 class="text-center mb-4">TANDA TERIMA PERMINTAAN ATK</h4>

    <table class="table table-borderless">
        <tbody>
            <tr>
                <th width="30%">Nama Peminta</th>
                <td>{{ $outRequest->createdBy->employee->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>{{ $outRequest->workUnit->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Permintaan</th>
                <td>{{ $outRequest->request_date ? Carbon::parse($outRequest->request_date)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>{{ $outRequest->period ? Carbon::createFromFormat('Y-m', $outRequest->period)->translatedFormat('F Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>{{ $outRequest->request_note }}</td>
            </tr>
        </tbody>
    </table>

    <h5 class="mt-4">Detail Barang</h5>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Qty Disetujui</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outRequest->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->atkItem->name }}</td>
                    <td>{{ $item->atkItem->unit }}</td>
                    <td>{{ $item->qty_approved }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row signature mt-5">
        <div class="col-6 text-center">
            <p>Disetujui oleh:</p>
            <br><br><br>
            <strong>{{ $outRequest->approvedBy->employee->full_name ?? '-' }}</strong>
        </div>
        <div class="col-6 text-center">
            <p>Diterima oleh:</p>
            <br><br><br>
            <strong>{{ $outRequest->createdBy->employee->full_name ?? '-' }}</strong>
        </div>
    </div>
@endsection