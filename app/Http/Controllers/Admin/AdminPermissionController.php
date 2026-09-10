<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPermissionController extends Controller
{
    /**
     * Display a listing of admin permissions.
     */
    public function index(Request $request): View
    {
        $query = AdminPermission::query()->withCount('roles');

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $adminPermissions = $query->latest('id')->paginate(10)->withQueryString();

        $totalCount = AdminPermission::count();
        $activeCount = AdminPermission::where('status', 'active')->count();
        $inactiveCount = AdminPermission::where('status', 'inactive')->count();

        return view('admin.admin-permissions.index', [
            'adminPermissions' => $adminPermissions,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    /**
     * Show the form for creating a new admin permission.
     */
    public function create(): View
    {
        return view('admin.admin-permissions.create', [
            'statuses' => $this->getAvailableStatuses(),
        ]);
    }

    /**
     * Store a newly created admin permission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $slugValue = $request->filled('slug') ? $request->string('slug') : $request->string('title');
        $request->merge(['slug' => Str::slug($slugValue)]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:admin_permissions,slug'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        AdminPermission::create($validated);

        return redirect()
            ->route('admin.admin-permissions.index')
            ->with('success', 'Admin permission created successfully.');
    }

    /**
     * Display the specified admin permission.
     */
    public function show(AdminPermission $adminPermission): View
    {
        $adminPermission->load('roles');

        return view('admin.admin-permissions.show', [
            'adminPermission' => $adminPermission,
        ]);
    }

    /**
     * Show the form for editing the specified admin permission.
     */
    public function edit(AdminPermission $adminPermission): View
    {
        return view('admin.admin-permissions.edit', [
            'adminPermission' => $adminPermission,
            'statuses' => $this->getAvailableStatuses(),
        ]);
    }

    /**
     * Update the specified admin permission in storage.
     */
    public function update(Request $request, AdminPermission $adminPermission): RedirectResponse
    {
        $slugValue = $request->filled('slug') ? $request->string('slug') : $request->string('title');
        $request->merge(['slug' => Str::slug($slugValue)]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('admin_permissions', 'slug')->ignore($adminPermission->id)],
            'status' => ['required', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $adminPermission->update($validated);

        return redirect()
            ->route('admin.admin-permissions.index')
            ->with('success', 'Admin permission updated successfully.');
    }

    /**
     * Remove the specified admin permission from storage.
     */
    public function destroy(AdminPermission $adminPermission): RedirectResponse
    {
        $adminPermission->delete();

        return redirect()
            ->route('admin.admin-permissions.index')
            ->with('success', 'Admin permission deleted successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(AdminPermission $adminPermission): RedirectResponse|JsonResponse
    {
        $newStatus = $adminPermission->status === 'active' ? 'inactive' : 'active';
        $adminPermission->update(['status' => $newStatus]);

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $adminPermission->status,
                'message' => "Admin permission status changed to {$adminPermission->status}.",
            ]);
        }

        return back()->with('success', "Admin permission status changed to {$adminPermission->status}.");
    }

    /**
     * Return list of available statuses.
     */
    protected function getAvailableStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }
}
