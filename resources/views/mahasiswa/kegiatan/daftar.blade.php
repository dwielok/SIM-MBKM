<?php
// jika $data ada ISI-nya maka actionnya adalah edit, jika KOSONG : insert
$is_edit = isset($data);
?>

<form method="post" action="{{ $url }}" role="form" class="form-horizontal" id="form-master">
    @csrf
    {!! $is_edit ? method_field('PUT') : '' !!}
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>
                <div class="form-group  row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Kode Kegiatan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm"
                            value="{{ isset($info['kegiatan']->kode_kegiatan) ? $info['kegiatan']->kode_kegiatan : 'diisi otomatis' }}"
                            readonly />
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Peran</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm" id="tipe_pendaftar"
                            name="tipe_pendaftar">
                            <option value="2">Individu</option>
                            <option value="0">Kelompok</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="form-control form-control-sm" name="mahasiswa_id"
                    value="{{ isset($info['mahasiswa_id']) ? $info['mahasiswa_id'] : 'diisi otomatis' }}" readonly />

                <div id="mahasiswa_select">
                    <div class="form-group required row mb-2">
                        <label class="col-sm-3 control-label col-form-label">Mahasiswa 1</label>
                        <div class="col-sm-9">
                            <select data-testid="partner-category"
                                class="form-control select2_combobox form-control-sm mahasiswa-1" id="mahasiswa_1"
                                name="mahasiswa[]" readonly disabled>
                                <option value="" selected>Pilih Mahasiswa 1</option>
                                @foreach ($info['mahasiswas'] as $item)
                                    <option value="{{ $item->mahasiswa_id }}"
                                        {{ $item->mahasiswa_id == $info['mahasiswa_id'] ? 'selected' : '' }}>
                                        {{ $item->nim }} -
                                        {{ $item->nama_mahasiswa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group required row mb-2">
                        <label class="col-sm-3 control-label col-form-label">Mahasiswa 2</label>
                        <div class="col-sm-9">
                            {{-- <select data-testid="partner-category"
                                class="form-control select2_combobox form-control-sm mahasiswa-2" id="mahasiswa"
                                name="mahasiswa[]">
                                <option value="">Pilih Mahasiswa 1</option>
                                @foreach ($info['mahasiswas'] as $item)
                                    <option value="{{ $item->mahasiswa_id }}"
                                        {{ $item->mahasiswa_id == $info['mahasiswa_id'] ? 'selected' : '' }}>
                                        {{ $item->nim }} -
                                        {{ $item->nama_mahasiswa }}</option>
                                @endforeach
                            </select> --}}
                            <select data-testid="partner-category"
                                class=" select2_combobox form-control form-control-sm" id="mahasiswa_2"
                                name="mahasiswa[]">
                                <option value="" selected>Pilih Mahasiswa 2</option>
                                @foreach ($info['mahasiswas'] as $item)
                                    <option value="{{ $item->mahasiswa_id }}">
                                        {{ $item->nim }} -
                                        {{ $item->nama_mahasiswa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group required row mb-2">
                        <label class="col-sm-3 control-label col-form-label">Mahasiswa 3</label>
                        <div class="col-sm-9">
                            <select data-testid="partner-category" class="form-control select2_combobox form-control-sm"
                                id="mahasiswa_3" name="mahasiswa[]">
                                <option value="">Pilih Mahasiswa 3</option>
                                @foreach ($info['mahasiswas'] as $item)
                                    <option value="{{ $item->mahasiswa_id }}">
                                        {{ $item->nim }} -
                                        {{ $item->nama_mahasiswa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Daftar</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        unblockUI();

        //hide mahasiswa select
        $('#mahasiswa_select').hide();

        let selected_mahasiswa = [];

        //show mahasiswa select if tipe_pendaftar is 0
        $('#tipe_pendaftar').on('change', function() {
            var value = $(this).val()
            if (value == 0) {
                $('#mahasiswa_select').show();
            } else {
                $('#mahasiswa_select').hide();
            }
        })


        // $('.mahasiswa').select2();
        $('.select2_combobox').select2();
        // $('.mahasiswa-3').select2();

        $('#tipe_kegiatan_id').on('change', function() {
            var value = $(this).val()
            if (value == 1) {
                $('#jenis_magang_form').removeClass('d-none');
            } else {
                $('#jenis_magang_form').addClass('d-none');
            }
        })

        $("#form-master").validate({
            rules: {
                tipe_kegiatan_id: {
                    required: true,
                },
                periode_id: {
                    required: true
                },
                posisi_lowongan: {
                    required: true
                },
                deskripsi: {
                    required: true
                },
                kuota: {
                    required: true,
                    number: true,
                    //must not be zero
                    min: 1
                },
                mulai_kegiatan: {
                    required: true
                },
                akhir_kegiatan: {
                    required: true
                }
            },
            submitHandler: function(form) {
                $('.form-message').html('');
                blockUI(form);
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(data) {
                        unblockUI(form);
                        setFormMessage('.form-message', data);
                        if (data.stat) {
                            resetForm('#form-master');
                            dataMaster.draw(false);
                        }
                        closeModal($modal, data);
                    }
                });
            },
            validClass: "valid-feedback",
            errorElement: "div",
            errorClass: 'invalid-feedback',
            errorPlacement: erp,
            highlight: hl,
            unhighlight: uhl,
            success: sc
        });
    });
</script>
