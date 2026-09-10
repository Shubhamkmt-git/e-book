<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of admin roles.
     */
    public function index(Request $request): View
    {
        $query = AdminRole::query();

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

        $adminRoles = $query->latest('id')->paginate(10)->withQueryString();

        $totalCount = AdminRole::count();
        $activeCount = AdminRole::where('status', 'active')->count();
        $inactiveCount = AdminRole::where('status', 'inactive')->count();

        return view('admin.admin-roles.index', [
            'adminRoles' => $adminRoles,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    /**
     * Show the form for creating a new admin role.
     */
    public function create(): View
    {
        $permissions = AdminPermission::where('status', 'active')->orderBy('id')->get();

        return view('admin.admin-roles.create', [
            'statuses' => $this->getAvailableStatuses(),
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created admin role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $slugValue = $request->filled('slug') ? $request->string('slug') : $request->string('title');
        $request->merge(['slug' => Str::slug($slugValue)]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:admin_roles,slug'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:admin_permissions,id'],
        ]);

        $role = AdminRole::create($validated);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions', []));
        }

        return redirect()
            ->route('admin.admin-roles.index')
            ->with('success', 'Admin role created successfully.');
    }

    /**
     * Display the specified admin role.
     */
    public function show(AdminRole $adminRole): View
    {
        $adminRole->load('permissions');

        return view('admin.admin-roles.show', [
            'adminRole' => $adminRole,
        ]);
    }

    /**
     * Show the form for editing the specified admin role.
     */
    public function edit(AdminRole $adminRole): View
    {
        $permissions = AdminPermission::where('status', 'active')->orderBy('id')->get();
        $assignedPermissionIds = $adminRole->permissions()->pluck('admin_permissions.id')->all();

        return view('admin.admin-roles.edit', [
            'adminRole' => $adminRole,
            'statuses' => $this->getAvailableStatuses(),
            'permissions' => $permissions,
            'assignedPermissionIds' => $assignedPermissionIds,
        ]);
    }

    /**
     * Update the specified admin role in storage.
     */
    public function update(Request $request, AdminRole $adminRole): RedirectResponse
    {
        $slugValue = $request->filled('slug') ? $request->string('slug') : $request->string('title');
        $request->merge(['slug' => Str::slug($slugValue)]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('admin_roles', 'slug')->ignore($adminRole->id)],
            'status' => ['required', 'string', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:admin_permissions,id'],
        ]);

        if ($adminRole->slug === 'super-admin' && $validated['status'] !== 'active') {
            return back()->withErrors(['status' => 'The Super Admin role is protected and cannot be deactivated.'])->withInput();
        }

        $adminRole->update($validated);
        $adminRole->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route('admin.admin-roles.index')
            ->with('success', 'Admin role updated successfully.');
    }

    /**
     * Remove the specified admin role from storage.
     */
    public function destroy(AdminRole $adminRole): RedirectResponse
    {
        if ($adminRole->slug === 'super-admin') {
            return redirect()
                ->route('admin.admin-roles.index')
                ->with('error', 'The Super Admin role is protected and cannot be deleted.');
        }

        $adminRole->delete();

        return redirect()
            ->route('admin.admin-roles.index')
            ->with('success', 'Admin role deleted successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(AdminRole $adminRole): RedirectResponse|JsonResponse
    {
        if ($adminRole->slug === 'super-admin') {
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The Super Admin role is protected and cannot be deactivated.',
                ], 422);
            }

            return back()->with('error', 'The Super Admin role is protected and cannot be deactivated.');
        }

        $newStatus = $adminRole->status === 'active' ? 'inactive' : 'active';
        $adminRole->update(['status' => $newStatus]);

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $adminRole->status,
                'message' => "Admin role status changed to {$adminRole->status}.",
            ]);
        }

        return back()->with('success', "Admin role status changed to {$adminRole->status}.");
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
