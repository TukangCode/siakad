<form id="frmMMhs" data-ic-post-to="{{ $action }}"
    class="form-horizontal" role="form" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @include('_the_alerter')
    <div class="form-group">
        <label for="pengampu_id" class="col-md-3 control-label">Matakuliah</label>
        <div class="col-md-9">
            {!! load_select_model('pengampu_id', \Stmik\Factories\MatakuliahFactory::getPengampuMatakuliahLists(),
                $data, ['class'=>'form-control']) !!}
            <div id="error-pengampu_id" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="tugas" class="col-md-3 control-label">Tugas</label>
        <div class="col-md-9">
            <input type="text" id="tugas" class="form-control" name="nama_tugas"
                   value="{{ load_input_value($data, "nama_tugas") }}" maxlength="100">
            <div id="error-tugas" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="keterangan" class="col-md-3 control-label">Keterangan</label>
        <div class="col-md-9">
            <textarea id="keterangan" class="form-control" rows="10" name="keterangan">{{ load_input_value($data, "keterangan") }}</textarea>
            <div id="error-keterangan" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="deadline" class="col-md-3 control-label">Deadline</label>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" id="deadline" class="form-control col-md-4" name="deadline"
                       value="{{ load_input_value($data, "deadline") }}">
                <span class="input-group-addon">yyyy-mm-dd</span>
            </div>
            <div id="error-deadline" class="error"></div>
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