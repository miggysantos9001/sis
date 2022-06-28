<!doctype html>
<?php 
    $setup = \App\Setup::latest()->first(); 
    if($setup != NULL){
        $title = $setup->name;
        $desc = $setup->description;
        $cname = $setup->cname;
    }else{
        $title = 'NO TITLE';
        $desc = 'NO DESCRIPTION';
        $cname = '';
    }
?>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>{{ $title }}</title>

        <link rel="shortcut icon" href="<?php echo asset('public/assets/media/favicons/favicon.png') ?>">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo asset('public/assets/media/favicons/favicon-192x192.png') ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('public/assets/media/favicons/apple-touch-icon-180x180.png') ?>">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="<?php echo asset('public/assets/css/codebase.min.css') ?>" >
        <style type="text/css">
            .bg-image {
                background-color: none !important;
                /* background-position: 0 50%; */
                /* background-size: cover; */
            }
        </style>
        @toastr_css
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">

            <main id="main-container">

                <div class="bg-image" style="background-image: url('<?php echo asset('public/assets/media/photos/pharma.jfif'); ?>">
                    <div class="row mx-0">
                        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                            <div class="p-30 invisible" data-toggle="appear">
                                
                            </div>
                        </div>
                        <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
                            <div class="content content-full">
                                
                                <div class="px-30 py-10">
                                    <center>
                                        @if($setup != NULL)
                                        <img class="" src="<?php echo asset('public/images/'.$setup->logo) ?>" alt="" style="margin-bottom: 10px;" height="250" width="350"><br>
                                        @endif
                                        <a class="link-effect font-w700" href="#">
                                            <span class="h3 font-w700 mt-30 mb-10" style="text-transform: uppercase;">{{ $title }}</span>
                                        </a>
                                        <h2 class="h5 font-w400 text-muted mb-0">{{ $cname }}</h2>
                                    </center>
                                </div>
                                @yield('content')
                                <center>
                                <p class="font-italic text-black" style="font-size: 10px;">
                                    Developed by: MDS | Copyright &copy; <span class="js-year-copy"></span>
                                </p>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
        <script src="<?php echo asset('public/assets/js/codebase.core.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/codebase.app.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/pages/op_auth_signin.min.js') ?>"></script>
        @toastr_js
        @toastr_render
    </body>
</html>