<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Department;
use App\Models\Doctor;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['dashboard.view']);

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'total_doctors' => Doctor::count(),
                'total_departments' => Department::count(),
                'total_appointments' => Booking::count(),
            ]
        );
    }
}
