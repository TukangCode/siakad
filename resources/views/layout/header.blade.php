<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>s</b>TM</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ config('siakad.nama') }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
		
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
				<!-- banghaji -->
				@can('dataIniHanyaBisaDipakaiOleh', 'mahasiswa')
				<div class="pull-left visible-xs">
					<ul class="nav navbar-nav">
						<li><a href="{{ route('mhs.dataDiri') }}" title="Data Diri"><i class="fa fa-user"></i> </a></li>
						<li><a href="{{ route('mhs.hasilStudy') }}" title="Hasil Studi"><i class="fa fa-list-ol"></i></a></li>
						<li><a href="{{ route('mhs.frs') }}" title="FRS"><i class="fa fa-file-text"></i></a></li>
					</ul>
				</div>
				@endcan
				<!-- banghaji -->
				<li id="indic-loader" class="pull-left">
                    <a><i class="fa fa-spin fa-spinner"></i> Loading </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('no-user-profile.jpg') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Session::get('username') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ asset('no-user-profile.jpg') }}" class="img-circle"
                                 alt="User Image">
                            <p>
                                {{ Session::get('nama') }}
                                <small>{{ Session::get('type') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" data-toggle="modal" data-target="#modal-util" data-ic-target="#modal-util-body"
                                   data-ic-get-from="{{ url('/user/profile') }}" title="Ubah Akun"
                                   class="btn btn-default btn-flat">
                                    <i class="fa fa-user"></i>
                                    Akun</a>
                                @can('dataIniHanyaBisaDipakaiOleh', 'mahasiswa')
                                <a href="{{ route('mhs.dataDiri') }}" title="Ubah Data Diri"
                                   class="btn btn-default btn-flat">
                                    <i class="fa fa-user"></i>
                                    Profil</a>
                                @endcan
                            </div>
                            <div class="pull-right">
                                <a onclick="return confirm('Yakin untuk logout?');"
                                   href="{{ url('/logout') }}" class="btn btn-default btn-flat">
                                    <i class="fa fa-sign-out"></i>
                                    Keluar</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>