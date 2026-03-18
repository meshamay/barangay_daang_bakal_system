<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        
        $user = Auth::user();
        $allowedTypes = ['super admin', 'super_admin', 'superadmin'];
        if (!in_array(strtolower($user->user_type ?? ''), $allowedTypes) && !in_array(strtolower($user->role ?? ''), $allowedTypes)) {
            abort(403, 'Unauthorized');
        }

        $query = AuditLog::with('user');

        
        if ($request->filled('from_date') && $request->filled('to_date')) {
            // Use whereBetween for accurate range, include full day for to_date
            $from = $request->from_date . ' 00:00:00';
            $to = $request->to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        } elseif ($request->filled('from_date')) {
            $from = $request->from_date . ' 00:00:00';
            $query->where('created_at', '>=', $from);
        } elseif ($request->filled('to_date')) {
            $to = $request->to_date . ' 23:59:59';
            $query->where('created_at', '<=', $to);
        }

        
        if ($request->filled('role') && $request->role !== 'ROLE') {
            $query->whereHas('user', function ($q) use ($request) {
                $role = strtolower($request->role);

                // handle various representations of super admin
                if ($role === 'superadmin') {
                    $q->where(function($sub) {
                        $sub->where('role', 'super admin')
                            ->orWhere('role', 'super_admin')
                            ->orWhere('role', 'superadmin')
                            ->orWhere('user_type', 'super admin')
                            ->orWhere('user_type', 'super_admin')
                            ->orWhere('user_type', 'superadmin');
                    });
                } else {
                    $q->where(function($subQuery) use ($role) {
                        $subQuery->where('role', $role)
                                 ->orWhere('user_type', $role);
                    });
                }
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(100);

        return view('admin.auditlogs.index', compact('auditLogs'));
    }
}