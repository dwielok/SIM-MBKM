@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-12">
                <div class="card card-outline card-{{ $theme->card_outline }}">
                    <div class="card-header">
                        <h3 class="card-title mt-1">
                            <i class="fas fa-angle-double-right text-md text-{{ $theme->card_outline }} mr-1"></i>
                            {!! $page->title !!}
                        </h3>
                        <div class="card-tools">
                            @if ($allowAccess->create)
                                <button type="button" data-block="body"
                                    class="btn btn-sm btn-{{ $theme->button }} mt-1 ajax_modal"
                                    data-url="{{ $page->url }}/ajukan"><i class="fas fa-plus"></i> Ajukan Mitra</button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- untuk Filter data -->
                        <div id="filter" class="form-horizontal filter-date p-2 border-bottom">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm row text-sm mb-0">
                                        <label class="col-md-1 col-form-label">Filter</label>
                                        <div class="col-md-4">
                                            <select
                                                class="form-control form-control-sm w-100 filter_combobox filter_program">
                                                <option value="">- Semua -</option>
                                                @foreach ($programs as $d)
                                                    <option value="{{ $d->program_id }}">{{ $d->program_nama }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Program Kegiatan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-full-width" id="table_master">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Periode</th>
                                        <th>Nama Mitra</th>
                                        <th>Alamat</th>
                                        <th>Website</th>
                                        <th>Durasi</th>
                                        <th>Kuota</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('content-js')
    <script>
        $(document).ready(function() {

            $('.filter_combobox').select2();

            var v = 0;
            dataMaster = $('#table_master').DataTable({
                "bServerSide": true,
                "bAutoWidth": false,
                "ajax": {
                    "url": "{{ $page->url }}/list",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.program = $('.filter_program').val();
                    },
                },
                "aoColumns": [{
                        "mData": "no",
                        "sClass": "text-center",
                        "sWidth": "5%",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "kegiatan.kegiatan_nama",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true,
                    },
                    {
                        "mData": "periode.periode_nama",
                        "sClass": "",
                        "sWidth": "5%",
                        "bSortable": true,
                        "bSearchable": true,
                    },
                    {
                        "mData": "mitra_nama",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "mitra_alamat",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "mitra_website",
                        "sClass": "",
                        "sWidth": "5%",
                        "bSortable": true,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            return '<a href="' + data + '" target="_blank">Link</a>';
                        }
                        // "mRender": function(data, type, row, meta) {
                        //     switch (data) {
                        //         case 2:
                        //             return '<span class="badge badge-danger">Ditolak</span>';
                        //             break;
                        //         case 1:
                        //             return '<span class="badge badge-success">Diterima</span>';
                        //             break;
                        //         case 0:
                        //             return '<span class="badge badge-info">Menunggu</span>';
                        //             break;
                        //         default:
                        //             return '<span class="badge badge-danger">-</span>';
                        //             break;
                        //     }
                        // }
                    },
                    {
                        "mData": "mitra_durasi",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true,
                        "mRender": function(data, type, row, meta) {
                            return data + ' Bulan';
                        }
                    },
                    {
                        "mData": "mitra_kuota",
                        "sClass": "",
                        "sWidth": "15%",
                        "bSortable": true,
                        "bSearchable": true,
                        "mRender": function(data, type, row, meta) {
                            const jumlah = row.mitra_jumlah_pendaftar + '/' + data;
                            if (data == 0 || data == row.mitra_jumlah_pendaftar) {
                                return '<span class="badge badge-danger">' + jumlah + '</span>'
                            } else {
                                return jumlah
                            }
                        }
                    },
                    {
                        "mData": "encrypt_mitra_id",
                        "sClass": "pr-2",
                        "sWidth": "8%",
                        "bSortable": false,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            var buttons = '';
                            @if ($allowAccess->update)
                                // if (row.kegiatan.is_kuota == 1) {
                                buttons +=
                                    `<a href="{{ $page->url }}/${data}" class=" btn btn-xs btn-info tooltips text-light text-xs" data-placement="left" data-original-title="Lihat detail" >Daftar</a> `
                                // }
                            @endif
                            return buttons;
                        }
                    },
                ],
                "fnDrawCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('a', this.fnGetNodes()).tooltip();
                }
            });

            $('.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataMaster.search($(this).val(), {
                        caseInsensitive: true
                    }).draw();
                }
            });

            $('.filter_program').change(function() {
                dataMaster.draw();
            });
        });
    </script>
@endpush
