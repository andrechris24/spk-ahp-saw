@extends('layout')
@section('content')
<div class="page-heading">
          <div class="page-title">
              <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Alternatif</h3>
                <p class="text-subtitle text-muted">
                  Alternatif adalah data yang berupa objek seperti orang, rumah, dll.
                </p>
              </div>
          </div>
          <section class="section">
            <div class="card">
              <div class="card-header">Daftar Alternatif</div>
              <div class="card-body">
              	<button type="button" class="btn btn-primary">
              	<i class="bi bi-plus-lg"></i>
              	Tambah Alternatif
              	</button>
                <table class="table table-striped" id="table-alter">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Alternatif</th>
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