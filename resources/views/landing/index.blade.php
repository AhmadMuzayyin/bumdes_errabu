<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUMDES ERRABU</title>
    <meta name="keywords" content="Bumdes Errabu">
    <meta name="description" content="Bumdes Errabu Bluto Sumenep">
    <meta name="author" content="Pendi">

    <link rel="shortcut icon" href="{{ url('logo.png') }}"
    type="image/x-icon">
    <!-- FONTS ONLINE -->
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:500,600,700,100,800,900,400,200,300' rel='stylesheet'
        type='text/css'>
    <link
        href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,800,700italic'
        rel='stylesheet' type='text/css'>

    <!--MAIN STYLE-->
    <link href="{{ url('landing/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('landing/css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('landing/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('landing/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('landing/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="{{ url('landing/rs-plugin/css/settings.css') }}" media="screen" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body>

    <!-- Page Wrap -->
    <div id="wrap">

        <!-- Header -->
        <header>
            <!-- Top Bar -->
            <div class="container">
                <div class="top-bar">
                    <div class="open-time">
                        <p><i class="ion-ios-clock-outline"></i> Badan Usaha Milik Desa (BUMDes) Desa Errabu Kec. Bluto
                            Kab. Sumenep</p>
                    </div>
                    <div class="call">
                        <p><i class="ion-headphone"></i> 1800 123 4659</p>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="container">
                <div class="logo"> <a href="#."><img
                            src="{{ url('logo.png') }}"
                            width="70" alt=""></a> </div>

                @include('landing.navbar')
            </div>
        </header>
        <!-- Header End -->

        <!--======= HOME MAIN SLIDER =========-->
        <section class="home-slider">
            <div class="tp-banner-container">
                <div class="tp-banner">
                    <ul>

                        <!-- Slider 1 -->
                        <li data-transition="fade" data-slotamount="7"> <img
                                src="{{ url('landing/images/slides/slide-1.jpg') }}" data-bgposition="center center"
                                alt="" />

                            <!-- Layer -->
                            <div class="tp-caption sft font-montserrat tp-resizeme" data-x="center" data-hoffset="0"
                                data-y="center" data-voffset="-100" data-speed="700" data-start="1000"
                                data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 60px; text-transform: uppercase; font-weight:bolder; letter-spacing:3px;">
                                Desa Errabu </div>

                            <!-- Layer -->
                            <div class="tp-caption sfb  font-montserrat text-center tp-resizeme" data-x="center"
                                data-hoffset="0" data-y="center" data-voffset="-20" data-speed="700" data-start="1500"
                                data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 18px; font-weight:100; text-transform: uppercase;"> Desa
                                indah dengan pemandangan alam yang asri. </div>
                        </li>

                        <!-- Slider 2 -->
                        <li data-transition="fade" data-slotamount="7"> <img
                                src="{{ url('landing/images/slides/slide-2.jpg') }}" data-bgposition="center center"
                                alt="" />

                            <!-- Layer -->
                            <div class="tp-caption sft font-montserrat tp-resizeme" data-x="center" data-hoffset="0"
                                data-y="center" data-voffset="-100" data-speed="700" data-start="1000"
                                data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 60px; text-transform: uppercase; font-weight:bolder; letter-spacing:3px;">
                                BUMDES ERRABU </div>

                            <!-- Layer -->
                            <div class="tp-caption sfb  font-montserrat text-center tp-resizeme" data-x="center"
                                data-hoffset="0" data-y="center" data-voffset="-20" data-speed="700"
                                data-start="1500" data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 18px; font-weight:100; text-transform: uppercase;">
                                Badan Usaha Milik Desa (BUMDes) adalah sebuah lembaga<br> usaha milik desa yang dibangun
                                untuk desa dan dikelola oleh masyarakat terpilih atau perangkat desa. </div>
                        </li>

                        <!-- Slider 3 -->
                        <li data-transition="fade" data-slotamount="7"> <img
                                src="{{ url('landing/images/slides/slide-3.jpg') }}" data-bgposition="center center"
                                alt="" />

                            <!-- Layer -->
                            <div class="tp-caption sft font-montserrat tp-resizeme" data-x="center" data-hoffset="0"
                                data-y="center" data-voffset="-100" data-speed="700" data-start="1000"
                                data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 60px; text-transform: uppercase; font-weight:bolder; letter-spacing:3px;">
                                Kecamatan Bluto </div>

                            <!-- Layer -->
                            <div class="tp-caption sfb  font-montserrat text-center tp-resizeme" data-x="center"
                                data-hoffset="0" data-y="center" data-voffset="-20" data-speed="700"
                                data-start="1500" data-easing="easeOutBack"
                                data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                                data-splitin="none" data-splitout="none" data-elementdelay="0.1"
                                data-endelementdelay="0.1" data-endspeed="300" data-captionhidden="on"
                                style="color: #fff; font-size: 18px; font-weight:100; text-transform: uppercase;">
                                Kecamatan Bluto adalah sebuah kecamatan di kabupaten sumenep <br>
                                yang mempunyai beberapa pedesaan yang ramah dan pemandangan yang indah. </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CONTENT START -->
        <div class="content">

            <!--======= ABOUT US =========-->
            <section class="sec-100px about">
                <div class="container">
                    <div class="row">
                    </div>
                </div>
            </section>

            <!--======= HISTORY =========-->
            <section class="history">
                <div class="row">

                    <!-- IMAGE -->
                    <div class="col-md-4 no-padding">
                        <div class="museum-img"> </div>
                    </div>

                    <!-- History Content -->
                    <div class="col-md-8 no-padding">
                        <div class="history-detail">
                            <ul class="row">
                                <li class="col-md-3">
                                    <h4>Visi</h4>
                                    <hr>
                                </li>
                                <li class="col-md-9">
                                    <p>Terwujudnya masyarakat Desa Errabu yang Bersih, Relegius, Sejahtera, Rapi dan
                                        Indah melalui Akselerasi Pembangunan yang berbasis Keagamaan, Budaya Hukum dan
                                        Berwawasan Lingkungan dengan berorentasi pada peningkatan Kinerja Aparatur dan
                                        Pemberdayaan Masyarakat</p>
                                </li>
                            </ul>

                            <!-- On View -->
                            <ul class="row on-view">
                                <li class="col-md-3">
                                    <h4>Misi</h4>
                                    <hr>
                                </li>
                                <li class="col-md-9">
                                    <ul class="row">
                                        <li class="col-xs-6">
                                            <p> Pembangunan Jangka Panjang</p>
                                            <p> Melanjutkan pembangunan desa yang belum terlaksana.</p>
                                            <p> Meningkatkan kerjasama antara pemerintah desa dengan lembaga desa yang
                                                ada.</p>
                                            <p> Meningkatkan kesejahtraan masyarakat desa dengan meningkatkan sarana dan
                                                prasarana ekonomi warga.</p>
                                        </li>
                                        <li class="col-xs-6">
                                            <p> Pembangunan Jangka Pendek</p>
                                            <p> Mengembangkan dan Menjaga serta melestarikan ada istiadat desa terutama
                                                yang telah mengakar di desa Lobuk.</p>
                                            <p> Meningkatkan pelayanan dalam bidang pemerintahan kepada warga
                                                masyarakat. </p>
                                            <p> Meningkatkan sarana dan prasarana ekonomi warga desa dengan perbaikan
                                                prasarana dan sarana ekonomi.</p>
                                            <p> Meningkatkan sarana dan prasarana pendidikan guna peningkatan sumber
                                                daya manusia Desa Errabu.</p>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section class="sec-100px event" id="potensi_desa">
                <div class="container">
                    <div class="tittle">
                        <h2>POTENSI DESA</h2>
                        <hr>
                        <p>Potensi Pertanian</p>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>
                                Desa Errabu merupakan desa yang memiliki potensi besar untuk pengembangan pertanian.
                                Desa ini memiliki lahan pertanian yang cukup luas dan subur untuk ditanami berbagai
                                jenis tanaman pangan seperti jagung, tembakau, padi, dan lain-lain.

                                Desa Errabu terletak di dataran tinggi dan memiliki curah hujan yang cukup merata
                                sepanjang tahun sehingga sangat cocok untuk pertanian. Jenis tanah di desa ini juga
                                termasuk tanah vulkanik yang kaya akan unsur hara yang diperlukan untuk pertumbuhan
                                tanaman. Dengan kondisi alam yang demikian, Desa Errabu sangat potensial untuk
                                pengembangan pertanian tanaman pangan.

                                Jagung merupakan tanaman pangan utama yang biasa dibudidayakan petani di Desa Errabu.
                                Varietas jagung yang ditanam umumnya adalah jagung manis dan jagung pulut. Hasil panen
                                jagung dari Desa Errabu biasanya memasok kebutuhan jagung ke pasar tradisional maupun
                                industri pengolahan jagung. Selain itu, tembakau juga menjadi komoditas penting kedua
                                setelah jagung yang banyak dibudidayakan di Desa Errabu.

                                Padi sawah juga mulai banyak dikembangkan petani Desa Errabu beberapa tahun terakhir
                                ini. Pengembangan padi sawah dimungkinkan karena tersedianya sumber air irigasi dari
                                sungai yang melintasi desa. Dengan pengembangan padi sawah, diharapkan Desa Errabu bisa
                                memenuhi kebutuhan beras masyarakat setempat.

                                Dengan potensi alam serta minat masyarakatnya yang besar dalam bidang pertanian, Desa
                                Errabu berpeluang menjadi desa agropolitan yang maju jika potensi pertaniannya
                                dikembangkan secara optimal. Pengembangan pertanian di Desa Errabu perlu didukung dengan
                                penyuluhan pertanian, bantuan sarana produksi pertanian, serta akses ke lembaga keuangan
                                dan pasar. Jika hal tersebut dilakukan, maka Desa Errabu bisa menjadi lumbung pangan
                                yang penting di kabupaten maupun provinsi.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="sec-100px event" id="badan_usaha">
                <div class="container">
                    <div class="tittle">
                        <h2>BADAN USAHA</h2>
                        <hr>
                        <p>Badan Usaha yang Dinaungi Oleh Pemerintah Desa Errabu.</p>
                    </div>
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Operator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($badan_usaha as $bu)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bu->nama }}</td>
                                        <td>{{ $bu->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="sec-100px event" id="keuangan">
                <div class="container">
                    <div class="tittle">
                        <h2>KEUANGAN</h2>
                        <hr>
                        <p>Penghasilan Dari Setiap Badan Usaha Milik Desa Errabu.</p>
                    </div>
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Badan Usaha</th>
                                    <th>Operator</th>
                                    <th>Dana Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keuangan as $dana)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dana->badan_usaha->nama }}</td>
                                        <td>{{ $dana->badan_usaha->user->name }}</td>
                                        <td>{{ $dana->nominal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>

        <!--======= Footer =========-->
        <footer>
            <div class="container">

                <!-- Footer Link -->

                <ul class="foot-link">
                    <li><a href="{{ url('/') }}">Home </a></li>
                    <li><a href="#potensi_desa"> POTENSI DESA </a></li>
                    <li><a href="#badan_usaha"> BADAN USAHA </a></li>
                    <li><a href="#keuangan"> KEUANGAN </a></li>
                </ul>
                <!-- Footer Logo -->
                <div class="foot-logo"> <img
                        src="https://errabu.desa.sumenepkab.go.id/desa/logo/OKE_LOGO__sid__FiChDyw.png" width="100"
                        alt=""> </div>
            </div>
        </footer>
    </div>
    <!-- Wrap End -->

    <script src="{{ url('landing/js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ url('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('landing/js/own-menu.js') }}"></script>
    <script src="{{ url('landing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('landing/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ url('landing/js/smooth-scroll.js') }}"></script>
    <script src="{{ url('landing/js/jquery.prettyPhoto.js') }}"></script>

    <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
    <script type="text/javascript" src="{{ url('landing/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('landing/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ url('landing/js/main.js') }}"></script>
    <script type="text/javascript">
        /*-----------------------------------------------------------------------------------*/
        /*  SLIDER REVOLUTION
        /*-----------------------------------------------------------------------------------*/
        jQuery('.tp-banner').show().revolution({
            dottedOverlay: "none",
            delay: 10000,
            startwidth: 1170,
            startheight: 630,
            navigationType: "bullet",
            navigationArrows: "solo",
            navigationStyle: "preview4",
            parallax: "mouse",
            parallaxBgFreeze: "on",
            parallaxLevels: [7, 4, 3, 2, 5, 4, 3, 2, 1, 0],
            keyboardNavigation: "on",
            shadow: 0,
            fullWidth: "on",
            fullScreen: "off",
            shuffle: "off",
            autoHeight: "off",
            forceFullWidth: "off",
            fullScreenOffsetContainer: ""
        });
    </script>
</body>

</html>
