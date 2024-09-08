<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Falcon | Dashboard &amp; Web App Template</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/img/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/img/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicons/favicon-16x16.png" />
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicons/favicon.ico" />
    <link rel="manifest" href="../../assets/img/favicons/manifest.json" />
    <meta name="msapplication-TileImage" content="../../assets/img/favicons/mstile-150x150.png" />
    <meta name="theme-color" content="#ffffff" />
    <script src="../../assets/js/config.js"></script>
    <script src="../../vendors/simplebar/simplebar.min.js"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet" />
    <link href="../../vendors/simplebar/simplebar.min.css" rel="stylesheet" />
    <link href="../../assets/css/theme-rtl.css" rel="stylesheet" id="style-rtl" />
    <link href="../../assets/css/theme.css" rel="stylesheet" id="style-default" />
    <link href="../../assets/css/user-rtl.css" rel="stylesheet" id="user-style-rtl" />
    <link href="../../assets/css/user.css" rel="stylesheet" id="user-style-default" />
    <script>
        var isRTL = JSON.parse(localStorage.getItem("isRTL"));
        if (isRTL) {
            var linkDefault = document.getElementById("style-default");
            var userLinkDefault = document.getElementById("user-style-default");
            linkDefault.setAttribute("disabled", true);
            userLinkDefault.setAttribute("disabled", true);
            document.querySelector("html").setAttribute("dir", "rtl");
        } else {
            var linkRTL = document.getElementById("style-rtl");
            var userLinkRTL = document.getElementById("user-style-rtl");
            linkRTL.setAttribute("disabled", true);
            userLinkRTL.setAttribute("disabled", true);
        }
    </script>
</head>

<body>

    {{content}}


    <div class="offcanvas offcanvas-end settings-panel border-0" id="settings-offcanvas" tabindex="-1" aria-labelledby="settings-offcanvas">
        <div class="offcanvas-header settings-panel-header bg-shape">
            <div class="z-1 py-1">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="text-white mb-0 me-2"><span class="fas fa-palette me-2 fs-9"></span>Settings</h5>
                    <button class="btn btn-primary btn-sm rounded-pill mt-0 mb-0" data-theme-control="reset" style="font-size: 12px"><span class="fas fa-redo-alt me-1" data-fa-transform="shrink-3"></span>Reset</button>
                </div>
                <p class="mb-0 fs-10 text-white opacity-75">Set your own customized style</p>
            </div>
            <div class="z-1" data-bs-theme="dark">
                <button class="btn-close z-1 mt-0" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="../../vendors/popper/popper.min.js"></script>
    <script src="../../vendors/bootstrap/bootstrap.min.js"></script>
    <script src="../../vendors/anchorjs/anchor.min.js"></script>
    <script src="../../vendors/is/is.min.js"></script>
    <script src="../../vendors/fontawesome/all.min.js"></script>
    <script src="../../vendors/lodash/lodash.min.js"></script>
    <script src="../../vendors/list.js/list.min.js"></script>
    <script src="../../assets/js/theme.js"></script>
</body>

</html>