<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Dashboard · {{ env('APP_NAME', 'IFRS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Rawline';
            src: url('{{ asset('fonts/rawline/rawline-400.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Rawline';
            src: url('{{ asset('fonts/rawline/rawline-700.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
        :root {
            --bs-font-sans-serif: 'Rawline', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --bs-body-font-family: var(--bs-font-sans-serif);
        }
        body, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Rawline', sans-serif !important;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: #0000001a;
            border: solid rgba(0, 0, 0, 0.15);
            border-width: 1px 0;
            box-shadow:
                inset 0 0.5em 1.5em #0000001a,
                inset 0 0.125em 0.5em #00000026;
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        /*
         * Mantém o comportamento padrão dos ícones pequenos da sidebar.
         * Os ícones grandes dos cards devem usar a classe .dash-icon,
         * definida na própria página do dashboard.
         */
        .nav-link .bi {
            vertical-align: -0.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .bi {
            width: 1em;
            height: 1em;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        @media (min-width: 768px) {
            .sidebar {
                min-height: calc(100vh - 48px);
            }
        }
    </style>

    @yield('custom_css')
</head>

<body>
<header class="navbar flex-md-nowrap py-3 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 bg-transparent shadow-none" href="{{route('index')}}">
        {{-- LOGO BRANCA - Sempre visível no Header (Desktop e Mobile) --}}
        <svg viewBox="0 0 708 204" style="height: 45px; width: auto;" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M288.881 90.2261C286.775 87.8963 283.191 85.6562 278.128 83.5056C273.065 81.355 267.846 80.2797 262.469 80.2797C256.107 80.2797 251.336 81.467 248.155 83.8416C245.018 86.1714 243.45 89.4869 243.45 93.788C243.45 95.1321 243.629 96.3418 243.988 97.4171C244.346 98.4924 244.794 99.4557 245.332 100.307C245.914 101.113 246.766 101.897 247.886 102.659C249.006 103.421 250.104 104.07 251.179 104.608C252.299 105.146 253.8 105.728 255.682 106.355C257.608 106.938 259.4 107.453 261.058 107.901C262.716 108.304 264.889 108.842 267.577 109.514C271.878 110.545 275.552 111.575 278.599 112.606C281.645 113.591 284.58 114.868 287.403 116.436C290.225 117.96 292.488 119.684 294.19 121.611C295.893 123.493 297.237 125.8 298.223 128.533C299.253 131.266 299.768 134.38 299.768 137.875C299.768 141.773 299.141 145.29 297.887 148.426C296.677 151.562 294.997 154.206 292.846 156.356C290.696 158.507 288.097 160.321 285.05 161.8C282.004 163.234 278.733 164.286 275.238 164.958C271.744 165.586 267.98 165.899 263.948 165.899C256.51 165.899 249.319 164.779 242.375 162.539C235.43 160.254 229.18 156.983 223.625 152.727L230.681 139.488C233.504 142.4 238.096 145.223 244.458 147.956C250.865 150.644 257.451 151.988 264.217 151.988C270.31 151.988 274.992 150.913 278.263 148.762C281.578 146.611 283.236 143.52 283.236 139.488C283.236 137.875 282.989 136.419 282.497 135.119C282.004 133.82 281.152 132.655 279.943 131.625C278.733 130.549 277.479 129.631 276.179 128.869C274.925 128.108 273.11 127.323 270.736 126.517C268.406 125.711 266.255 125.039 264.284 124.501C262.357 123.963 259.736 123.269 256.421 122.417C251.268 121.073 247.012 119.707 243.652 118.318C240.292 116.929 237.312 115.227 234.714 113.21C232.16 111.194 230.3 108.82 229.135 106.087C227.971 103.309 227.388 100.038 227.388 96.2746C227.388 91.5702 228.284 87.3363 230.076 83.5728C231.913 79.7645 234.422 76.6282 237.603 74.164C240.784 71.6998 244.525 69.8181 248.827 68.5188C253.128 67.2195 257.81 66.5698 262.873 66.5698C274.88 66.5698 285.879 70.2437 295.87 77.5915L288.881 90.2261ZM311.096 164.958V67.2419H326.486V164.958H311.096ZM402.265 90.2261C400.159 87.8963 396.575 85.6562 391.512 83.5056C386.449 81.355 381.229 80.2797 375.853 80.2797C369.491 80.2797 364.719 81.467 361.538 83.8416C358.402 86.1714 356.834 89.4869 356.834 93.788C356.834 95.1321 357.013 96.3418 357.371 97.4171C357.73 98.4924 358.178 99.4557 358.715 100.307C359.298 101.113 360.149 101.897 361.269 102.659C362.389 103.421 363.487 104.07 364.562 104.608C365.682 105.146 367.183 105.728 369.065 106.355C370.992 106.938 372.784 107.453 374.442 107.901C376.099 108.304 378.272 108.842 380.96 109.514C385.262 110.545 388.935 111.575 391.982 112.606C395.029 113.591 397.963 114.868 400.786 116.436C403.609 117.96 405.871 119.684 407.574 121.611C409.276 123.493 410.62 125.8 411.606 128.533C412.637 131.266 413.152 134.38 413.152 137.875C413.152 141.773 412.525 145.29 411.27 148.426C410.06 151.562 408.38 154.206 406.23 156.356C404.079 158.507 401.481 160.321 398.434 161.8C395.387 163.234 392.117 164.286 388.622 164.958C385.127 165.586 381.364 165.899 377.331 165.899C369.894 165.899 362.703 164.779 355.758 162.539C348.814 160.254 342.564 156.983 337.008 152.727L344.065 139.488C346.887 142.4 351.48 145.223 357.842 147.956C364.249 150.644 370.835 151.988 377.6 151.988C383.693 151.988 388.375 150.913 391.646 148.762C394.962 146.611 396.619 143.52 396.619 139.488C396.619 137.875 396.373 136.419 395.88 135.119C395.387 133.82 394.536 132.655 393.326 131.625C392.117 130.549 390.862 129.631 389.563 128.869C388.308 128.108 386.494 127.323 384.119 126.517C381.789 125.711 379.639 125.039 377.667 124.501C375.741 123.963 373.12 123.269 369.804 122.417C364.652 121.073 360.396 119.707 357.035 118.318C353.675 116.929 350.696 115.227 348.097 113.21C345.543 111.194 343.684 108.82 342.519 106.087C341.354 103.309 340.772 100.038 340.772 96.2746C340.772 91.5702 341.668 87.3363 343.46 83.5728C345.297 79.7645 347.806 76.6282 350.987 74.164C354.168 71.6998 357.909 69.8181 362.21 68.5188C366.511 67.2195 371.193 66.5698 376.256 66.5698C388.263 66.5698 399.263 70.2437 409.254 77.5915L402.265 90.2261ZM472.397 115.563H505.798V164.958H492.895V152.862C484.786 161.374 475.287 165.631 464.4 165.631C458.083 165.631 452.057 164.264 446.322 161.531C440.632 158.798 435.748 155.169 431.671 150.644C427.639 146.119 424.413 140.809 421.993 134.716C419.619 128.578 418.431 122.238 418.431 115.697C418.431 109.156 419.619 102.883 421.993 96.8795C424.368 90.831 427.571 85.589 431.604 81.1534C435.681 76.7178 440.609 73.1784 446.389 70.535C452.213 67.8915 458.396 66.5698 464.938 66.5698C473.943 66.5698 481.649 68.474 488.056 72.2823C494.508 76.0458 499.414 81.243 502.774 87.8739L490.946 96.409C488.303 91.2118 484.674 87.2691 480.059 84.5809C475.444 81.8479 470.269 80.4813 464.534 80.4813C458.575 80.4813 453.244 82.1615 448.539 85.5217C443.835 88.8372 440.251 93.2056 437.787 98.6268C435.322 104.003 434.09 109.828 434.09 116.1C434.09 126.002 437.115 134.425 443.163 141.369C449.212 148.269 456.694 151.719 465.61 151.719C475.735 151.719 484.83 146.947 492.895 137.404V126.987H472.397V115.563ZM586.281 151.316V164.958H519.277V67.2419H585.071V80.8846H534.667V108.64H578.283V121.477H534.667V151.316H586.281ZM680.204 164.958V95.3337L651.441 148.157H642.368L613.47 95.3337V164.958H598.08V67.2419H614.612L646.938 126.987L679.264 67.2419H695.796V164.958H680.204Z" fill="white"/>
            <path d="M9.06274 89.1167L9.06275 153.764" stroke="white" stroke-width="18.1254"/>
            <path d="M178.233 94.8564L178.233 139.566" stroke="white" stroke-width="18.1254"/>
            <path d="M33.23 50.1472L33.23 190.921" stroke="white" stroke-width="18.1254"/>
            <path d="M154.066 61.6267L154.066 174.608" stroke="white" stroke-width="18.1254"/>
            <path d="M57.397 40.4802L57.397 203.609" stroke="white" stroke-width="18.1254"/>
            <path d="M105.731 50.1472L105.731 203.609" stroke="white" stroke-width="18.1254"/>
            <path d="M129.899 40.4802L129.899 199.379" stroke="white" stroke-width="18.1254"/>
            <path d="M81.5645 53.168L81.5645 196.358" stroke="white" stroke-width="18.1254"/>
            <path d="M103.143 12.286C94.8605 20.5039 93.186 40.4544 93.186 40.4544C93.186 40.4544 114.642 39.4096 123.246 30.5333C131.224 22.3021 140.947 -2.67399e-05 140.947 -2.67399e-05C140.947 -2.67399e-05 111.736 3.7597 103.143 12.286Z" fill="white"/>
        </svg>
    </a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button
                class="navbar-toggler border-0 px-3"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
        </li>
    </ul>
</header>

<div class="container-fluid">
    <div class="row">

        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
            <div
                class="offcanvas-md offcanvas-end bg-body-tertiary"
                tabindex="-1"
                id="sidebarMenu"
                aria-labelledby="sidebarMenuLabel"
            >
                <div class="offcanvas-header align-items-center">
                    <h5 class="offcanvas-title" id="sidebarMenuLabel">
                        {{-- LOGO COLORIDA - Aparece no topo do menu hambúrguer aberto no telemóvel --}}
                        <svg viewBox="0 0 708 204" style="height: 40px; width: auto;" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M288.881 90.226C286.775 87.8962 283.191 85.656 278.128 83.5055C273.065 81.3549 267.846 80.2796 262.469 80.2796C256.107 80.2796 251.336 81.4669 248.155 83.8415C245.018 86.1713 243.45 89.4867 243.45 93.7879C243.45 95.132 243.629 96.3417 243.988 97.417C244.346 98.4923 244.794 99.4555 245.332 100.307C245.914 101.113 246.766 101.897 247.886 102.659C249.006 103.421 250.104 104.07 251.179 104.608C252.299 105.146 253.8 105.728 255.682 106.355C257.608 106.938 259.4 107.453 261.058 107.901C262.716 108.304 264.889 108.842 267.577 109.514C271.878 110.544 275.552 111.575 278.599 112.605C281.645 113.591 284.58 114.868 287.403 116.436C290.225 117.959 292.488 119.684 294.19 121.611C295.893 123.493 297.237 125.8 298.223 128.533C299.253 131.266 299.768 134.38 299.768 137.875C299.768 141.773 299.141 145.29 297.887 148.426C296.677 151.562 294.997 154.206 292.846 156.356C290.696 158.507 288.097 160.321 285.05 161.8C282.004 163.233 278.733 164.286 275.238 164.958C271.744 165.586 267.98 165.899 263.948 165.899C256.51 165.899 249.319 164.779 242.375 162.539C235.43 160.254 229.18 156.983 223.625 152.727L230.681 139.488C233.504 142.4 238.096 145.222 244.458 147.955C250.865 150.644 257.451 151.988 264.217 151.988C270.31 151.988 274.992 150.912 278.263 148.762C281.578 146.611 283.236 143.52 283.236 139.488C283.236 137.875 282.989 136.418 282.497 135.119C282.004 133.82 281.152 132.655 279.943 131.625C278.733 130.549 277.479 129.631 276.179 128.869C274.925 128.107 273.11 127.323 270.736 126.517C268.406 125.711 266.255 125.038 264.284 124.501C262.357 123.963 259.736 123.269 256.421 122.417C251.268 121.073 247.012 119.707 243.652 118.318C240.292 116.929 237.312 115.226 234.714 113.21C232.16 111.194 230.3 108.819 229.135 106.086C227.971 103.309 227.388 100.038 227.388 96.2745C227.388 91.5701 228.284 87.3362 230.076 83.5727C231.913 79.7644 234.422 76.6281 237.603 74.1639C240.784 71.6997 244.525 69.818 248.827 68.5187C253.128 67.2194 257.81 66.5697 262.873 66.5697C274.88 66.5697 285.879 70.2436 295.87 77.5914L288.881 90.226ZM311.096 164.958V67.2418H326.486V164.958H311.096ZM402.265 90.226C400.159 87.8962 396.575 85.656 391.512 83.5055C386.449 81.3549 381.229 80.2796 375.853 80.2796C369.491 80.2796 364.719 81.4669 361.538 83.8415C358.402 86.1713 356.834 89.4867 356.834 93.7879C356.834 95.132 357.013 96.3417 357.371 97.417C357.73 98.4923 358.178 99.4555 358.715 100.307C359.298 101.113 360.149 101.897 361.269 102.659C362.389 103.421 363.487 104.07 364.562 104.608C365.682 105.146 367.183 105.728 369.065 106.355C370.992 106.938 372.784 107.453 374.442 107.901C376.099 108.304 378.272 108.842 380.96 109.514C385.262 110.544 388.935 111.575 391.982 112.605C395.029 113.591 397.963 114.868 400.786 116.436C403.609 117.959 405.871 119.684 407.574 121.611C409.276 123.493 410.62 125.8 411.606 128.533C412.637 131.266 413.152 134.38 413.152 137.875C413.152 141.773 412.525 145.29 411.27 148.426C410.06 151.562 408.38 154.206 406.23 156.356C404.079 158.507 401.481 160.321 398.434 161.8C395.387 163.233 392.117 164.286 388.622 164.958C385.127 165.586 381.364 165.899 377.331 165.899C369.894 165.899 362.703 164.779 355.758 162.539C348.814 160.254 342.564 156.983 337.008 152.727L344.065 139.488C346.887 142.4 351.48 145.222 357.842 147.955C364.249 150.644 370.835 151.988 377.6 151.988C383.693 151.988 388.375 150.912 391.646 148.762C394.962 146.611 396.619 143.52 396.619 139.488C396.619 137.875 396.373 136.418 395.88 135.119C395.387 133.82 394.536 132.655 393.326 131.625C392.117 130.549 390.862 129.631 389.563 128.869C388.308 128.107 386.494 127.323 384.119 126.517C381.789 125.71 379.639 125.038 377.667 124.501C375.741 123.963 373.12 123.269 369.804 122.417C364.652 121.073 360.396 119.707 357.035 118.318C353.675 116.929 350.696 115.226 348.097 113.21C345.543 111.194 343.684 108.82 342.519 106.086C341.354 103.309 340.772 100.038 340.772 96.2745C340.772 91.5701 341.668 87.3362 343.46 83.5727C345.297 79.7644 347.806 76.6281 350.987 74.1639C354.168 71.6997 357.909 69.818 362.21 68.5187C366.511 67.2194 371.193 66.5697 376.256 66.5697C388.263 66.5697 399.263 70.2436 409.254 77.5914L402.265 90.226ZM472.397 115.562H505.798V164.958H492.895V152.861C484.786 161.374 475.287 165.63 464.4 165.63C458.083 165.63 452.057 164.264 446.322 161.531C440.632 158.798 435.748 155.169 431.671 150.644C427.639 146.118 424.413 140.809 421.993 134.716C419.619 128.578 418.431 122.238 418.431 115.697C418.431 109.156 419.619 102.883 421.993 96.8793C424.368 90.8309 427.571 85.5888 431.604 81.1533C435.681 76.7177 440.609 73.1782 446.389 70.5348C452.213 67.8914 458.396 66.5697 464.938 66.5697C473.943 66.5697 481.649 68.4739 488.056 72.2822C494.508 76.0457 499.414 81.2429 502.774 87.8738L490.946 96.4089C488.303 91.2117 484.674 87.269 480.059 84.5808C475.444 81.8477 470.269 80.4812 464.534 80.4812C458.575 80.4812 453.244 82.1614 448.539 85.5216C443.835 88.8371 440.251 93.2054 437.787 98.6267C435.322 104.003 434.09 109.828 434.09 116.1C434.09 126.002 437.115 134.425 443.163 141.369C449.212 148.269 456.694 151.719 465.61 151.719C475.735 151.719 484.83 146.947 492.895 137.404V126.987H472.397V115.562ZM586.281 151.316V164.958H519.277V67.2418H585.071V80.8845H534.667V108.64H578.283V121.476H534.667V151.316H586.281ZM680.204 164.958V95.3336L651.441 148.157H642.368L613.47 95.3336V164.958H598.08V67.2418H614.612L646.938 126.987L679.264 67.2418H695.796V164.958H680.204Z" fill="#044837"/>
                            <path d="M9.06274 89.1167L9.06275 153.764" stroke="#75B734" stroke-width="18.1254"/>
                            <path d="M178.233 94.8564L178.233 139.566" stroke="#76B835" stroke-width="18.1254"/>
                            <path d="M33.23 50.1471L33.23 190.921" stroke="#217F2B" stroke-width="18.1254"/>
                            <path d="M154.066 61.6266L154.066 174.608" stroke="#217F2B" stroke-width="18.1254"/>
                            <path d="M57.397 40.4802L57.397 203.609" stroke="#044837" stroke-width="18.1254"/>
                            <path d="M105.731 50.1471L105.731 203.609" stroke="black" stroke-width="18.1254"/>
                            <path d="M129.899 40.4802L129.899 199.379" stroke="#044837" stroke-width="18.1254"/>
                            <path d="M81.5645 53.168L81.5645 196.358" stroke="#217F2B" stroke-width="18.1254"/>
                            <path d="M103.143 12.2861C94.8605 20.504 93.186 40.4545 93.186 40.4545C93.186 40.4545 114.642 39.4097 123.246 30.5334C131.224 22.3022 140.947 9.53304e-05 140.947 9.53304e-05C140.947 9.53304e-05 111.736 3.75982 103.143 12.2861Z" fill="#217F29"/>
                        </svg>
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

                    @php
                        $usuarioLogado = auth()->user();

                        $normalizarValorLdap = function ($valor) {
                            if ($valor instanceof \Illuminate\Support\Collection) {
                                $valor = $valor->first();
                            }

                            if (is_array($valor)) {
                                $valor = $valor[0] ?? null;
                            }

                            if (is_object($valor) && method_exists($valor, '__toString')) {
                                $valor = (string) $valor;
                            }

                            if (! is_string($valor) || trim($valor) === '') {
                                return null;
                            }

                            $valor = strtolower(trim($valor));

                            if (str_contains($valor, '@')) {
                                $valor = \Illuminate\Support\Str::before($valor, '@');
                            }

                            return $valor;
                        };

                        $ldapUsername = null;

                        if ($usuarioLogado) {
                            $possiveisCampos = [
                                $usuarioLogado->username ?? null,
                                $usuarioLogado->login ?? null,
                                $usuarioLogado->samaccountname ?? null,
                                $usuarioLogado->samAccountName ?? null,
                                $usuarioLogado->uid ?? null,
                                $usuarioLogado->email ?? null,
                            ];

                            foreach ($possiveisCampos as $campo) {
                                $ldapUsername = $normalizarValorLdap($campo);

                                if ($ldapUsername) {
                                    break;
                                }
                            }
                        }

                        $adminLdapUsername = config('sisgem.admin_ldap_username') ?: env('ADMIN_LDAP_USERNAME');
                        $adminLdapUsername = $normalizarValorLdap($adminLdapUsername);

                        $isAdminAvaliacao = $ldapUsername && $adminLdapUsername && $ldapUsername === $adminLdapUsername;
                    @endphp

                    <ul class="nav flex-column">

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Painel</span>
                        </h6>

                        <li class="nav-item">
                            <a
                                class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('home') ? 'active' : '' }}"
                                aria-current="page"
                                href="{{ route('home') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-easel" viewBox="0 0 16 16">
                                    <path d="M8 0a.5.5 0 0 1 .473.337L9.046 2H14a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1.85l1.323 3.837a.5.5 0 1 1-.946.326L11.092 11H8.5v3a.5.5 0 0 1-1 0v-3H4.908l-1.435 4.163a.5.5 0 1 1-.946-.326L3.85 11H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4.954L7.527.337A.5.5 0 0 1 8 0M2 3v7h12V3z"/>
                                </svg>
                                Ver Painel
                            </a>
                        </li>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Contratos</span>
                        </h6>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs(['contratos', 'contrato.*']) ? 'active' : '' }}" href="{{ route('contratos') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                    <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                                    <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                </svg>
                                Contratos
                            </a>
                        </li>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Alunos</span>
                        </h6>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs(['cardapio', 'cardapio.*']) ? 'active' : '' }}" href="{{ route('cardapio') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cup-straw" viewBox="0 0 16 16">
                                    <path d="M13.902.334a.5.5 0 0 1-.28.65l-2.254.902-.4 1.927c.376.095.715.215.972.367.228.135.56.396.56.82q0 .069-.011.132l-.962 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87s-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 5.132A1 1 0 0 1 3.5 5c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 3.614 6.867 3.5 8 3.5c.712 0 1.389.045 1.985.127l.464-2.215a.5.5 0 0 1 .303-.356l2.5-1a.5.5 0 0 1 .65.278M9.768 4.607A14 14 0 0 0 8 4.5c-1.076 0-2.033.11-2.707.278A3.3 3.3 0 0 0 4.645 5c.146.073.362.15.648.222C5.967 5.39 6.924 5.5 8 5.5c.571 0 1.109-.03 1.588-.085zm.292 1.756C9.445 6.45 8.742 6.5 8 6.5c-1.133 0-2.176-.114-2.95-.308a6 6 0 0 1-.435-.127l.838 8.03c.013.121.06.186.102.215.357.249 1.168.69 2.438.69s2.081-.441 2.438-.69c.042-.029.09-.094.102-.215l.852-8.03a6 6 0 0 1-.435.127 9 9 0 0 1-.89.17zM4.467 4.884s.003.002.005.006zm7.066 0-.005.006zM11.354 5a3 3 0 0 0-.604-.21l-.099.445.055-.013c.286-.072.502-.149.648-.222"/>
                                </svg>
                                Cardápio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('cursos.*') ? 'active' : '' }}" href="{{ route('cursos.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                    <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                    <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                                </svg>
                                Cursos
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('alunos.*') ? 'active' : '' }}" href="{{ route('alunos.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                    <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                    <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z"/>
                                </svg>
                                Discentes Autorizados
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('retirada.*') ? 'active' : '' }}" href="{{ route('retirada.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                                    <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0z"/>
                                </svg>
                                Controle de Retirada
                            </a>
                        </li>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Análise de dados</span>
                        </h6>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('graficos.tipos_merenda') ? 'active' : '' }}" href="{{ route('graficos.tipos_merenda') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16">
                                    <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1z"/>
                                </svg>
                                Tipos de Merenda
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('graficos.por_dia_semana') ? 'active' : '' }}" href="{{ route('graficos.por_dia_semana') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pie-chart" viewBox="0 0 16 16">
                                    <path d="M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793zm1 0V7.5h6.482A7 7 0 0 0 8.5 1.018M14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8"/>
                                </svg>
                                Dias da semana
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('graficos.por_turma') ? 'active' : '' }}" href="{{ route('graficos.por_turma') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                                    <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z"/>
                                </svg>
                                Retirada por turma
                            </a>
                        </li>
                        @if(auth()->check() && \App\Models\User::isSuperAdmin(auth()->user()))
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                            <span>Administração</span>
                        </h6>
                            <li class="nav-item">
                                <a
                                    class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('avaliacao.index') ? 'active' : '' }}"
                                    href="{{ route('avaliacao.index') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks" viewBox="0 0 16 16">
                                        <path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 7 2.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 7 7.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M1.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m0 5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m0 5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/>
                                    </svg>
                                    Formulário SUS
                                </a>
                            </li>

                            <li class="nav-item">
                                <a
                                    class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('avaliacao.respostas') || request()->routeIs('avaliacao.moderacao') ? 'active' : '' }}"
                                    href="{{ route('avaliacao.respostas') }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
                                        <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-5 0h2V7H7zm-5 0h2v-3H2z"/>
                                    </svg>
                                    Respostas SUS
                                </a>
                            </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                                </svg>
                                Gerenciar Usuários
                            </a>
                        </li>
                        @endif
                    </ul>

                    <hr class="my-3" />

                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a
                                    class="nav-link d-flex align-items-center gap-2"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                                        <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3zm1 13h8V2H4z"/>
                                        <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0"/>
                                    </svg>
                                    Sair
                                </a>
                            </form>
                        </li>
                    </ul>

                </div>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>

    </div>
</div>

@yield('custom_js')

</body>
</html>
