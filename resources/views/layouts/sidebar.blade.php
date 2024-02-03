<div>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="{{ url('logo.png') }}" width="100" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Bumdes Errabu</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <ion-icon class="nav-icon" name="home"></ion-icon>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    @if (auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('operator.index') }}"
                            class="nav-link {{ request()->routeIs('operator.*') ? 'active' : '' }}">
                            <ion-icon class="nav-icon" name="person-circle"></ion-icon>
                            <p>
                                Operator
                            </p>
                        </a>
                    </li>
                        <li class="nav-item">
                            <a href="{{ route('badan_usaha.index') }}"
                                class="nav-link {{ request()->routeIs('badan_usaha.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="briefcase"></ion-icon>
                                <p>
                                    Badan Usaha
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('spending.index') }}"
                                class="nav-link {{ request()->routeIs('spending.*') ? 'active' : '' }}">
                               <ion-icon class="nav-icon" name="cash"></ion-icon>
                                <p>
                                    Dana Keluar
                                </p>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->role == 'operator')
                    <li class="nav-item">
                        <a href="{{ route('income.index') }}"
                            class="nav-link {{ request()->routeIs('income.*') ? 'active' : '' }}">
                            <ion-icon class="nav-icon" name="card"></ion-icon>
                            <p>
                                Dana Masuk
                            </p>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</div>
