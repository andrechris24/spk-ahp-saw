@extends('errors.error')
@section('title', '503 Service Unavailable')
@section('error-title', '503 Service Unavailable')
@section('error-text', 'Website atau Server sedang tidak tersedia
	atau sedang dalam pemeliharaan. Cobalah beberapa saat lagi.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda"
arrow="left-circle-fill" />
@endsection