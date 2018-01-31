@extends($layout)
@section('content-header')
    <h1>Data Jadwal<small>Perbaharui Jadwal Anda</small></h1>
@endsection
@section('content')
    <section id="master-mahasiswa">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <table id="master-mhs" class="table"
                               data-toolbar="#dt-toolbar"
                               data-url="{{ route('dosen.jadwal.getDT')}}"
                               data-pagination="true"
                               data-classes="table table-hover table-condensed"
                               data-striped="true"
                               data-side-pagination="server"
                               data-page-list="[5, 10, 20, 50, 100, 200]"
                               data-search="true"
                               data-show-toggle="true"
                               data-query-params="MasterMhs.addFilter"
                               data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th data-field="hari" data-sortable="true" data-visible="true">Hari</th>
								<th data-field="nama" data-sortable="true">Matakuliah</th>
								<th data-field="kelas" data-sortable="true">Kelas</th>
                                <th data-field="jam_masuk" data-sortable="true">Jam masuk</th>
                                <th data-field="jam_keluar" data-sortable="true">Jam keluar</th>
                                <th data-field="ruang" data-sortable="true">Ruangan</th>
                                <th data-width="100px" data-formatter="MasterMhs.loadAksi" data-events="eventAksi">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('late-script')
<script type="text/javascript">
    var MasterMhs = {
        init: function() {
            $spp = $('#master-mhs');
            $spp.bootstrapTable();
            $spp.on('load-success.bs.table', function(e,data){
                MasterMhs.attachIC();
            });
        },
        loadAksi: function(value, row, index) {
            return [
                '<a data-toggle="modal" data-target="#modal-util" data-ic-target="#modal-util-body" ' +
                'title="Edit Jadwal Ini" data-ic-get-from="http://localhost/siakad/public/dosen/jadwal/edit/' + row['id'] + '"' +
                ' class="btn btn-xs bg-blue"><i class="fa fa-edit"></i></a>'
            ].join('&nbsp;');
        },
        addFilter: function (p) {
            p.filter = {
                'jurusan': $('#jurusan').val(),
                'status': $('#status').val()
            };
            return p;
        },
        sendFilter: function (e) {
            $('#master-mhs').bootstrapTable('refresh');
        },
        onEditSuccess: function() {
            $('#master-mhs').bootstrapTable('refresh');
        },
        attachIC: function () {
            Intercooler.processNodes($('table#master-mhs tbody'));
        }
    };
    $(document).ready(function () {
        MasterMhs.init();
    });
</script>
@endpush