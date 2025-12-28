<?php

namespace App\Http\Controllers;

use App\Models\PagePermission;
use Illuminate\Http\Request;

class AccessControlController extends Controller
{
    /**
     * Display access control settings (Owner only)
     */
    public function index()
    {
        $permissions = PagePermission::all();
        
        return view('access-control.index', [
            'title' => 'Pengaturan Hak Akses',
            'active' => 'access-control',
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update page permission
     */
    public function update(Request $request, $id)
    {
        $permission = PagePermission::findOrFail($id);
        
        $permission->update([
            'admin_access' => $request->has('admin_access'),
        ]);
        
        return redirect()->route('access-control.index')
            ->with('success', 'Hak akses untuk "' . $permission->page_name . '" berhasil diperbarui.');
    }

    /**
     * Bulk update all permissions
     */
    public function bulkUpdate(Request $request)
    {
        $permissions = $request->input('permissions', []);
        
        // Get all page permission IDs
        $allPermissions = PagePermission::all();
        
        foreach ($allPermissions as $permission) {
            $permission->update([
                'admin_access' => in_array($permission->id, $permissions),
            ]);
        }
        
        return redirect()->route('access-control.index')
            ->with('success', 'Semua hak akses berhasil diperbarui.');
    }
}
