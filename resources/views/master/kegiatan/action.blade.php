<?php
// jika $data ada ISI-nya maka actionnya adalah edit, jika KOSONG : insert
$is_edit = isset($data);
?>

<form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-master">
    @csrf
    {!! $is_edit ? method_field('PUT') : '' !!}
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-message text-center"></div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Jenis Program</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm"
                            id="jenis_program_id" name="jenis_program_id"
                            value="{{ isset($data->jenis_program_id) ? $data->jenis_program_id : '' }}">
                            <option disabled selected value="">Pilih opsi</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->jenis_program_id }}"
                                    {{ isset($data->jenis_program_id) ? ($program->jenis_program_id == $data->jenis_program_id ? 'selected' : '') : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Jenis Magang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="nama_kegiatan"
                            name="nama_kegiatan"
                            value="{{ isset($data->nama_kegiatan) ? $data->nama_kegiatan : '' }}" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        unblockUI();

        $("#form-master").validate({
            rules: {
                nama_program: {
                    required: true,
                    maxlength: 100
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
