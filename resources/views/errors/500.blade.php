@extends('errors.error')
@section('title', '500 Internal Server Error')
@section('error-title', '500 Internal Server Error')
@section('error-text', 'Terjadi kesalahan internal pada server. '.
'Cobalah beberapa saat lagi, atau klik Kembali ke Beranda.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda" arrow="left-circle-fill" />
@endsection