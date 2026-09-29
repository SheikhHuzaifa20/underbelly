<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Traits\FileUploadTrait;

class BlogController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        return view('admin.blog.index');
    }

    public function getData(Request $request)
    {
        $query = Blog::orderBy('sort_order', 'asc');

        // Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        return DataTables::of($query)
            ->addColumn('image', function ($row) {
                if (!$row->image) {
                    return '<span class="text-muted">No Image</span>';
                }
                // Lazy loading image
                return '<img src="'.asset($row->image).'" class="lazy-load" width="120" />';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" class="toggleBlogStatus" data-id="' . $row->id . '" ' . $checked . '>
                        <span class="slider round" title="Click to toggle status"></span>
                    </label>
                ';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y h:i A') : '-';
            })
            ->addColumn('action', function ($row) {
                $actions = '';
                if (auth()->user()->hasPermission('edit_blog')) {
                    $actions .= '<a href="'.url('admin/blog/'.$row->id.'/edit').'"
                                    class="btn btn-sm btn-info"
                                    title="Edit Blog">
                                    <i class="la la-pencil"></i>
                                  </a> ';
                }
                if (auth()->user()->hasPermission('delete_blog')) {
                    $actions .= '<button class="btn btn-sm btn-danger deleteBlog"
                                    data-id="'.$row->id.'"
                                    title="Delete Blog">
                                    <i class="la la-trash"></i>
                                  </button>';
                }
                return $actions ?: '<span class="text-muted">No actions</span>';
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        $data['long_text'] = $request->input('long_text');

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/blog/', 'blog');
        }

        $item = Blog::create($data);

        log_activity('create', Blog::class, $item->id, 'Created new blog: ' . ($item->title ?? 'N/A'));

        return redirect('admin/blog')
            ->with('message', 'Blog added successfully!');
    }

    public function show(Blog $blog)
    {
        return view('admin.blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $data = $request->validated();
        $data['long_text'] = $request->input('long_text');

        if ($request->hasFile('image')) {
            $this->deleteFile($blog->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/blog/', 'blog');
        }

        $oldData = $blog->toArray();
        $blog->update($data);

        log_activity('update', Blog::class, $blog->id, 'Updated blog', [
            'before' => $oldData,
            'after' => $blog->toArray()
        ]);

        return redirect()->route('admin.blog.index')->with('message', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        log_activity('delete', Blog::class, $blog->id, "Deleted blog");
        return response()->json(['success' => 'Blog deleted successfully.']);
    }

    public function toggleStatus(Blog $blog)
    {
        $oldStatus = $blog->status;
        $blog->status = !$oldStatus;
        $blog->save();

        log_activity('status_toggle', Blog::class, $blog->id, 'Toggled status', [
            'old_status' => $oldStatus,
            'new_status' => $blog->status
        ]);

        return response()->json([
            'success' => true,
            'status' => $blog->status ? 'Active' : 'Inactive',
        ]);
    }

    public function trash()
    {
        return view('admin.blog.trash');
    }

    public function getTrashedData(Request $request)
    {
        $items = Blog::onlyTrashed()->orderByDesc('id')->get();

        return DataTables::of($items)
            ->addColumn('checkbox', fn($row) =>
                '<input type="checkbox" class="rowCheckbox" value="'.$row->id.'">'
            )
            ->addColumn('image', function($row) {
                return $row->image
                    ? '<img src="'.asset($row->image).'" class="lazy-load" width="120" />'
                    : '<span class="text-muted">No Image</span>';
            })
            ->addColumn('action', function($row) {
                $restore = '<button class="btn btn-sm btn-success restoreBlog" data-id="'.$row->id.'"><i class="la la-refresh"></i></button>';
                $delete = '<button class="btn btn-sm btn-danger forceDeleteBlog" data-id="'.$row->id.'"><i class="la la-trash"></i></button>';
                return $restore . ' ' . $delete;
            })
            ->rawColumns(['checkbox', 'image', 'action'])
            ->make(true);
    }

    public function restore($id)
    {
        $item = Blog::withTrashed()->findOrFail($id);
        $item->restore();
        log_activity('restore', Blog::class, $id, "Restored blog");
        return response()->json(['success' => 'Blog restored successfully!']);
    }

    public function forceDelete($id)
    {
        $item = Blog::withTrashed()->findOrFail($id);
        $this->deleteFile($item->image);
        $item->forceDelete();

        log_activity('force_delete', Blog::class, $id, "Permanently deleted blog");
        return response()->json(['success' => 'Blog permanently deleted.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        Blog::whereIn('id', $ids)->delete();
        log_activity('bulk_delete', Blog::class, null, 'Bulk delete', ['ids' => $ids]);
        return response()->json(['success' => 'Selected blog deleted successfully.']);
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        Blog::withTrashed()->whereIn('id', $ids)->restore();
        log_activity('bulk_restore', Blog::class, null, 'Bulk restore', ['ids' => $ids]);
        return response()->json(['success' => 'Selected blog restored successfully.']);
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if (empty($ids)) return response()->json(['error' => 'No items selected.'], 400);

        $items = Blog::withTrashed()->whereIn('id', $ids)->get();
        foreach ($items as $item) {
            $this->deleteFile($item->image);
            $item->forceDelete();
        }

        log_activity('bulk_force_delete', Blog::class, null, 'Bulk permanently delete', ['ids' => $ids]);
        return response()->json(['success' => 'Selected blog permanently deleted.']);
    }

    public function sort(Request $request)
    {
        $order = $request->input('order', []);
        if (!is_array($order) || empty($order)) {
            return response()->json(['success' => false, 'message' => 'No order data received'], 400);
        }

        foreach ($order as $item) {
            $pos = $item['position'] ?? $item['newPosition'] ?? null;
            $id  = $item['id'] ?? null;
            if ($id && $pos !== null) {
                Blog::where('id', $id)->update(['sort_order' => (int)$pos]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
}