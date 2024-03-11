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
                                        <div class="form-group required row mb-2">
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
                                                <label class="col-12 col-md-2 control-label col-form-label">Anggota</label>
                                                <div class="col-12 col-md-6" id="add_member">
                                                    <table class="table table-striped table-sm text-sm mb-0"
                                                        style="table-layout:fixed;width:100%;" id="table-mhs">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 14%">No</th>
                                                                <th style="width: 22%">NIM</th>
                                                                <th style="width: 45%">Nama Mahasiswa</th>
                                                                <th style="width: 14%">Kelas</th>
                                                                <th style="width: 5%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{ $saya->nim }}</td>
                                                                <td>{{ $saya->nama_mahasiswa }}</td>
                                                                <td>{{ $saya->kelas }}</td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-group mb-2">
                                                                <label class="control-label col-form-label">Cari
                                                                    Mahasiswa</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm" id="search"
                                                                        autocomplete="off" />
                                                                    <span class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-info btn-flat"
                                                                            id="btn-cari-mhs">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-2">
                                                                <input type="hidden" class="form-control form-control-sm"
                                                                    id="mhs_id" disabled readonly />
                                                                <label
                                                                    class="col-sm-3 control-label col-form-label">NIM</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="mhs_nim" disabled readonly />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-2">
                                                                <label
                                                                    class="col-sm-3 control-label col-form-label">Nama</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="mhs_nama" disabled readonly />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-2">
                                                                <label
                                                                    class="col-sm-3 control-label col-form-label">Kelas</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="mhs_kelas" disabled readonly />
                                                                </div>
                                                            </div>
                                                            <button type="button" id="btn-tambah-mhs"
                                                                class="btn btn-primary float-right">Tambah</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="data-mhs">
                                                <input type="hidden" class="form-control form-control-sm"
                                                    name="mahasiswa[]" value="{{ $saya->mahasiswa_id }}" />
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
                                            <a class="btn btn-primary text-white" onclick="stepper1.previous()">Back</a>
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
            $('#btn-tambah-mhs').attr('disabled', true)

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

            $("#btn-cari-mhs").click(function() {
                $('.form-message').html('');
                const nim = $('#search').val()

                $.ajax({
                    url: "{{ url('mahasiswa') }}" + "/" + nim + "/cari",
                    type: "GET",
                    success: function(response) {
                        // Handle the success response here
                        console.log(response);
                        if (response.stat) {
                            $('#btn-tambah-mhs').attr('disabled', false)
                            $('#mhs_id').val(response.data.mahasiswa_id)
                            $('#mhs_nim').val(response.data.nim)
                            $('#mhs_nama').val(response.data.nama_mahasiswa)
                            $('#mhs_kelas').val(response.data.kelas)
                        } else {
                            setFormMessage('.form-message', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            })

            $('#btn-tambah-mhs').click(function() {
                var rowCount = $('#table-mhs tbody tr').length;

                if (rowCount > 2) return

                const mhs_id = $('#mhs_id').val()
                const nim = $('#mhs_nim').val()
                const nama = $('#mhs_nama').val()
                const kelas = $('#mhs_kelas').val()
                $('#table-mhs tbody').append(`
                    <tr data-id="${rowCount+1}">
                        <td>${rowCount + 1}</td>
                        <td>${nim}</td>
                        <td>${nama}</td>
                        <td>${kelas}</td>
                        <td><a class="cursor-pointer" id="remove-mhs" data-id="${rowCount +1}"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                `);
                $('#data-mhs').append(`
                    <input type="hidden" class="form-control form-control-sm" name="mahasiswa[]" value="${mhs_id}" data-id="${rowCount+1}" />
                `);
                $('#btn-tambah-mhs').attr('disabled', true)
                $('#mhs_nim').val('')
                $('#mhs_nama').val('')
                $('#mhs_kelas').val('')
                $('#search').val('')
            })

            $(document).on('click', '#remove-mhs', function() {
                const index = $(this).data('id')
                console.log(index)
                //remove index <tr></tr> in table #table-mhs
                $(`#table-mhs tr[data-id="${index}"]`).remove();
                //remove too in <input name="mahasiswa[]" with data-id=index
                $(`#data-mhs input[name="mahasiswa[]"][data-id="${index}"]`).remove();
            });
        });
    </script>
@endpush
