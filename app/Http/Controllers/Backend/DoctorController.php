<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest; // Make sure to create this request
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class DoctorController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.view']);

        return view('backend.pages.doctors.index', [
            'doctors' => Doctor::with('department')->get(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.create']);

        return view('backend.pages.doctors.create', [
            'departments' => Department::all(),
        ]);
    }

    public function store(DoctorRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.create']);

        Doctor::create($request->validated());

        session()->flash('success', __('Doctor has been created.'));
        return redirect()->route('admin.doctors.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.edit']);

        $doctor = Doctor::findOrFail($id);
        return view('backend.pages.doctors.edit', [
            'doctor' => $doctor,
            'departments' => Department::all(),
        ]);
    }

    public function update(DoctorRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.edit']);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->validated());

        session()->flash('success', __('Doctor has been updated.'));
        return redirect()->route('admin.doctors.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.delete']);

        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        session()->flash('success', __('Doctor has been deleted.'));
        return back();
    }
}
