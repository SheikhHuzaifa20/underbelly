<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity-log.index');
    }

    public function getData(Request $request)
    {
        $query = ActivityLog::with('admin')->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('admin', function ($row) {
                return $row->admin ? $row->admin->name : 'System';
            })
            ->addColumn('entity', function ($row) {
                return $row->entity_type . ' #' . $row->entity_id;
            })
            ->addColumn('action', function ($row) {
                return ucfirst($row->action);
            })
            ->addColumn('description', function ($row) {
                return $row->description ?: '-';
            })
            ->addColumn('ip_address', function ($row) {
                return $row->ip_address ?: '-';
            })
            ->addColumn('changes', function ($row) {
                if (!$row->changes) {
                    return '-';
                }

                return '<a href="'.route('admin.activity-log.show', $row->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y h:i A') : '-';
            })
            ->rawColumns(['changes']) // if you want to render HTML inside changes
            ->make(true);
    }

    public function show(ActivityLog $activityLog)
    {
        // Pass the log and its decoded changes
        $changes = $activityLog->changes ?? [];

        return view('admin.activity-log.show', compact('activityLog', 'changes'));
    }
}
