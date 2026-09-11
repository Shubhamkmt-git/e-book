<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request): View
    {
        $query = AdminUser::query();

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->string('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $adminUsers = $query->latest('id')->paginate(10)->withQueryString();

        $totalCount = AdminUser::count();
        $activeCount = AdminUser::where('status', 'active')->count();
        $inactiveCount = AdminUser::where('status', 'inactive')->count();

        return view('admin.admin-users.index', [
            'adminUsers' => $adminUsers,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        return view('admin.admin-users.create', [
            'roles' => $this->getAvailableRoles(),
            'statuses' => $this->getAvailableStatuses(),
        ]);
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:super-admin,admin,manager,editor'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('admin-profiles', 'public');
            $validated['profile_image'] = $path;
        }

        AdminUser::create($validated);

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user successfully created.');
    }

    /**
     * Display the specified admin user.
     */
    public function show(AdminUser $adminUser): View
    {
        return view('admin.admin-users.show', [
            'adminUser' => $adminUser,
        ]);
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(AdminUser $adminUser): View
    {
        return view('admin.admin-users.edit', [
            'adminUser' => $adminUser,
            'roles' => $this->getAvailableRoles(),
            'statuses' => $this->getAvailableStatuses(),
        ]);
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, AdminUser $adminUser): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin_users', 'email')->ignore($adminUser->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:super-admin,admin,manager,editor'],
            'status' => ['required', 'string', 'in:active,inactive,suspended'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_profile_image' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($request->boolean('remove_profile_image')) {
            if ($adminUser->profile_image && Storage::disk('public')->exists($adminUser->profile_image)) {
                Storage::disk('public')->delete($adminUser->profile_image);
            }
            $validated['profile_image'] = null;
        }

        if ($request->hasFile('profile_image')) {
            if ($adminUser->profile_image && Storage::disk('public')->exists($adminUser->profile_image)) {
                Storage::disk('public')->delete($adminUser->profile_image);
            }
            $path = $request->file('profile_image')->store('admin-profiles', 'public');
            $validated['profile_image'] = $path;
        }

        unset($validated['remove_profile_image']);

        if ($adminUser->role === 'super-admin' && isset($validated['status']) && $validated['status'] !== 'active') {
            return back()->withErrors(['status' => 'Super Admin accounts are protected and cannot be deactivated.'])->withInput();
        }

        $adminUser->update($validated);

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(AdminUser $adminUser): RedirectResponse
    {
        if ($adminUser->role === 'super-admin' || $adminUser->email === 'admin@ebook.com') {
            return redirect()
                ->route('admin.admin-users.index')
                ->with('error', 'Super Admin accounts are protected and cannot be deleted.');
        }

        if ($adminUser->profile_image && Storage::disk('public')->exists($adminUser->profile_image)) {
            Storage::disk('public')->delete($adminUser->profile_image);
        }

        $adminUser->delete();

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(AdminUser $adminUser): RedirectResponse|JsonResponse
    {
        if ($adminUser->role === 'super-admin' || $adminUser->email === 'admin@ebook.com') {
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin accounts are protected and cannot be deactivated.',
                ], 422);
            }

            return back()->with('error', 'Super Admin accounts are protected and cannot be deactivated.');
        }

        $newStatus = $adminUser->status === 'active' ? 'inactive' : 'active';
        $adminUser->update(['status' => $newStatus]);

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $adminUser->status,
                'message' => "Admin user status changed to {$adminUser->status}.",
            ]);
        }

        return back()->with('success', "Admin user status changed to {$adminUser->status}.");
    }

    /**
     * Return list of available roles.
     *
     * @return array<string, string>
     */
    protected function getAvailableRoles(): array
    {
        $dbRoles = AdminRole::where('status', 'active')->pluck('title', 'slug')->toArray();

        if (! empty($dbRoles)) {
            return $dbRoles;
        }

        return [
            'super-admin' => 'Super Admin',
            'admin' => 'Admin',
            'manager' => 'Manager',
            'editor' => 'Editor',
        ];
    }

    /**
     * Return list of available statuses.
     *
     * @return array<string, string>
     */
    protected function getAvailableStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
        ];
    }
}
