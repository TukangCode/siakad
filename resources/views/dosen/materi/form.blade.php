<form id="frmMMhs" data-ic-post-to="{{ $action }}"
    class="form-horizontal" role="form" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @include('_the_alerter')
    <div class="form-group">
        <label for="hari" class="col-md-3 control-label">Hari</label>
        <div class="col-md-9">
            {!! load_select_model('hari', ['senin'=>"senin", 'selasa'=>"selasa", 'rabu'=>"rabu",'kamis'=>"kamis",'jumat'=>"jumat",'sabtu'=>"sabtu"],
			$data, ['class'=>'form-control']) !!}
            <div id="error-jenis_kelamin" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="mata_kuliah_id" class="col-md-3 control-label">Matakuliah</label>
        <div class="col-md-9">
            {!! load_select_model('mata_kuliah_id', \Stmik\Factories\MatakuliahFactory::getMatakuliahLists(),
                $data, ['class'=>'form-control']) !!}
            <div id="error-mata_kuliah_id" class="error"></div>
        </div>
    </div>
    @if(is_null($data))
    <div class="form-group">
        <label for="nomor_induk" class="col-md-3 control-label">Nomor Induk Mahasiswa</label>
        <div class="col-md-9">
            <input type="text" id="nomor_induk" class="form-control" name="nomor_induk"
                   value="{{ load_input_value($data, "nomor_induk") }}" maxlength="12">
            <div id="error-nomor_induk" class="error"></div>
            <p class="help-block">Masukkan Nomor Induk Mahasiswa di sini.</p>
        </div>
    </div>
    @endif
    <div class="form-group">
        <label for="tgl_lahir" class="col-md-3 control-label">Jam Masuk</label>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" id="tgl_lahir" class="form-control col-md-4" name="tgl_lahir"
                       value="{{ load_input_value($data, "jam_masuk") }}">
                <span class="input-group-addon">HH:mm:ss</span>
            </div>
            <div id="error-tgl_lahir" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_lahir" class="col-md-3 control-label">Jam Keluar</label>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" id="tgl_lahir" class="form-control col-md-4" name="tgl_lahir"
                       value="{{ load_input_value($data, "jam_keluar") }}">
                <span class="input-group-addon">HH:mm:ss</span>
            </div>
            <div id="error-tgl_lahir" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="jurusan_id" class="col-md-3 control-label">Ruangan</label>
        <div class="col-md-9">
            {!! load_select_model('ruangan_id', \Stmik\Factories\RuanganFactory::getRuanganLists(),
                $data, ['class'=>'form-control']) !!}
            <div id="error-jurusan_id" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="submit" class="btn btn-primary">
                Simpan @include('_ic-indicator')
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
$('#frmMMhs').on('error.ic', function (evt, elt, stat, str, xhr) {
    $('#alerter-error, #alerter-success').hide();
    TSSTMIK.resetFormErrorMsg('#frmMMhs div.error');
    if(xhr.status==422){
        TSSTMIK.showFormErrorMsg(xhr.responseText);
    } else {
        $('#message-error').text(str).closest('div.form-group').show();
    }
});
@if(isset($success))
    $('#message-success').text("{{$success}}").closest('div.form-group').show();
    MasterMhs.onEditSuccess(); // trigger it!
@endif
</script>