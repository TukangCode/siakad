<form id="frmMMhs" data-ic-post-to="{{ $action }}"
    class="form-horizontal" role="form" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="dosen_id" value="{{ Auth::user()->owner_id }}">
    @include('_the_alerter')
    <div class="form-group">
        <label for="perihal" class="col-md-3 control-label">Perihal</label>
        <div class="col-md-9">
            <input type="text" id="perihal" class="form-control" name="perihal"
                   value="{{ load_input_value($data, "perihal") }}">
            <div id="error-perihal" class="error"></div>
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