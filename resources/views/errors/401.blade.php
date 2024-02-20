@extends('errors.error')
@section('title', '401 Unauthorized')
@section('error-title', '401 Unauthorized')
@section('error-text', 'Anda harus log in untuk membuka halaman ini.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda" arrow="left-circle-fill" />
@endsection