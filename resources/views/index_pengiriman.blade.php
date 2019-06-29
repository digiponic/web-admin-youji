<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table id='table_dashboard' class='table table-striped table-bordered table-hover'>
  <thead>
      <tr>
        <th>Pengiriman</th>
        <th>Kode Penjualan</th>
        <th>Pelanggan</th>
        <th>Kota/Kecamatan</th>
        <th>Alamat</th>
        <th>Pembayaran</th>
        <th>Aksi</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->tanggal}}</td>
        <td>{{$row->kode}}</td>
        <td>{{$row->pelanggan}}</td>
        <td>{{$row->kota}}<br/>{{$row->kecamatan}}</td>
        <td>{{$row->alamat}}</td>
        <td>{{$row->metode_pembayaran}}</td>
        <td>
          <!-- To make sure we have read access, wee need to validate the privilege -->
          {{-- <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("terkirim/$row->id")}}'><i class="fa fa-check"></i></a> --}}
          {{-- @if(CRUDBooster::isUpdate() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("edit/$row->id")}}'><i class="fa fa-pencil"></i></a>
          @endif
          
          @if(CRUDBooster::isDelete() && $button_edit)
          <a class='btn btn-danger btn-sm' href='{{CRUDBooster::mainpath("delete/$row->id")}}'><i class="fa fa-trash"></i></a>
          @endif --}}
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->
{{-- <p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p> --}}
@endsection