<!-- Nav -->
<nav>
    <ul id="ownmenu" class="ownmenu">
        <li class="active"><a href="{{ url('/') }}">HOME</a></li>
        <li><a href="#potensi_desa"> Potensi Desa </a></li>
        <li><a href="#badan_usaha"> Badan Usaha </a></li>
        <li><a href="#keuangan"> Keuangan </a></li>
        @if (auth()->user())
            <li><a href="{{ route('dashboard') }}"> Dashboard </a></li>
        @else
            <li><a href="{{ route('login') }}"> Login </a></li>
        @endif
    </ul>
</nav>
