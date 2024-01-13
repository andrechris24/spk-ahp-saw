@extends('errors.error')
@section('title', '401 Unauthorized')
@section('error-title', '401 Unauthorized')
@section('error-text', 'Anda tidak boleh membuka halaman ini tanpa otorisasi. '.
'Mohon untuk log in ke halaman Administrator.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda" arrow="left-circle-fill" />
@endsection