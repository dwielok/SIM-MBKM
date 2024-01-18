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
                    <label class="col-sm-3 control-label col-form-label">Nama Perusahaan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="nama_perusahaan"
                            name="nama_perusahaan"
                            value="{{ isset($data->nama_perusahaan) ? $data->nama_perusahaan : '' }}" />
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="email" name="email"
                            value="{{ isset($data->email) ? $data->email : '' }}" />
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Kategori</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm" id="kategori"
                            name="kategori" value="{{ isset($data->kategori) ? $data->kategori : '' }}">
                            <option disabled selected value="">Pilih opsi yang sesuai</option>
                            <option value="BUMN"
                                {{ isset($data->kategori) ? ($data->kategori == 'BUMN' ? 'selected' : '') : '' }}>BUMN
                            </option>
                            <option value="Perusahaan Terbuka"
                                {{ isset($data->kategori) ? ($data->kategori == 'Perusahaan Terbuka' ? 'selected' : '') : '' }}>
                                Perusahaan Terbuka</option>
                            <option value="UMKM"
                                {{ isset($data->kategori) ? ($data->kategori == 'UMKM' ? 'selected' : '') : '' }}>UMKM
                            </option>
                            <option value="Perusahaan Multinasional"
                                {{ isset($data->kategori) ? ($data->kategori == 'Perusahaan Multinasional' ? 'selected' : '') : '' }}>
                                Perusahaan Multinasional</option>
                            <option value="Yayasan / Non-profit"
                                {{ isset($data->kategori) ? ($data->kategori == 'Yayasan / Non-profit' ? 'selected' : '') : '' }}>
                                Yayasan / Non-profit</option>
                            <option value="Perusahaan Tertutup/Private"
                                {{ isset($data->kategori) ? ($data->kategori == 'Perusahaan Tertutup/Private' ? 'selected' : '') : '' }}>
                                Perusahaan Tertutup/Private</option>
                            <option value="Instansi Pemerintahan"
                                {{ isset($data->kategori) ? ($data->kategori == 'Instansi Pemerintahan' ? 'selected' : '') : '' }}>
                                Instansi Pemerintahan</option>
                            <option value="Instansi Multilalteral / Internasional"
                                {{ isset($data->kategori) ? ($data->kategori == 'Instansi Multilalteral / Internasional' ? 'selected' : '') : '' }}>
                                Instansi Multilalteral / Internasional</option>
                            <option value="Perguruan Tinggi atau Satuan Pendidikan lainnya"
                                {{ isset($data->kategori) ? ($data->kategori == 'Perguruan Tinggi atau Satuan Pendidikan lainnya' ? 'selected' : '') : '' }}>
                                Perguruan Tinggi atau Satuan Pendidikan lainnya</option>
                            <option value="Lainnya"
                                {{ isset($data->kategori) ? ($data->kategori == 'Lainnya' ? 'selected' : '') : '' }}>
                                Lainnya</option>
                        </select>

                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Tipe Industri</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm" id="tipe_industri"
                            name="tipe_industri">
                            <option disabled selected value="">Pilih opsi yang sesuai</option>
                            <option value="Energi dan Pertambangan"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Energi dan Pertambangan' ? 'selected' : '' }}>
                                Energi dan Pertambangan</option>
                            <option value="Basic Material (Industri Kimia, Kertas, Kayu, dll)"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Basic Material (Industri Kimia, Kertas, Kayu, dll)' ? 'selected' : '' }}>
                                Basic Material (Industri Kimia, Kertas, Kayu, dll)</option>
                            <option value="Jasa Industrial"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Jasa Industrial' ? 'selected' : '' }}>
                                Jasa Industrial</option>
                            <option value="Makanan dan Minuman"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Makanan dan Minuman' ? 'selected' : '' }}>
                                Makanan dan Minuman</option>
                            <option value="Pertanian dan Perkebunan"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Pertanian dan Perkebunan' ? 'selected' : '' }}>
                                Pertanian dan Perkebunan</option>
                            <option value="FMCG"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'FMCG' ? 'selected' : '' }}>
                                FMCG</option>
                            <option value="Otomotif"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Otomotif' ? 'selected' : '' }}>
                                Otomotif</option>
                            <option value="Ritel"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Ritel' ? 'selected' : '' }}>
                                Ritel</option>
                            <option value="Pariwisata"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Pariwisata' ? 'selected' : '' }}>
                                Pariwisata</option>
                            <option value="Media"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Media' ? 'selected' : '' }}>
                                Media</option>
                            <option value="Edukasi"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Edukasi' ? 'selected' : '' }}>
                                Edukasi</option>
                            <option value="Hiburan dan Entertainment"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Hiburan dan Entertainment' ? 'selected' : '' }}>
                                Hiburan dan Entertainment</option>
                            <option value="Kesehatan"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Kesehatan' ? 'selected' : '' }}>
                                Kesehatan</option>
                            <option value="Perbankan dan Jasa Keuangan"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Perbankan dan Jasa Keuangan' ? 'selected' : '' }}>
                                Perbankan dan Jasa Keuangan</option>
                            <option value="Real Estate"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Real Estate' ? 'selected' : '' }}>
                                Real Estate</option>
                            <option value="Teknologi Informasi"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Teknologi Informasi' ? 'selected' : '' }}>
                                Teknologi Informasi</option>
                            <option value="Infrastruktur (Telekomunikasi, Konstruksi, Alat Berat, dll)"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Infrastruktur (Telekomunikasi, Konstruksi, Alat Berat, dll)' ? 'selected' : '' }}>
                                Infrastruktur (Telekomunikasi, Konstruksi, Alat Berat, dll)</option>
                            <option value="Logistik dan Transportasi"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Logistik dan Transportasi' ? 'selected' : '' }}>
                                Logistik dan Transportasi</option>
                            <option value="Lainnya"
                                {{ isset($data->tipe_industri) && $data->tipe_industri == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>

                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Alamat</label>
                    <div class="col-sm-9">
                        <textarea type="text" class="form-control form-control-sm" id="alamat" name="alamat">{{ isset($data->alamat) ? $data->alamat : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Provinsi</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm" id="provinsi_id"
                            name="provinsi_id">
                            <option disabled selected value="">Pilih provinsi</option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}"
                                    {{ isset($data->provinsi_id) && $data->provinsi_id == $provinsi->id ? 'selected' : '' }}>
                                    {{ $provinsi->nama_provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Kabupaten/Kota</label>
                    <div class="col-sm-9">
                        <select data-testid="partner-category" class="form-control form-control-sm" id="kota_id"
                            name="kota_id">
                            <option disabled selected value="">Pilih kabupaten/kota</option>
                            @foreach ($kabupatens as $kabupaten)
                                <option value="{{ $kabupaten->id }}"
                                    {{ isset($data->kota_id) && $data->kota_id == $kabupaten->id ? 'selected' : '' }}>
                                    {{ $kabupaten->nama_kab_kota }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Profil Perusahaan</label>
                    <div class="col-sm-9">
                        <textarea type="text" class="form-control form-control-sm" id="profil_perusahaan" name="profil_perusahaan">{{ isset($data->profil_perusahaan) ? $data->profil_perusahaan : '' }}</textarea>
                    </div>
                </div>
                <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Website</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="website" name="website"
                            value="{{ isset($data->website) ? $data->website : '' }}" />
                    </div>
                </div>
                {{-- <div class="form-group required row mb-2">
                    <label class="col-sm-3 control-label col-form-label">Status</label>
                    <div class="col-sm-9 mt-2">
                        <div class="icheck-success d-inline mr-3">
                            <input type="radio" id="radioActive" name="is_active" value="1" <?php echo isset($data->is_active) ? ($data->is_active == 1 ? 'checked' : '') : 'checked'; ?>>
                            <label for="radioActive">Aktif </label>
                        </div>
                        <div class="icheck-danger d-inline mr-3">
                            <input type="radio" id="radioFailed" name="is_active" value="0" <?php echo isset($data->is_active) ? ($data->is_active == 0 ? 'checked' : '') : ''; ?>>
                            <label for="radioFailed">non-aktif</label>
                        </div>
                    </div>
                </div> --}}
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

        $('#provinsi_id').change(function() {
            var provinsi_id = $(this).val();
            if (provinsi_id) {
                $.ajax({
                    url: "{{ url('kota') }}?provinsi_id=" + provinsi_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#kota_id').empty();
                        $('#kota_id').append(
                            '<option disabled selected value="">Pilih kabupaten/kota</option>'
                            );
                        $.each(data, function(key, value) {
                            $('#kota_id').append('<option value="' + value.id +
                                '">' + value.nama_kab_kota +
                                '</option>');
                        });
                    }
                });
            } else {
                $('#kota_id').empty();
                $('#kota_id').append(
                '<option disabled selected value="">Pilih kabupaten/kota</option>');
            }
        });

        $("#form-master").validate({
            rules: {
                nama_perusahaan: {
                    required: true,
                },
                kategori: {
                    required: true
                },
                tipe_industri: {
                    required: true
                },
                alamat: {
                    required: true
                },
                provinsi_id: {
                    required: true
                },
                kota_id: {
                    required: true
                },
                profil_perusahaan: {
                    required: true
                },
                website: {
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
