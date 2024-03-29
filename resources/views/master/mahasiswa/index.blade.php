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
                                    data-url="{{ $page->url }}/create"><i class="fas fa-plus"></i> Tambah</button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-full-width" id="table_master">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Prodi</th>
                                        <th>Nim</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Email</th>
                                        <th>No HP</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Kelas</th>
                                        {{-- <th>Nama Orang Tua</th>
                                        <th>No HP Orang Tua</th> --}}
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
                    "type": "POST"
                },
                "aoColumns": [{
                        "mData": "no",
                        "sClass": "text-center",
                        "sWidth": "5%",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "prodi.prodi_name",
                        "sClass": "",
                        "sWidth": "10%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "nim",
                        "sClass": "",
                        "sWidth": "5%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "nama_mahasiswa",
                        "sClass": "",
                        "sWidth": "20%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "email_mahasiswa",
                        "sClass": "",
                        "sWidth": "15%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "no_hp",
                        "sClass": "",
                        "sWidth": "15%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "jenis_kelamin",
                        "sClass": "",
                        "sWidth": "15%",
                        "bSortable": true,
                        "bSearchable": true,
                        "mRender": function(data, type, row, meta) {
                            return data == 1 ? 'Laki-laki' : 'Perempuan';
                        }
                    },
                    {
                        "mData": "kelas",
                        "sClass": "",
                        "sWidth": "15%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    // {
                    //     "mData": "nama_ortu",
                    //     "sClass": "",
                    //     "sWidth": "30%",
                    //     "bSortable": true,
                    //     "bSearchable": true
                    // },
                    // {
                    //     "mData": "hp_ortu",
                    //     "sClass": "",
                    //     "sWidth": "30%",
                    //     "bSortable": true,
                    //     "bSearchable": true
                    // },
                    {
                        "mData": "mahasiswa_id",
                        "sClass": "text-center pr-2",
                        "sWidth": "10%",
                        "bSortable": false,
                        "bSearchable": false,
                        "mRender": function(data, type, row, meta) {
                            console.log(row);
                            var buttons = '';
                            @if ($allowAccess->update)
                                // if (row.status == 0) {
                                //     buttons +=
                                //         `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/confirm_approve" class="ajax_modal btn btn-xs btn-success tooltips text-white" data-placement="left" data-original-title="Approve" ><i class="fa fa-check"></i></a> ` +
                                //         `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/confirm_reject" class="ajax_modal btn btn-xs btn-danger tooltips text-white" data-placement="left" data-original-title="Reject" ><i class="fa fa-times"></i></a> `;
                                // }
                                buttons +=
                                    `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/edit" class="ajax_modal btn btn-xs btn-warning tooltips text-secondary" data-placement="left" data-original-title="Edit Data" ><i class="fa fa-edit"></i></a> `;
                            @endif
                            @if ($allowAccess->delete)
                                buttons +=
                                    `<a href="#" data-block="body" data-url="{{ $page->url }}/${data}/delete" class="ajax_modal btn btn-xs btn-danger tooltips text-light" data-placement="left" data-original-title="Hapus Data" ><i class="fa fa-trash"></i></a> `;
                            @endif

                            return buttons;
                        }
                    }
                ],
                "fnDrawCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('a', this.fnGetNodes()).tooltip();
                }
            });

            $('.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    dataMaster.search($(this).val()).draw();
                }
            });
        });
    </script>
@endpush
