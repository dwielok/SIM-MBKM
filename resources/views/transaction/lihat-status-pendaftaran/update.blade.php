@extends('layouts.template')

@section('content')
    <div class="container-fluid" id="container-daftar">
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
                        <div class="form-message text-center"></div>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th class="w-15 text-right">Magang ID</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">{{ $magang->magang_kode }}</td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Nama Kegiatan</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">{{ $magang->mitra->kegiatan->kegiatan_nama }}</td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Nama Mitra</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">
                                        <i class="far fa-building text-md text-primary"></i>
                                        {{ $magang->mitra->mitra_nama }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Periode</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">{{ $magang->periode->periode_nama }}</td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Durasi</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">
                                        <i class="far fa-clock text-md text-primary"></i>
                                        {{ $magang->mitra->mitra_durasi }}
                                        bulan
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Skema</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">{{ $magang->magang_skema }}</td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Tanggal Pendaftaran</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">
                                        <i class="far fa-calendar-alt text-md text-primary"></i>
                                        {{ \Carbon\Carbon::parse($magang->mitra->mitra_batas_pendaftaran)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-15 text-right">Anggota</th>
                                    <th class="w-1">:</th>
                                    <td class="w-84">
                                        <table class="table table-sm text-sm table-bordered"
                                            style="table-layout:fixed;width:100%;" id="table-mhs">
                                            <thead>
                                                <tr>
                                                    <th style="width: 14%">No</th>
                                                    <th style="width: 22%">NIM</th>
                                                    <th style="width: 45%">Nama Mahasiswa</th>
                                                    <th style="width: 14%">Kelas</th>
                                                    <th style="width: 14%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($anggotas as $key => $a)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $a->mahasiswa->nim }}</td>
                                                        <td>{{ $a->mahasiswa->nama_mahasiswa }}@if ($a->magang_tipe == 0)
                                                                <span class="badge badge-pill badge-primary">Ketua</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $a->mahasiswa->kelas }}</td>
                                                        <td>
                                                            @if ($a->magang_tipe == 1)
                                                                @if ($a->is_accept == 0)
                                                                    <span
                                                                        class="badge badge-pill badge-warning">Menunggu</span>
                                                                @elseif ($a->is_accept == 1)
                                                                    <span
                                                                        class="badge badge-pill badge-success">Menerima</span>
                                                                @elseif ($a->is_accept == 2)
                                                                    <span
                                                                        class="badge badge-pill badge-danger">Menolak</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @if (!$magang->proposal_exist)
                                    @if ($magang->ketua)
                                        <tr>
                                            <th class="w-15 text-right">Berkas Proposal</th>
                                            <th class="w-1">:</th>
                                            <td class="w-84 py-2">
                                                @if (!$magang->can_upload_proposal)
                                                    <span class="badge badge-danger">Belum bisa upload dikarenakan ada
                                                        anggota yang belum menerima ajakan</span>
                                                @else
                                                    <form method="post" action="{{ route('dokumen.upload_proposal') }}"
                                                        role="form" class="form-horizontal" id="form-proposal"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="d-flex">
                                                            <div class="form-control-sm custom-file">
                                                                <input type="hidden" value="{{ $magang->magang_id }}"
                                                                    name="magang_id" />
                                                                <input type="file"
                                                                    class="form-control-sm custom-file-input"
                                                                    data-target="0" id="proposal" name="proposal"
                                                                    data-rule-filesize="1"
                                                                    data-rule-accept="application/pdf"
                                                                    accept="application/pdf" />
                                                                <label
                                                                    class="form-control-sm custom-file-label file_label_0"
                                                                    for="proposal">Choose
                                                                    file</label>
                                                            </div>
                                                            <button type="submit"
                                                                class="ml-2 btn btn-sm btn-primary text-white">Upload</button>
                                                        </div>
                                                        <small class="form-text text-muted">Pilih file proposal dengan
                                                            format
                                                            .pdf</small>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <th class="w-15 text-right">Berkas Proposal</th>
                                        <th class="w-1">:</th>
                                        <td class="w-84 py-2">
                                            <table class="table table-sm text-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center w-5 p-1">No</th>
                                                        <th>Nama Berkas</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th><em>Last Update</em></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center w-5 p-1">1</td>
                                                        <td>
                                                            <a
                                                                href="{{ asset('assets/proposal/' . $magang->proposal->dokumen_magang_file) }}">{{ $magang->proposal->dokumen_magang_file }}</a>
                                                        </td>
                                                        <td>-</td>
                                                        <td>
                                                            @if ($magang->proposal->dokumen_magang_status == '1')
                                                                <span class="badge badge-success">Disetujui</span>
                                                            @elseif ($magang->proposal->dokumen_magang_status == '0')
                                                                <span class="badge badge-danger">Ditolak</span>
                                                            @else
                                                                <span class="badge badge-warning">Menunggu</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($magang->proposal->created_at)->format('d M Y H:i') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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

            loadFile()

            $("#form-proposal").submit(function() {
                $('.form-message').html('');
                let blc = '#container-daftar';
                blockUI(blc);
                $(this).ajaxSubmit({
                    dataType: 'json',
                    success: function(data) {
                        refreshToken(data);
                        unblockUI(blc);
                        setFormMessage('.form-message', data);
                        if (data.stat) {
                            //reload this page
                            window.location.reload();
                        }
                    }
                });
                return false;
            });

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
