<!-- Navbar -->
<nav class="main-header navbar navbar-expand {{config('lte3.view.dark_mode') ? 'dark-mode' : 'navbar-white navbar-light'}}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
               class="nav-link dropdown-toggle"><i class="far fa-clock"></i></a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow"
                style="left: 0px; right: inherit;">
                <li><a href="#" class="dropdown-item">UTC: {{ now()->timezone(config('app.timezone')) }} </a></li>
                @if(config('app.timezone_client'))
                    <li><a href="#" class="dropdown-item">{{ config('app.timezone_client') }}: {{ now()->timezone(config('app.timezone_client')) }}</a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
               class="nav-link dropdown-toggle">Domain: Laravel</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow"
                style="left: 0px; right: inherit;">
                <li><a href="#" class="dropdown-item">Laravel</a></li>
                <li><a href="#" class="dropdown-item">Vue.js</a></li>
            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="flag-icon flag-icon-us"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0" style="left: inherit; right: 0px;">
                <a href="#" class="dropdown-item">
                    <i class="flag-icon flag-icon-ua mr-2"></i> Ukrainian
                </a>
                <a href="#" class="dropdown-item active">
                    <i class="flag-icon flag-icon-us mr-2"></i> English
                </a>
                <a href="#" class="dropdown-item">
                    <i class="flag-icon flag-icon-de mr-2"></i> German
                </a>
                <a href="#" class="dropdown-item">
                    <i class="flag-icon flag-icon-fr mr-2"></i> French
                </a>
                <a href="#" class="dropdown-item">
                    <i class="flag-icon flag-icon-es mr-2"></i> Spanish
                </a>
            </div>
        </li>

        <li class="nav-item d-none d-sm-inline-block" data-toggle="tooltip" title="Visit">
            <a href="#" class="nav-link"> <i class="fas fa-chevron-circle-right"></i></a>
        </li>

        <li class="nav-item" data-toggle="tooltip" title="Change">
            <div class="theme-switch-wrapper nav-link">
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                               aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="/vendor/adminlte/dist/img/user1-128x128.jpg" alt="User Avatar"
                             class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="/vendor/adminlte/dist/img/user8-128x128.jpg" alt="User Avatar"
                             class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="/vendor/adminlte/dist/img/user3-128x128.jpg" alt="User Avatar"
                             class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>

        @auth
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="/vendor/adminlte/dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2"
                     alt="User Image">
                <span class="d-none d-md-inline">{{ Lte3::user('name') }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="/vendor/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                         alt="User Image">

                    <p>
                        {{ Lte3::user('name') }}
                        <small>Created {{ Lte3::user('created_at') }}</small>
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div>
                    <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="/admin/profile" class="btn btn-default btn-flat">Profile</a>
                    <a href="/logout" class="btn btn-default btn-flat float-right js-click-submit"
                       data-confirm="Logout?">Sign out</a>
                </li>
            </ul>
        </li>
        @else
        <li class="nav-item">
            <a href="/logout" class="nav-link js-click-submit" data-confirm="Logout?" role="button">
                <i class="fa fa-sign-out-alt"></i>
            </a>
        </li>
        @endauth
    </ul>
</nav>
<!-- /.navbar -->
