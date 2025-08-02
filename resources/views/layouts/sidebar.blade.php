 <div class="sidebar" data-background-color="dark">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="{{ route('admin.dashboard') }}" class="logo">
                 <img src="{{ asset('assets/backend/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                     class="navbar-brand" height="20" />
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li @class([
                     'nav-item',
                     'active' => request()->routeIs('admin.dashboard'),
                 ])>

                     <a href="{{ route('admin.dashboard') }}">
                         <i class="fas fa-home"></i>
                         <p>Dashboard</p>
                     </a>

                 </li>

                 @if (Auth::user()->companies)
                     @if (Auth::user()->isCompanyAdmin())
                         <li @class([
                             'nav-item',
                             'active' => request()->routeIs('admin.company.category'),
                         ])>
                             <a href="{{ route('admin.company.category.search') }}">
                                 <i class="fas fa-layer-group"></i>
                                 <span class="sub-item">Category</span>
                             </a>
                         </li>
                     @endif
                 @endif


                 <li @class(['nav-item', 'active' => request()->routeIs('admin.task')])>
                     <a href="{{ route('admin.task') }}">
                         <i class="fas fa-layer-group"></i>
                         <span class="sub-item">Task</span>
                     </a>
                 </li>

                 <li @class(['nav-item', 'active' => request()->routeIs('admin.report')])>
                     <a href="{{ route('admin.report') }}">
                         <i class="fas fa-chart-bar"></i>
                         <span class="sub-item">Reports</span>
                     </a>
                 </li>


                 <li @class([
                     'nav-item',
                     'active' => request()->routeIs('admin.company.*'),
                 ])>
                     <a href="{{ route('admin.company.index') }}">
                         <i class="fas fa-building"></i>
                         <span class="sub-item">Companies</span>
                     </a>
                 </li>


                 <li @class(['nav-item', 'active' => request()->routeIs('admin.user.*')])>
                     <a href="{{ route('admin.user.index') }}">
                         <i class="fas fa-users"></i>
                         <span class="sub-item">Users</span>
                     </a>
                 </li>

             </ul>
         </div>
     </div>
 </div>
