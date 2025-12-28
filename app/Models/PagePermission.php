<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'page_name',
        'admin_access',
    ];

    protected $casts = [
        'admin_access' => 'boolean',
    ];

    /**
     * Check if admin can access a specific page
     */
    public static function canAdminAccess($pageKey)
    {
        $permission = self::where('page_key', $pageKey)->first();
        return $permission ? $permission->admin_access : true; // Default to true if not found
    }

    /**
     * Get all page permissions as associative array
     */
    public static function getAllPermissions()
    {
        return self::pluck('admin_access', 'page_key')->toArray();
    }
}
