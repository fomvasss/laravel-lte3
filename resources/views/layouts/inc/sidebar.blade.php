  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="{{ url(config('lte3.dashboard_slug')) }}" class="brand-link">
      <img src="/vendor/adminlte/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{!! config('lte3.logo') !!}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @if(config('lte3.view.sidebar.auth') && auth()->check())
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/vendor/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/admin/profile" class="d-block"> {{ Lte3::user('name') }} </a>
            </div>
        </div>
        @endif
        @if(config('lte3.view.sidebar.search'))
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>
        @endif

        @include('lte3::layouts.inc.sidebar-menu.example')

    </div>
    <!-- /.sidebar -->
  </aside>
