@extends('errors.error')
@section('title', '429 Too Many Requests')
@section('error-title', '429 Too Many Requests')
@section('error-text', 'Anda mengirim terlalu banyak permintaan ke server.
Tunggu beberapa saat lalu Klik tombol Coba lagi.')
@section('error-action')
<x-back-to-home url="javascript:location.reload();" btn="Coba lagi" arrow="clockwise" />
@endsection