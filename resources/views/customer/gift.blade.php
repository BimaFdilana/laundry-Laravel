@extends('layouts.backend')
@section('title', 'Gift Customer')
@section('style')
@endsection
@section('content')

    <div class="row mb-1">
        <div class="col">
            <h3>Notifikasi Gift</h3>
        </div>
    </div>

    @foreach ($gifts as $gift)
        @php
            $isExpired = $gift->expired_at && \Carbon\Carbon::parse($gift->expired_at)->isPast();
        @endphp

        <div class="alert alert-{{ $isExpired ? 'secondary' : 'success' }}" role="alert">
            <h4 class="alert-heading">🎁 {{ $isExpired ? 'Gift Expired' : 'Selamat!' }}</h4>
            <p>
                Anda {{ $isExpired ? 'pernah mendapatkan' : 'mendapatkan' }} gift berupa
                <strong>{{ $gift->gift }}</strong><br>
                Ket: {{ $gift->keterangan }}
            </p>
            @if ($gift->expired_at)
                <small class="text-muted">
                    {{ $isExpired ? 'Expired pada' : 'Berlaku hingga' }}:
                    {{ \Carbon\Carbon::parse($gift->expired_at)->translatedFormat('d F Y') }}
                </small>
            @endif

            {{-- Tombol Tandai Sudah Dibaca --}}
            <form action="{{ route('customer.gift.read', $gift->id) }}" method="POST" class="mt-2">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-primary">
                    Tandai Sudah Dibaca
                </button>
            </form>
        </div>
    @endforeach

    @if ($gifts->isEmpty())
        <p class="text-muted">Tidak ada notifikasi gift saat ini.</p>
    @endif

@endsection
@section('scripts')
@endsection
