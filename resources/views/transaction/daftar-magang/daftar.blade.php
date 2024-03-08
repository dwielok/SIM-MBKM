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
                    </div>
                    <div class="card-body p-0">
                        <form method="post" action="{{ $url }}" role="form" class="form-horizontal"
                            id="form-kuota" enctype="multipart/form-data">
                            @csrf
                            {!! method_field($action) !!}
                            <div class="form-message text-center"></div>
                            <div id="stepper1" class="bs-stepper">
                                <div class="bs-stepper-header">
                                    <div class="step" data-target="#test-l-1">
                                        <button type="button" class="btn step-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Mitra</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#test-l-2">
                                        <button type="button" class="btn step-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Peran</span>
                                        </button>
                                    </div>
                                    @if ($mitra->kegiatan->is_submit_proposal == 1)
                                        <div class="line"></div>
                                        <div class="step" data-target="#test-l-3">
                                            <button type="button" class="btn step-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">Proposal</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="test-l-1" class="content">
                                        @foreach ($datas as $data)
                                            <div class="form-group row mb-2">
                                                <label
                                                    class="col-12 col-md-2 control-label col-form-label">{{ $data->title }}</label>
                                                <div class="col-12 col-md-10">
                                                    @if ($data->textarea)
                                                        <textarea readonly disabled type="text" class="form-control form-control-sm" id="mitra_deskripsi">{!! $data->value ? $data->value : '' !!}</textarea>
                                                    @else
                                                        <input disabled="" type="text"
                                                            class="form-control form-control-sm" id="judul"
                                                            name="judul" value="{!! $data->value !!}">
                                                    @endif
                                                </div>

                                            </div>
                                        @endforeach
                                        <div class="form-group row mb-2 required">
                                            <label class="col-12 col-md-2 control-label col-form-label">Skema</label>
                                            <div class="col-12 col-md-10">
                                                <select data-testid="partner-category" class="form-control form-control-sm"
                                                    id="magang_skema" name="magang_skema">
                                                    <option value="" disabled selected>Pilih Skema</option>
                                                    @foreach ($mitra->skema as $s)
                                                        <option value="{{ $s }}">{{ $s }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Pilih skema yang akan diambil</small>
                                            </div>
                                        </div>
                                        <a class="btn btn-primary text-white" onclick="stepper1.next()">Simpan</a>
                                    </div>
                                    <div id="test-l-2" class="content">
                                        <div class="form-group row mb-2">
                                            <label class="col-12 col-md-2 control-label col-form-label">Peran</label>
                                            <div class="col-12 col-md-10">
                                                <select data-testid="partner-category" class="form-control form-control-sm"
                                                    id="tipe_pendaftar" name="tipe_pendaftar">
                                                    <option value="2">Individu</option>
                                                    <option value="0">Kelompok</option>
                                                </select>
                                                <small class="form-text text-muted">Pilih peran individu / kelompok</small>
                                            </div>
                                        </div>
                                        <div id="mahasiswa_select">
                                            <div class="form-group row mb-2">
                                                <label class="col-12 col-md-2 control-label col-form-label">Mahasiswa
                                                    1</label>
                                                <div class="col-12 col-md-10">
                                                    <select data-testid="partner-category"
                                                        class="form-control select2_combobox form-control-sm mahasiswa-1"
                                                        id="mahasiswa_1" name="mahasiswa[]" readonly>
                                                        <option value="" selected>Pilih Mahasiswa 1</option>
                                                        @foreach ($mahasiswas as $item)
                                                            <option value="{{ $item->mahasiswa_id }}"
                                                                {{ $item->mahasiswa_id == $mahasiswa_id ? 'selected' : '' }}>
                                                                {{ $item->nim }} -
                                                                {{ $item->nama_mahasiswa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-12 col-md-2 control-label col-form-label">Mahasiswa
                                                    2</label>
                                                <div class="col-12 col-md-10">
                                                    <select data-testid="partner-category2"
                                                        class="form-control select2_combobox form-control-sm mahasiswa-2"
                                                        id="mahasiswa_2" name="mahasiswa[]">
                                                        <option value="" selected>Pilih Mahasiswa 2</option>
                                                        @foreach ($mahasiswas as $item)
                                                            <option value="{{ $item->mahasiswa_id }}">
                                                                {{ $item->nim }} -
                                                                {{ $item->nama_mahasiswa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-12 col-md-2 control-label col-form-label">Mahasiswa
                                                    3</label>
                                                <div class="col-12 col-md-10">
                                                    <select data-testid="partner-category"
                                                        class="form-control select2_combobox form-control-sm mahasiswa-3"
                                                        id="mahasiswa_3" name="mahasiswa[]">
                                                        <option value="" selected>Pilih Mahasiswa 3</option>
                                                        @foreach ($mahasiswas as $item)
                                                            <option value="{{ $item->mahasiswa_id }}">
                                                                {{ $item->nim }} -
                                                                {{ $item->nama_mahasiswa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-primary text-white" onclick="stepper1.previous()">Back</a>
                                        @if ($mitra->kegiatan->is_submit_proposal == 1)
                                            <a class="btn btn-primary text-white" onclick="stepper1.next()">Simpan</a>
                                        @else
                                            <button class="btn btn-warning text-dark" type="submit">Konfirmasi</button>
                                        @endif
                                    </div>
                                    @if ($mitra->kegiatan->is_submit_proposal == 1)
                                        <div id="test-l-3" class="content">
                                            <div class="form-group row mb-2 required">
                                                <label
                                                    class="col-12 col-md-2 control-label col-form-label">Proposal</label>
                                                <div class="col-12 col-md-10">
                                                    <div class="form-control-sm custom-file">
                                                        <input type="file" class="form-control-sm custom-file-input"
                                                            data-target="0" id="berita_doc_0" name="proposal"
                                                            data-rule-filesize="1"
                                                            data-rule-accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                            accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                                                        <label class="form-control-sm custom-file-label file_label_0"
                                                            for="berita_doc_0">Choose
                                                            file</label>
                                                    </div>
                                                    <small class="form-text text-muted">Pilih Proposal untuk
                                                        Diupload</small>
                                                </div>
                                            </div>
                                            <a class="btn btn-primary text-white"
                                                onclick="stepper1.previous()">Previous</a>
                                            <button class="btn btn-warning" type="submit">Konfirmasi</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('content-js')
    <script>
        var stepper1Node = document.querySelector('#stepper1')
        var stepper1 = new Stepper(document.querySelector('#stepper1'))

        stepper1Node.addEventListener('show.bs-stepper', function(event) {
            console.warn('show.bs-stepper', event)
        })
        stepper1Node.addEventListener('shown.bs-stepper', function(event) {
            console.warn('shown.bs-stepper', event)
        })

        var stepper2 = new Stepper(document.querySelector('#stepper2'), {
            linear: false,
            animation: true
        })
        var stepper3 = new Stepper(document.querySelector('#stepper3'), {
            animation: true
        })
        var stepper4 = new Stepper(document.querySelector('#stepper4'))
    </script>
    <script>
        var loadFile = function(event) {
            $('input.custom-file-input').on('change', function() {
                // Get the file name
                var fileName = $(this).val().split('\\').pop();

                // Set the label text to the file name
                $(this).next('.custom-file-label').html(fileName);
            });

        };

        $(document).ready(function() {
            // unblockUI();
            // bsCustomFileInput.init();

            $('#mitra_deskripsi').summernote("disable");

            loadFile()

            $('.select2_combobox').select2();

            $('#mahasiswa_select').hide();

            $('#tipe_pendaftar').on('change', function() {
                var value = $(this).val()
                console.log(value);
                if (value == 0) {
                    $('#mahasiswa_select').show();
                } else {
                    $('#mahasiswa_select').hide();
                }
            })

            $("#form-kuota").submit(function() {
                $('.form-message').html('');
                let blc = '#modal-kuota';
                blockUI(blc);
                $(this).ajaxSubmit({
                    dataType: 'json',
                    success: function(data) {
                        refreshToken(data);
                        unblockUI(blc);
                        setFormMessage('.form-message', data);
                        if (data.stat) {
                            if (typeof dataDetail != 'undefined') dataDetail.draw();
                            if (typeof dataMaster != 'undefined') dataMaster.draw(false);
                            if (typeof tableFile != 'undefined') tableFile.draw();
                            if (typeof tableQuiz != 'undefined') tableQuiz.draw();
                            if (typeof tableAssignment != 'undefined') tableAssignment.draw();
                        }
                        closeModal($modal, data);
                    }
                });
                return false;
            });
        });
    </script>
@endpush
