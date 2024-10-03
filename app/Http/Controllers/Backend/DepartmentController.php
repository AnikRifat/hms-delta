<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest; // Make sure to create this request
use App\Models\Department;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['department.view']);

        return view('backend.pages.departments.index', [
            'departments' => Department::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['department.create']);

        return view('backend.pages.departments.create');
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['department.create']);

        Department::create($request->validated());

        session()->flash('success', __('Department has been created.'));

        return redirect()->route('admin.departments.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['department.edit']);

        $department = Department::findOrFail($id);

        return view('backend.pages.departments.edit', [
            'department' => $department,
        ]);
    }

    public function update(DepartmentRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['department.edit']);

        $department = Department::findOrFail($id);
        $department->update($request->validated());

        session()->flash('success', __('Department has been updated.'));

        return redirect()->route('admin.departments.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['department.delete']);

        $department = Department::findOrFail($id);
        $department->delete();

        session()->flash('success', __('Department has been deleted.'));

        return back();
    }
}
