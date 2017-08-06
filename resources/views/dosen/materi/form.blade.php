<form id="frmMMhs" data-ic-post-to="{{ $action }}" enctype="multipart/form-data"
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
        <label for="nama_materi" class="col-md-3 control-label">Nama Materi</label>
        <div class="col-md-9">
            <input type="text" id="nama_materi" class="form-control" name="nama_materi"
                   value="{{ load_input_value($data, "nama_materi") }}" maxlength="100">
            <div id="error-nama_materi" class="error"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="filename" class="col-md-3 control-label">File</label>
        <div class="col-md-9">
            <input type="file" id="filename" name="filename">
            <div id="error-filename" class="error"></div>
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