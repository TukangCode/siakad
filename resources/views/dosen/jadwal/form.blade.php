<form id="frmMMhs" data-ic-post-to="{{ $action }}"
    class="form-horizontal" role="form" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @include('_the_alerter')
    <div class="form-group">
        <label for="hari" class="col-md-3 control-label">Hari</label>
        <div class="col-md-9">
            {!! load_select_model('hari', ['senin'=>"senin", 'selasa'=>"selasa", 'rabu'=>"rabu",'kamis'=>"kamis",'jumat'=>"jumat",'sabtu'=>"sabtu"],
			$data, ['class'=>'form-control']) !!}
            <div id="error-hari" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="pengampu_id" class="col-md-3 control-label">Matakuliah</label>
        <div class="col-md-9">
            {!! load_select_model('pengampu_id', \Stmik\Factories\MatakuliahFactory::getPengampuMatakuliahLists(),
                $data, ['class'=>'form-control']) !!}
            <div id="error-pengampu_id" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="jam_masuk" class="col-md-3 control-label">Jam Masuk</label>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" id="jam_masuk" class="form-control col-md-4" name="jam_masuk"
                       value="{{ load_input_value($data, "jam_masuk") }}">
                <span class="input-group-addon">HH:mm:ss</span>
            </div>
            <div id="error-jam_masuk" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="jam_keluar" class="col-md-3 control-label">Jam Keluar</label>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" id="jam_keluar" class="form-control col-md-4" name="jam_keluar"
                       value="{{ load_input_value($data, "jam_keluar") }}">
                <span class="input-group-addon">HH:mm:ss</span>
            </div>
            <div id="error-jam_keluar" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="ruangan_id" class="col-md-3 control-label">Ruangan</label>
        <div class="col-md-9">
            {!! load_select_model('ruangan_id', \Stmik\Factories\RuanganFactory::getRuanganLists(),
                $data, ['class'=>'form-control']) !!}
            <div id="error-ruangan_id" class="error"></div>
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