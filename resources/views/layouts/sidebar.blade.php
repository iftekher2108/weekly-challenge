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


                 <li @class(['nav-item', 'active' => request()->routeIs('admin.category')])>
                     <a href="{{ route('admin.category.company') }}">
                         <i class="fas fa-layer-group"></i>
                         <span class="sub-item">Category</span>
                     </a>
                 </li>

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

                 @if(Auth::user()->isSuperAdmin() || Auth::user()->isCompanyAdmin())
                 <li @class(['nav-item', 'active' => request()->routeIs('admin.company.*')])>
                     <a href="{{ route('admin.company.index') }}">
                         <i class="fas fa-building"></i>
                         <span class="sub-item">Companies</span>
                     </a>
                 </li>
                 @endif

                 @if(Auth::user()->isSuperAdmin() || Auth::user()->isCompanyAdmin())
                 <li @class(['nav-item', 'active' => request()->routeIs('admin.user.*')])>
                     <a href="{{ route('admin.user.index') }}">
                         <i class="fas fa-users"></i>
                         <span class="sub-item">Users</span>
                     </a>
                 </li>
                 @endif


                 {{-- <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#sidebarLayouts">
                         <i class="fas fa-th-list"></i>
                         <p>Sidebar Layouts</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="sidebarLayouts">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a href="sidebar-style-2.html">
                                     <span class="sub-item">Sidebar Style 2</span>
                                 </a>
                             </li>
                             <li>
                                 <a href="icon-menu.html">
                                     <span class="sub-item">Icon Menu</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li> --}}





                 {{-- <li class="nav-item">
                     <a data-bs-toggle="collapse" href="#submenu">
                         <i class="fas fa-bars"></i>
                         <p>Menu Levels</p>
                         <span class="caret"></span>
                     </a>
                     <div class="collapse" id="submenu">
                         <ul class="nav nav-collapse">
                             <li>
                                 <a data-bs-toggle="collapse" href="#subnav1">
                                     <span class="sub-item">Level 1</span>
                                     <span class="caret"></span>
                                 </a>
                                 <div class="collapse" id="subnav1">
                                     <ul class="nav nav-collapse subnav">
                                         <li>
                                             <a href="#">
                                                 <span class="sub-item">Level 2</span>
                                             </a>
                                         </li>
                                         <li>
                                             <a href="#">
                                                 <span class="sub-item">Level 2</span>
                                             </a>
                                         </li>
                                     </ul>
                                 </div>
                             </li>
                             <li>
                                 <a data-bs-toggle="collapse" href="#subnav2">
                                     <span class="sub-item">Level 1</span>
                                     <span class="caret"></span>
                                 </a>
                                 <div class="collapse" id="subnav2">
                                     <ul class="nav nav-collapse subnav">
                                         <li>
                                             <a href="#">
                                                 <span class="sub-item">Level 2</span>
                                             </a>
                                         </li>
                                     </ul>
                                 </div>
                             </li>
                             <li>
                                 <a href="#">
                                     <span class="sub-item">Level 1</span>
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </li> --}}


             </ul>
         </div>
     </div>
 </div>
