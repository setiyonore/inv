<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
{{--                <img src="https://i.pravatar.cc/300" class="img-circle elevation-2" alt="">--}}
            </div>
            <div class="info">
                @if (Auth::check())
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                @else
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @can('dashboard')
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="{{ request()->route()->named('home') ? 'nav-link active' : 'nav-link'}}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <p>Dashboard </p>
                    </a>
                </li>
                @endcan
                @can('pelanggan')
                <li class="nav-item">
                    <a href="{{ url('customers') }}" class="{{ request()->route()->named('customers.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-address-book nav-icon"></i>
                        <p>Pelanggan</p>
                    </a>
                </li>
                @endcan
                @can('karyawan')
                <li class="nav-item">
                    <a href="{{ url('employees') }}" class="{{ request()->route()->named('employees.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                @endcan
                @can('paket')
                <li class="nav-item">
                    <a href="{{url('package')}}" class="{{ request()->route()->named('package.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-box-open nav-icon"></i>
                        <p>Paket</p>
                    </a>
                </li>
                @endcan
                @can('transaksi')
                <li class="nav-item">
                    <a href="{{url('transaction')}}" class="{{ request()->route()->named('transaction.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-dolly-flatbed nav-icon"></i>
                        <p>Transaksi</p>
                    </a>
                </li>
                @endcan
                @can('laporan')
                <li class="nav-item">
                    <a href="{{url('report')}}" class="{{ request()->route()->named('report.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                @endcan
                @can('tugas-saya')
                <li class="nav-item">
                    <a href="{{url('task')}}" class="{{ request()->route()->named('task.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-tasks nav-icon"></i>
                        <p>Tugas Saya</p>
                    </a>
                </li>
                @endcan
                @can('jenis-referensi')
                <li class="nav-item">
                    <a href="{{ url('typereferences') }}" class="{{ request()->route()->named('typereferences.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-list-ul nav-icon"></i>
                        <p>Jenis Referensi</p>
                    </a>
                </li>
                @endcan
                @can('referensi')
                <li class="nav-item">
                    <a href="{{ url('references') }}" class="{{ request()->route()->named('references.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-list-ul nav-icon"></i>
                        <p>Referensi</p>
                    </a>
                </li>
                @endcan
                @can('no-rekening')
                <li class="nav-item">
                    <a href="{{url('norek')}}" class="{{ request()->route()->named('norek.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-money-check-alt nav-icon"></i>
                        <p>No Rekening</p>
                    </a>
                </li>
                @endcan
                @can('roles')
                <li class="nav-item">
                    <a href="{{url('roles')}}" class="{{ request()->route()->named('roles.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-user-tag nav-icon"></i>
                        <p>Roles</p>
                    </a>
                </li>
                @endcan
                @can('user')
                <li class="nav-item">
                    <a href="{{url('users')}}" class="{{ request()->route()->named('users.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Users</p>
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
