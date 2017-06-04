@extends($layout)
@section('content-header')
    <h1>Data Dosen<small>pendidik berkualitas</small></h1>
@endsection
@section('content')
    <section id="master-mahasiswa">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <form id="dt-toolbar" class="form-inline" role="form">
                            <a data-toggle="modal" data-target="#modal-util" data-ic-target="#modal-util-body"
                               data-ic-get-from="{{ route('master.dosen.create') }}" title="Tambah Dosen"
                               class="btn btn-default form-control"><i class="fa fa-plus-circle"></i> Tambah</a>&nbsp;
                            <label for="ta">Filter:&nbsp;</label>
                            <select class="form-control" id="jurusan">
                                {!! load_select('ta', \Stmik\Factories\JurusanFactory::getJurusanLists(),
                                    0, [], ['Pilih Jurusan'], true) !!}
                            </select>
                            <a onclick="MasterMhs.sendFilter(event)" id="send-filter" class="form-control btn btn-sm btn-warning"><i class="fa fa-exchange"></i> </a>
                        </form>
                        <table id="master-mhs" class="table"
                               data-toolbar="#dt-toolbar"
                               data-url="{{ route('master.dosen.getDT') }}"
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
                                <th data-field="nomor_induk" data-sortable="true" data-visible="true">Nomor Induk</th>
                                <th data-field="nama" data-sortable="true">Nama</th>
                                <th data-field="hp" data-sortable="false">HP</th>
                                <th data-field="nidn" data-sortable="true">Nidn</th>
                                <th data-field="jenis_kelamin" data-sortable="true">Jenis Kelamin</th>
                                <th data-field="status" data-sortable="true">Status</th>
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
                'title="Edit Dosen Ini" data-ic-get-from="http://localhost/siakad/public/master/dosen/edit/'+ row['nomor_induk'] +'"'+ 
                ' class="btn btn-xs bg-blue"><i class="fa fa-edit"></i></a>',
                '<a data-toggle="modal" data-target="#modal-util" data-ic-target="#modal-util-body" ' +
                    'title="Set User Account" data-ic-get-from="http://localhost/siakad/public/user/setUserUntuk/' + row['nomor_induk'] + '/dosen" '+
                    ' class="btn btn-xs bg-red"><i class="fa fa-user"></i></a>',
                '<a title="Hapus Data Dosen Ini" data-ic-delete-from="http://localhost/siakad/public/master/dosen/delete/' + row['nomor_induk'] + '"' +
                    ' data-ic-target="closest tr" data-ic-confirm="Yakin menghapus data dosen ini?" ' +
                    ' class="btn btn-xs bg-red-active"><i class="fa fa-trash"></i></a>'
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