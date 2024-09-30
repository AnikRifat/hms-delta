 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <h2 class="text-white">Admin</h2>
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                    <li class="active">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                        <ul class="collapse">
                            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                            Roles & Permissions
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                            @if ($usr->can('role.view'))
                                <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">All Roles</a></li>
                            @endif
                            @if ($usr->can('role.create'))
                                <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">Create Role</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif


                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Admins
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">

                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}"><a href="{{ route('admin.admins.index') }}">All Admins</a></li>
                            @endif

                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a href="{{ route('admin.admins.create') }}">Create Admin</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('department.create') || $usr->can('department.view') ||  $usr->can('department.edit') ||  $usr->can('department.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Department
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.departments.create') || Route::is('admin.departments.index') || Route::is('admin.departments.edit') || Route::is('admin.departments.show') ? 'in' : '' }}">

                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.departments.index')  || Route::is('admin.departments.edit') ? 'active' : '' }}"><a href="{{ route('admin.departments.index') }}">All Departments</a></li>
                            @endif

                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.departments.create')  ? 'active' : '' }}"><a href="{{ route('admin.departments.create') }}">Create Department</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('doctor.create') || $usr->can('doctor.view') ||  $usr->can('doctor.edit') ||  $usr->can('doctor.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Doctor
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.doctors.create') || Route::is('admin.doctors.index') || Route::is('admin.doctors.edit') || Route::is('admin.doctors.show') ? 'in' : '' }}">

                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.doctors.index')  || Route::is('admin.doctors.edit') ? 'active' : '' }}"><a href="{{ route('admin.doctors.index') }}">All Doctors</a></li>
                            @endif

                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.doctors.create')  ? 'active' : '' }}"><a href="{{ route('admin.doctors.create') }}">Create Doctor</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
