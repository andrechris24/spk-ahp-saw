@extends('errors.error')
@section('title', '403 Forbidden')
@section('error-title', '403 Forbidden')
@section('error-text', 'Anda tidak diperbolehkan untuk mengakses halaman ini.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda"
arrow="left-circle-fill" />
@endsection