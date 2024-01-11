@extends('errors.error')
@section('title', '404 Not Found')
@section('error-title', '404 Not Found')
@section('error-text', 'Halaman atau Data yang Anda cari tidak ditemukan.')
@section('error-action')
<x-back-to-home url="{{ route('home.index') }}" btn="Kembali ke Beranda"
arrow="left-circle-fill" />
@endsection