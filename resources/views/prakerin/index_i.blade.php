@extends('layouts.app2')

@section('css')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection 

@if(!$candidates->count())
@section('content-header')
<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-left mb-0">Belum ada siswa prakerin, silahkan cari kandidat yang sesuai dengan kebutuhan perusahaan anda</h2>
        </div>
    </div>
</div>
@endsection
@endif

@section('content')
<section id="basic-datatable">
  @include('flash::message')
    @foreach ($candidates as $candidate)
    @php
    $vacancy = $candidate[0]->vacancy;
    @endphp
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
              <div class="card-header">
                  <h4 class="card-title">Grup Magang - {{ $vacancy->title }} ({{ $vacancy->started_internship == 'yes' ? 'Aktif' : 'Selesai' }})</h4>
                  <div class="card-subtitle float-right">
                    @if($vacancy->started_internship == 'yes')
                    <button class="btn btn-danger btn-end" data-href="{{ route('prakerin.end', [$vacancy->id]) }}"><i class="fa fa-exclamation-circle"> Akhiri Magang</i></button>
                    <a class="btn btn-primary btn-modal" href="javascript:void(0);" data-href="{{ route('vacancy.ifile.create', [$vacancy->id]) }}" data-container=".my-modal"><i class="fa fa-plus"> Kirim File</i></a>
                    @endif
                  </div>
              </div>
              <div class="card-content">
                  <div class="card-body card-dashboard">
                      <div class="table-responsive">
                          <table class="table zero-configuration datatable">
                              <thead>
                                  <tr>
                                      <th>Nama</th>
                                      <th>Tanggal Diterima</th>
                                      <th>Detail</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($candidate as $key => $value)
                                <tr>
                                    <td>{{ $value->user->name ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->acc)->format('d-m-Y') ?? '' }}</td>
                                    <td><a href="{{ route('prakerin.detail_s', [$value->id, $vacancy->id]) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a></td>
                                </tr>
                                @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
    @endforeach
</section>
<div class="modal fade my-modal" id="xlarge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true"></div>
@endsection

@section('js')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script>
  $('.datatable').on('click', '.btn-modal', function(e){
      var t = $(this).data("container")
      $.ajax({
          url: $(this).data('href'),
          dataType: "html",
          success: function(e) {
              $(t).html(e).modal("show")
          }
      })
  })

  $('.btn-end').on('click', function(e){
      var btn = $(this);
      e.stopPropagation();
      Swal.fire({
          title: 'Anda yakin?',
          text: "Anda akan mengakhiri magang!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Akhiri!'
      }).then((result) => {
          if (result.value) {
              $.ajax({
                  url: btn.data('href'),
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  dataType: 'json',
                  success: function(res) {
                      if(res.status) {
                          Swal.fire({
                              icon: 'success',
                              title: 'Berhasil',
                              text: res.message
                          }).then((result) => {
                              window.location.href = res.url
                          })
                      } else {
                          Swal.fire({
                              icon: 'error',
                              title: 'Gagal',
                              text: res.message
                          })
                      }
                  }
              })
          }
      })
  });
</script>
@endsection