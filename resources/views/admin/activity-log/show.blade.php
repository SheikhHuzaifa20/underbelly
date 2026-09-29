@extends('layouts.app')

@push('before-css')
<style>
    .long-text {
        max-width: 350px;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
    }
    .long-text pre {
        max-height: 200px;
        overflow: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
    @media (max-width: 768px) {
        .long-text {
            max-width: 220px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <h3>Activity Log Detail</h3>

    <p><strong>Admin:</strong> {{ $activityLog->admin ? $activityLog->admin->name : 'System' }}</p>
    <p><strong>Action:</strong> {{ ucfirst($activityLog->action) }}</p>
    <p><strong>Entity:</strong> {{ $activityLog->entity_type }} #{{ $activityLog->entity_id }}</p>
    <p><strong>IP:</strong> {{ $activityLog->ip_address ?? '-' }}</p>
    <p><strong>Created At:</strong> {{ $activityLog->created_at->format('d M, Y h:i A') }}</p>

    <hr>

    <h4>Changes</h4>
    <div id="activity-changes-container"></div>

    <a href="{{ route('admin.activity.logs.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

@push('js')
<script>
    const changes = @json($changes);
    const container = document.getElementById('activity-changes-container');

    /* --------- Helpers (same as modal logic) --------- */

    function formatValue(value) {
        if (value === null) return '<em>null</em>';

        if (typeof value === 'object') {
            // If it's an array
            if (Array.isArray(value)) {
                if (value.length === 0) return '<em>No items</em>';
                return '<ul>' + value.map(v => `<li>${formatValue(v)}</li>`).join('') + '</ul>';
            }

            // If it's an object
            let entries = Object.entries(value);
            if (entries.length === 0) return '<em>Empty object</em>';
            return '<ul>' + entries.map(([k,v]) => `<li><strong>${k}:</strong> ${formatValue(v)}</li>`).join('') + '</ul>';
        }

        return value;
    }

    function renderSection(title,before,after){
        let keys = new Set([...Object.keys(before), ...Object.keys(after)]);
        let rows = '';
        keys.forEach(key=>{
            let b=before[key]??'-';
            let a=after[key]??'-';
            rows+=`<tr class="${b!==a?'table-warning':''}">
                        <td class="text-nowrap"><strong>${key}</strong></td>
                        <td class="long-text">${formatValue(b)}</td>
                        <td class="long-text">${formatValue(a)}</td>
                    </tr>`;
        });
        return `<h5 class="mt-4">${title}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th width="20%">Field</th>
                                <th width="40%">Before</th>
                                <th width="40%">After</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
    }

    function renderArraySection(title,beforeArr,afterArr){
        let html=`<h5 class="mt-4">${title}</h5>`;
        if(!beforeArr.length&&!afterArr.length) return html+'<p class="text-muted">No data</p>';

        html+=`<div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr><th>#</th><th>Before</th><th>After</th></tr>
                        </thead>
                        <tbody>`;
        let max=Math.max(beforeArr.length,afterArr.length);
        for(let i=0;i<max;i++){
            html+=`<tr class="${JSON.stringify(beforeArr[i])!==JSON.stringify(afterArr[i])?'table-warning':''}">
                        <td>${i+1}</td>
                        <td class="long-text">${formatValue(beforeArr[i]??'-')}</td>
                        <td class="long-text">${formatValue(afterArr[i]??'-')}</td>
                   </tr>`;
        }
        html+='</tbody></table></div>';
        return html;
    }

    function renderProductChanges(before,after){
        let html='';
        if(before.product||after.product)
            html+=renderSection('Product Info',before.product||{},after.product||{});
        if(before.images||after.images)
            html+=renderArraySection('Images',before.images||[],after.images||[]);
        if(before.attributes||after.attributes)
            html+=renderArraySection('Attributes',before.attributes||[],after.attributes||[]);
        if(before.attribute_values||after.attribute_values)
            html+=renderArraySection('Attribute Values',before.attribute_values||[],after.attribute_values||[]);
        return html;
    }

    function renderBeforeAfter(before,after){
        return renderSection('Details',before,after);
    }

    function renderGeneric(changes){
        return `<div class="table-responsive"><pre class="bg-light p-2" style="max-height:500px;overflow:auto;">${JSON.stringify(changes,null,4)}</pre></div>`;
    }

    function renderUserSingle(user){
        return `
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr><th width="30%">ID</th><td>${user.id ?? '-'}</td></tr>
                        <tr><th>Name</th><td>${user.name ?? '-'}</td></tr>
                        <tr><th>Email</th><td>${user.email ?? '-'}</td></tr>
                        <tr><th>Role</th><td>${user.role ?? '-'}</td></tr>
                    </tbody>
                </table>
            </div>
        `;
    }

    /* --------- Main Renderer --------- */
    function renderChanges(changes){
        let html='';
        if(changes.before && changes.after && changes.before.product){
            html+=renderProductChanges(changes.before,changes.after);
        } else if(changes.newData){
            html+=renderProductChanges(changes.newData, {});
        } else if(changes.user && typeof changes.user === 'object'){
            html += renderUserSingle(changes.user);
        } else if(changes.before || changes.after){
            html+=renderBeforeAfter(changes.before||{},changes.after||{});
        } else if(changes.ids && Array.isArray(changes.ids)){
            html+=`<h5>Affected IDs</h5><ul>${changes.ids.map(id=>`<li>ID: ${id}</li>`).join('')}</ul>`;
        } else {
            html+=renderGeneric(changes);
        }
        return html;
    }

    container.innerHTML=renderChanges(changes);

</script>
@endpush
