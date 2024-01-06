<form method="post" action="<?php echo $url?>" role="form" class="form-horizontal" id="user-form" width="80%">
<div id="modal-user" class="modal-dialog modal-md" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><?php echo $title?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="form-message text-center"></div>
			<div class="form-group row mb-1">
				<label for="kode" class="col-sm-2 col-form-label">Kode</label>
				<div class="col-sm-10">
					<input type="text" class="form-control form-control-sm" id="kode" placeholder="Kode" name="kode" <?php echo isset($data->kode)? 'value="'.$data->kode.'" ' : ''?> />
				</div>
			</div>
			<div class="form-group row mb-1">
				<label for="nama" class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-10">
					<input type="text" class="form-control form-control-sm" id="nama" placeholder="Nama" name="nama" value="<?php echo isset($data->nama)? $data->nama : ''?>"/>
				</div>
			</div>
			<div class="form-group row mb-1">
				<label for="url" class="col-sm-2 col-form-label">URL</label>
				<div class="col-sm-10">
					<input type="text" class="form-control form-control-sm" id="url" placeholder="" name="url" value="<?php echo isset($data->url)? $data->url : ''?>"/>
				</div>
			</div>
			<div class="form-group row mb-1">
				<label class="col-sm-2 col-form-label">Level</label>
				<div class="col-sm-4">
					<input type="text" class="form-control form-control-sm" id="level" placeholder="Level" name="level" value="<?php echo isset($data->level)? $data->level : ''?>"/>
				</div>
				<label class="col-sm-2 col-form-label">Urutan</label>
				<div class="col-sm-4">
					<input type="text" class="form-control form-control-sm" id="urutan" placeholder="Urutan" name="urutan" value="<?php echo isset($data->urutan)? $data->urutan : ''?>"/>
				</div>
			</div>
			<div class="form-group row mb-1">
				<label class="col-sm-2 col-form-label">Class</label>
				<div class="col-sm-4">
					<input type="text" class="form-control form-control-sm" id="class" placeholder="Class" name="class" value="<?php echo isset($data->class)? $data->class : ''?>"/>
				</div>
				<label class="col-sm-2 col-form-label">Icon</label>
				<div class="col-sm-4">
					<input type="text" class="form-control form-control-sm" id="icon" placeholder="Icon" name="icon" value="<?php echo isset($data->icon)? $data->icon : ''?>"/>
				</div>
			</div>
			<div class="form-group row mb-1">	
				<label for="group" class="col-sm-2 col-form-label">Parent</label>
				<div class="col-sm-10">
					<select id="parent" name="parent" class="form-control form-control-sm parent" style="width: 100%;">
						<option value="">- Pilih -</option>
						<?php 
							foreach($menu as $p){
								echo '<option value="'.$p->menu_id.'">'.$p->level.' - '.$p->nama.'  ('.$p->kode.')</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row mb-1">
				<label for="Status" class="col-sm-2 col-form-label">Status</label>
				<div class="col-sm-10 mt-1">
					<div class="icheck-primary d-inline mr-2">
						<input type="radio" id="radioPrimary1" name="is_aktif" value="1" <?php echo isset($data->is_aktif)? (($data->is_aktif == 1)? 'checked' : '') : '' ?>>
							<label for="radioPrimary1">Aktif </label>
					</div>
					<div class="icheck-danger d-inline">
						<input type="radio" id="radioPrimary2" name="is_aktif" value="0" <?php echo isset($data->is_aktif)? (($data->is_aktif == 0)? 'checked' : '') : 'checked' ?>>
						<label for="radioPrimary2">Non-aktif</label>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script>
	$(document).ready(function(){
 //unblockUI();

		$('.parent').select2();

		<?php if(isset($data->parent)) echo '$(".parent").val("'.$data->parent.'").trigger("change");'?>
		<?php if(isset($data->kode)) echo "setSandStr('#kode',2,'-',3);"?>
		$("#user-form").validate({
			rules: {
				<?php if(!isset($data->kode)):?>
				kode: {
					required: true,
					minlength: 3,
					maxlength: 20
				},
				<?php endif;?>
				nama: {
					required: true,
					minlength: 5,
					maxlength: 50
				},
				level: {
					required: true,
					digits: true
				},
				urutan: {
					required: true,
					digits: true
				},
				class: {
					required: true,
					minlength: 4,
					maxlength: 20
				},
				icon: {
					required: true,
					minlength: 5,
					maxlength: 50
				},
				is_aktif: {
					required: true
				}
			},
			submitHandler: function(form) {
				$('.form-message').html('');
				blockUI(form);
				$(form).ajaxSubmit({
					dataType:  'json',
					data: {<?php echo $page->tokenName ?> : $('meta[name=<?php echo $page->tokenName ?>]').attr("content")},
					success: function(data){
						unblockUI(form);
						setFormMessage('.form-message', data);
						if(data.stat){
							resetForm('#user-form', '.level_filter, .parent_filter');
							setSandStr('#kode',2,'-',3);
							dataTable.draw();
						}
						closeModal($modal, data);
					}
				});
			},
			validClass: "valid-feedback",
			errorElement: "div", // contain the error msg in a small tag
			errorClass: 'invalid-feedback',
			errorPlacement: erp,
			highlight: hl,
			unhighlight: uhl,
			success: sc
		});
	});
</script>