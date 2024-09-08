<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Semantic Admin | {{title}}</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{base_url}}assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{base_url}}assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{base_url}}assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{base_url}}assets/img/favicons/favicon.ico">
    <link rel="manifest" href="{{base_url}}assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <script src="{{base_url}}assets/js/config.js"></script>
    <script src="{{base_url}}vendors/simplebar/simplebar.min.js"></script>
    <script src="{{base_url}}assets/js/scripts/utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{base_url}}vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link href="{{base_url}}assets/css/theme-rtl.css" rel="stylesheet" id="style-rtl">
    <link href="{{base_url}}assets/css/theme.css" rel="stylesheet" id="style-default">
    <link href="{{base_url}}assets/css/user-rtl.css" rel="stylesheet" id="user-style-rtl">
    <link href="{{base_url}}assets/css/user.css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css" />
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" /> -->
    <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.5/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/fh-4.0.1/r-3.0.3/sb-1.8.0/sp-2.3.2/sr-1.4.1/datatables.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.1.5/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/fh-4.0.1/r-3.0.3/sb-1.8.0/sp-2.3.2/sr-1.4.1/datatables.min.js"></script>


    <!-- <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script> -->
    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>


<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid" data-layout="container">
            <nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
                <script>
                    var navbarStyle = localStorage.getItem("navbarStyle");
                    if (navbarStyle && navbarStyle !== 'transparent') {
                        document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
                    }
                </script>
                <div class="d-flex align-items-center">
                    <div class="toggle-icon-wrapper">

                        <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>

                    </div><a class="navbar-brand" href="index.html">
                        <div class="d-flex align-items-center py-3"><img class="me-2" src="{{base_url}}assets/logos/semantic.png" alt="" width="130" />
                        </div>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                    <div class="navbar-vertical-content scrollbar">
                        <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                            <li class="nav-item">
                                <!-- parent pages--><a class="nav-link dropdown-indicator" href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="dashboard">
                                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span>
                                    </div>
                                </a>
                                <ul class="nav collapse show" id="dashboard">
                                    <li class="nav-item"><a class="nav-link <?= $currentPage == 'unauthorized-customers' ? 'active' : '' ?>" href="/reseller/unauthorized-customers">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Πελάτες χωρίς εξουσιοδότηση</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item"><a class="nav-link <?= $currentPage == 'authorized-customers' ? 'active' : '' ?>" href="/reseller/authorized-customers">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Πελάτες με εξουσιοδότηση</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item"><a class="nav-link <?= $currentPage == 'customers-with-contract' ? 'active' : '' ?>" href="/reseller/customers-with-contract">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Πελάτες με σύμβαση</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                    <li class="nav-item"><a class="nav-link <?= $currentPage == 'ready-customers' ? 'active' : '' ?>" href="/reseller/ready-customers">
                                            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Έτοιμοι πελάτες</span>
                                            </div>
                                        </a>
                                        <!-- more inner pages-->
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">

                    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
                    <a class="navbar-brand me-1 me-sm-3" href="index.html">
                        <div class="d-flex align-items-center"><img class="me-2" src="{{base_url}}assets/img/icons/spot-illustrations/falcon.png" alt="" width="40" /><span class="font-sans-serif text-primary">falcon</span>
                        </div>
                    </a>
                    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
                        <li class="nav-item ps-2 pe-0">
                            <div class="dropdown theme-control-dropdown"><a class="nav-link d-flex align-items-center dropdown-toggle fa-icon-wait fs-9 pe-1 py-0" href="#" role="button" id="themeSwitchDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-sun fs-7" data-fa-transform="shrink-2" data-theme-dropdown-toggle-icon="light"></span><span class="fas fa-moon fs-7" data-fa-transform="shrink-3" data-theme-dropdown-toggle-icon="dark"></span><span class="fas fa-adjust fs-7" data-fa-transform="shrink-2" data-theme-dropdown-toggle-icon="auto"></span></a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-caret border py-0 mt-3" aria-labelledby="themeSwitchDropdown">
                                    <div class="bg-white dark__bg-1000 rounded-2 py-2">
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="light" data-theme-control="theme"><span class="fas fa-sun"></span>Light<span class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="dark" data-theme-control="theme"><span class="fas fa-moon" data-fa-transform=""></span>Dark<span class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                                        <button class="dropdown-item d-flex align-items-center gap-2" type="button" value="auto" data-theme-control="theme"><span class="fas fa-adjust" data-fa-transform=""></span>Auto<span class="fas fa-check dropdown-check-icon ms-auto text-600"></span></button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown"><a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-xl">
                                    <div class="avatar-name rounded-circle"><span class="fs-10">{{firstTwoLetters}}</span></div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                                <div class="bg-white dark__bg-1000 rounded-2 py-2">
                                    <a class="dropdown-item fw-bold text-warning" href="#!"><span>{{email}}</span></a>

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/api/logout">Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
                <?= $content; ?>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="row g-0 justify-content-center fs-10 mt-4 mb-3">
            <div class="col-12 col-sm-auto text-center">
                <p class="mb-0 text-600">Semantic Software <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" /> 2024 &copy;</p>
            </div>
        </div>
    </footer>

    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{base_url}}vendors/popper/popper.min.js"></script>
    <script src="{{base_url}}vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{base_url}}vendors/anchorjs/anchor.min.js"></script>
    <script src="{{base_url}}vendors/is/is.min.js"></script>
    <script src="{{base_url}}vendors/echarts/echarts.min.js"></script>
    <script src="{{base_url}}vendors/fontawesome/all.min.js"></script>
    <script src="{{base_url}}vendors/lodash/lodash.min.js"></script>
    <script src="{{base_url}}vendors/list.js/list.min.js"></script>
    <script src="{{base_url}}assets/js/theme.js"></script>


</body>

</html>