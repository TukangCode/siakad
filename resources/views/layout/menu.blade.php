<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        {{--<div class="user-panel">--}}
            {{--<div class="pull-left image">--}}
                {{--<img src="{{ asset('lte/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">--}}
            {{--</div>--}}
            {{--<div class="pull-left info">--}}
                {{--<p>{{ Auth::user()->name }}</p>--}}
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            {{--</div>--}}
        {{--</div>--}}
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu"
            {{--data-ic-indicator="#indic-loader" data-ic-target="div#the-content" data-ic-push-url="true"--}}
        >
            <li class="header">NAVIGASI UTAMA</li>
            <li class="active">
                <a href="{{ route('home') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-star-half-full"></i>
                    <span>Master</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Dosen</a></li>
                    <li><a href="{{ route('master.mahasiswa') }}"><i class="fa fa-circle-o"></i> Mahasiswa</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Pegawai</a></li>
                    <li><a href="{{ route('master.mk') }}"><i class="fa fa-circle-o"></i> Mata Kuliah</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> User</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Mahasiswa</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('mhs.dataDiri') }}"><i class="fa fa-circle-o"></i> Data Diri</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Hasil Studi</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Isi FRS</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-university"></i>
                    <span>AKMA</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('akma.spp') }}"><i class="fa fa-circle-o"></i> Status SPP</a></li>
                    <li><a href="{{ route('akma.dkmk') }}"><i class="fa fa-circle-o"></i> Kelas & Dosen</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

