@extends('layout')
@section('content')
<div class="page-heading">
          <div class="page-title">
              <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kriteria</h3>
                <p class="text-subtitle text-muted">
                  Kriteria yang akan digunakan untuk Sistem Pendukung Keputusan
                </p>
              </div>
          </div>
          <section class="section">
            <div class="card">
              <div class="card-header">Daftar Kriteria</div>
              <div class="card-body">
              	<button type="button" class="btn btn-primary">
              	<i class="bi bi-plus-lg"></i>
              	Tambah Kriteria
              	</button>
                <table class="table table-striped" id="table-crit">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Kriteria</th>
                      <th>Atribut</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </section>
        </div>
@endsection

@section('js')

@endsection