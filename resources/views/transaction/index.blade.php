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
                                        <th>Nama Dosen</th>
                                        <th>D4 TI</th>
                                        <th>D4 SIB</th>
                                        <th>D2 PPLS</th>
                                        <th>Jumlah</th>
                                        {{-- <th>#</th> --}}
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
                        "mData": "dosen_name",
                        "sClass": "",
                        "sWidth": "19%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "count_advisor_TI",
                        "sClass": "",
                        "sWidth": "19%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "count_advisor_SIB",
                        "sClass": "",
                        "sWidth": "19%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "count_advisor_PPLS",
                        "sClass": "",
                        "sWidth": "19%",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "jumlah_total",
                        "sClass": "",
                        "sWidth": "19%",
                        "bSortable": true,
                        "bSearchable": true
                    },
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
