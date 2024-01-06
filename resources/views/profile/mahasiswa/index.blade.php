@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <form method="post" action="{{ $page->url }}" role="form" class="form-horizontal" id="form-profile">
        @csrf
        @method('PUT')

    <div class="row">
        <section class="col-lg-12">
            <div class="card card-outline card-{{ $theme->card_outline }}">
                <div class="card-header">
                    <h3 class="card-title mt-1">
                        <i class="fas fa-angle-double-right text-md text-{{ $theme->card_outline }} mr-1"></i>
                        Profile Mahasiswa
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="form-message-profile text-center"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label">Jurusan</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static mt-2">{{ $mhs->jurusan_name }} &nbsp; ({{ $mhs->jurusan_code }})</p>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-sm-3 col-form-label">Prodi</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static mt-2">{{ $mhs->prodi_name }} &nbsp; ({{ $mhs->prodi_code }})</p>
                                </div>
                            </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Username/NIM</label>
                            <div class="col-sm-9">
                                <p class="form-control-static mt-2">{{ $mhs->mahasiswa_nim }}</p>
                            </div>
                        </div>
                         <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="mahasiswa_name" value="{{ $mhs->mahasiswa_name }}">
                            </div>
                         </div>

                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="mahasiswa_email" value="{{ $mhs->mahasiswa_email }}">
                                <small class="form-text text-muted">Masukkan alamat email. Untuk menggunakan SSO, masukkan alamat Email Polinema</small>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">HP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="mahasiswa_phone" value="{{ $mhs->mahasiswa_phone }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">JK</label>
                            <div class="col-sm-9 mt-2">
                                <div class="icheck-{{ $theme->button }} d-inline mr-2">
                                    <input type="radio" id="radioAktif" name="mahasiswa_gender" value="P" <?php echo isset($mhs->mahasiswa_gender)? (($mhs->mahasiswa_gender == 'P')? 'checked' : '') : '' ?>>
                                    <label for="radioAktif">Perempuan </label>
                                </div>
                                <div class="icheck-warning d-inline">
                                    <input type="radio" id="radioNonAktif" name="mahasiswa_gender" value="L" <?php echo isset($mhs->mahasiswa_gender)? (($mhs->mahasiswa_gender == 'L')? 'checked' : '') : 'checked' ?>>
                                    <label for="radioNonAktif">Laki-laki</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Tahun Masuk</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="mahasiswa_tahun" value="{{ $mhs->mahasiswa_tahun }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="kelas" value="{{ $mhs->kelas }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Nama Orang Tua</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="ortu_nama" value="{{ $mhs->ortu_nama }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">HP Orang Tua</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="ortu_hp" value="{{ $mhs->ortu_hp }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Akun FB</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="https://facebook.com/username" class="form-control form-control-sm" name="url_fb" value="{{ $mhs->url_fb }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Akun Instagram</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="https://instagram.com/username" class="form-control form-control-sm" name="url_instagram" value="{{ $mhs->url_instagram }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Akun Twitter</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="https://twitter.com/username" class="form-control form-control-sm" name="url_twitter" value="{{ $mhs->url_twitter }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-sm-3 col-form-label">Akun Linkedin</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="https://linkedin.com/in/username"class="form-control form-control-sm" name="url_linkedin" value="{{ $mhs->url_linkedin }}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-{{ $theme->button }}">Simpan Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </div>
    </form>
</div>
@endsection

@push('content-js')
<script>
   $(document).ready(function() {

      $("#form-profile").validate({
         rules: {
             mahasiswa_name:{required: true, maxlength: 50},
             mahasiswa_email: {required: true, email: true, maxlength: 50},
             mahasiswa_phone: {required: true, number: true, minlength: 8, maxlength: 15},
             mahasiswa_gender: {required: true},
             mahasiswa_tahun: {required: true, min: 2019, max:2050},
             kelas:{required: true, maxlength: 5},
             ortu_nama:{required: true, maxlength: 50},
             ortu_hp: {required: true, number: true, minlength: 8, maxlength: 15},
             url_fb: {required: true, maxlength: 255, url: true},
             url_instagram: {required: true, maxlength: 255, url: true},
             url_twitter: {required: true, maxlength: 255, url: true},
             url_linkedin: {required: true, maxlength: 255, url: true},
         },
         submitHandler: function(form) {
            $('.form-message-profile').html('');
            $(form).ajaxSubmit({
               dataType: 'json',
               success: function(data) {
                  setFormMessage('.form-message-profile', data);
                  if (data.stat) {
                      setTimeout(function(){
                          location.reload();
                      }, 2000);
                  }
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
@endpush
