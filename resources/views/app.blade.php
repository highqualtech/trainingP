<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="TayCare">
    <meta name="author" content="HQT">
    <meta name="keywords" content="TayCare">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Expires" content="0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="/img/icons/icon-48x48.png" />

    <title>Training Portal</title>

    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="/js/jquery.js"></script>
    <script src="/js/elfinder.min.js"></script>
    <link href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/644d105428.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"
    />
    <link rel="stylesheet" type="text/css" href="/css/styles.css">

</head>

<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="/dashboard">
                <span class="align-middle"><img src="/img/logo2x.png"></span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/quickinvite">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Quick Invite</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/courses">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Courses</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/participants">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Participants</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/companies">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Companies</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/intakegroups">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Intake Groups</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/csvimport">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">CSV Importer</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="/marking">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Marking</span>
                    </a>
                </li>
                @if(($user->id=='1')||($user->id=='2'))
                    <li class="sidebar-item active">
                        <a class="sidebar-link" href="/users">
                            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Users</span>
                        </a>
                    </li>

                @endif

            </ul>

        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>

                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown"><span class="text-dark">{{ $user->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="/profile"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="/logout"
                                   onclick="event.preventDefault();
                                        this.closest('form').submit();">&nbsp;&nbsp;
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            <div class="container-fluid p-0">
                <div id="message_containertimeout" style="display:none;width:1200px;position: absolute;z-index: 10000;" class="bg-danger p-2 text-white">
                    <span id="msgtimeout"></span>
                    <p class="text-end">
                        <a id="clear_acktimeout" href="#"
                           onclick="$(this).parents('div#message_containertimeout').fadeOut(400); return false;" class="text-white">
                            close message
                        </a></p>
                </div>
                @yield('content')

            </div>
        </main>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

    <script src="/js/app.js"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function() {
            $('.js-select').select2();
        });
    </script>
    @yield('javascript')

    @if (Auth::check())
        <script>
            var set_messagetimeout = function (messagetimeout) {
                var containertimeout = $('#message_containertimeout');
                $(containertimeout).find('span#msgtimeout').html(messagetimeout);
                $(containertimeout).show();

            }

            var timeout = ({{config('session.lifetime')}} * 60000) +10 ;
            setTimeout(function(){
                window.location.reload(1);
            },  timeout);

            var timeoutI = ({{config('session.lifetime')}} * 60000) -10000 ;
            setTimeout(function(){
                set_messagetimeout("Your login session will shortly time out. Please click <a href='javascript:location.reload();'>HERE</a> to remain logged in");
            },  timeoutI);

        </script>
@endif

</body>

</html>
