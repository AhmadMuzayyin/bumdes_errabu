<div>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="{{ url('logo.png') }}" width="100" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">Bumdes Errabu</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    @if (auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="home"></ion-icon>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                    @endif

                    {{-- Menu Simpan Pinjam --}}
                    @if (auth()->user()->role == 'operator simpan pinjam')
                        <li class="nav-item">
                            <a href="{{ route('simpan-pinjam.dashboard') }}"
                                class="nav-link {{ request()->routeIs('simpan-pinjam.dashboard') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="home"></ion-icon>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('nasabah.index') }}"
                                class="nav-link {{ request()->routeIs('nasabah.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="people"></ion-icon>
                                <p>
                                    Nasabah
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('simpanan.*') || request()->routeIs('pengambilan-simpanan.*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('simpanan.*') || request()->routeIs('pengambilan-simpanan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="wallet"></ion-icon>
                                <p>
                                    Simpanan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('simpanan.index') }}"
                                        class="nav-link {{ request()->routeIs('simpanan.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Simpanan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pengambilan-simpanan.index') }}"
                                        class="nav-link {{ request()->routeIs('pengambilan-simpanan.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengambilan Simpanan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('pinjaman.*') || request()->routeIs('pengembalian-pinjaman.*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->routeIs('pinjaman.*') || request()->routeIs('pengembalian-pinjaman.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="cash"></ion-icon>
                                <p>
                                    Pinjaman
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('pinjaman.index') }}"
                                        class="nav-link {{ request()->routeIs('pinjaman.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pinjaman</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pengembalian-pinjaman.index') }}"
                                        class="nav-link {{ request()->routeIs('pengembalian-pinjaman.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengembalian</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pemasukan.index') }}"
                                class="nav-link {{ request()->routeIs('pemasukan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-up-outline"></ion-icon>
                                <p>
                                    Dana Masuk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pengeluaran.index') }}"
                                class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-down"></ion-icon>
                                <p>
                                    Pengeluaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('neraca-simpanpinjam.index') }}"
                                class="nav-link {{ request()->routeIs('neraca-simpanpinjam.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="bar-chart-outline"></ion-icon>
                                <p>
                                    Neraca Keuangan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('setting-pinjaman.index') }}"
                                class="nav-link {{ request()->routeIs('setting-pinjaman.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="settings"></ion-icon>
                                <p>
                                    Setting Pinjaman
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.index') }}"
                                class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="document-text"></ion-icon>
                                <p>
                                    Laporan
                                </p>
                            </a>
                        </li>
                    @endif

                    {{-- Menu Operator Foto Copy --}}
                    @if (auth()->user()->role == 'operator foto copy')
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.dashboard') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.dashboard') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="home"></ion-icon>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.harga.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.harga.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="pricetag"></ion-icon>
                                <p>
                                    Harga
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.pembayaran.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.pembayaran.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="cash"></ion-icon>
                                <p>
                                    Pembayaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.pemasukan.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.pemasukan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-up"></ion-icon>
                                <p>
                                    Pemasukan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.pengeluaran.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.pengeluaran.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-down"></ion-icon>
                                <p>
                                    Pengeluaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.neraca-fotocopy.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.neraca-fotocopy.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="bar-chart-outline"></ion-icon>
                                <p>
                                    Neraca Keuangan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fotocopy.laporan.index') }}"
                                class="nav-link {{ request()->routeIs('fotocopy.laporan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="document-text"></ion-icon>
                                <p>
                                    Laporan
                                </p>
                            </a>
                        </li>
                    @endif

                    {{-- Menu Operator BRI Link --}}
                    @if (auth()->user()->role == 'operator brilink')
                        <li class="nav-item">
                            <a href="{{ route('brilink.dashboard') }}"
                                class="nav-link {{ request()->routeIs('brilink.dashboard') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="home"></ion-icon>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.dana-masuk.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.dana-masuk.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-up-outline"></ion-icon>
                                <p>
                                    Dana Masuk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.dana-keluar.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.dana-keluar.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="trending-down-outline"></ion-icon>
                                <p>
                                    Dana Keluar
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.neraca-bri.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.neraca-bri.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="bar-chart-outline"></ion-icon>
                                <p>
                                    Neraca Keuangan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.setor-tunai.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.setor-tunai.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="arrow-up"></ion-icon>
                                <p>
                                    Setor Tunai
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.tarik-tunai.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.tarik-tunai.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="arrow-down"></ion-icon>
                                <p>
                                    Tarik Tunai
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.bayar-tagihan-pln.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.bayar-tagihan-pln.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="flash"></ion-icon>
                                <p>
                                    Bayar Tagihan PLN
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('brilink.laporan.index') }}"
                                class="nav-link {{ request()->routeIs('brilink.laporan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="document-text"></ion-icon>
                                <p>
                                    Laporan
                                </p>
                            </a>
                        </li>
                    @endif

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
                            <a href="{{ route('income.index') }}"
                                class="nav-link {{ request()->routeIs('income.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="card"></ion-icon>
                                <p>
                                    Dana Masuk
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
                        <li class="nav-item">
                            <a href="{{ route('neraca-admin.index') }}"
                                class="nav-link {{ request()->routeIs('neraca-admin.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="bar-chart-outline"></ion-icon>
                                <p>
                                    Neraca Keuangan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.umum.index') }}"
                                class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                                <ion-icon class="nav-icon" name="document-text"></ion-icon>
                                <p>
                                    Laporan
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
