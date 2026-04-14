@extends('layouts.vendor')

@section('title', 'System Tools')
@section('page_title', 'System & Developer Tools')
@section('page_subtitle', 'Artisan commands, migrations and logs')

@section('content')
    <div class="sa-system-grid">
        <div class="sa-stat-card">
            <i class="fa-brands fa-php"></i>
            <div>
                <span>PHP Version</span>
                <strong>{{ $phpVersion }}</strong>
            </div>
        </div>
        <div class="sa-stat-card">
            <i class="fa-solid fa-bolt"></i>
            <div>
                <span>Laravel Version</span>
                <strong>{{ $laravelVersion }}</strong>
            </div>
        </div>
        <div class="sa-stat-card">
            <i class="fa-solid fa-server"></i>
            <div>
                <span>Environment</span>
                <strong>{{ $env }}</strong>
            </div>
        </div>
        <div class="sa-stat-card">
            <i class="fa-solid fa-database"></i>
            <div>
                <span>Storage Used</span>
                <strong>{{ $diskSizeMb }} MB</strong>
            </div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-head">
            <div>
                <h3><i class="fa-solid fa-wrench"></i> Quick Actions</h3>
                <p>One-click maintenance operations</p>
            </div>
        </div>
        <div class="sa-card-body">
            <div class="sa-quick-actions">
                <button type="button" class="sa-action-btn" data-sa-action="cache_clear">
                    <i class="fa-solid fa-broom"></i>
                    <span>Clear All Caches</span>
                    <small>cache / config / view / route</small>
                </button>
                <button type="button" class="sa-action-btn" data-sa-action="optimize">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Optimize App</span>
                    <small>Build route & config cache</small>
                </button>
                <button type="button" class="sa-action-btn" data-sa-action="migrate">
                    <i class="fa-solid fa-database"></i>
                    <span>Run Migrations</span>
                    <small>php artisan migrate --force</small>
                </button>
                <button type="button" class="sa-action-btn" data-sa-action="storage_link">
                    <i class="fa-solid fa-link"></i>
                    <span>Storage Link</span>
                    <small>Link public/storage</small>
                </button>
                <button type="button" class="sa-action-btn sa-action-danger" data-sa-action="clear_logs">
                    <i class="fa-solid fa-trash"></i>
                    <span>Clear Log File</span>
                    <small>Empties laravel.log</small>
                </button>
            </div>
            <pre id="saActionOutput" class="sa-terminal">Ready.</pre>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-head">
            <div>
                <h3><i class="fa-solid fa-list-check"></i> Migration Status</h3>
                <p>All tracked migrations</p>
            </div>
        </div>
        <div class="sa-card-body">
            <pre class="sa-terminal">{{ $migrationStatus }}</pre>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-head">
            <div>
                <h3><i class="fa-solid fa-file-lines"></i> Last 200 Log Lines</h3>
                <p>Tail of storage/logs/laravel.log</p>
            </div>
        </div>
        <div class="sa-card-body">
            <pre class="sa-terminal sa-terminal-tall">{{ $logTail ?: 'Log file is empty.' }}</pre>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const out = document.getElementById('saActionOutput');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('[data-sa-action]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const action = btn.dataset.saAction;
            if (action === 'clear_logs' && !confirm('Clear the log file?')) return;

            out.textContent = '> Running ' + action + '...';
            btn.disabled = true;

            try {
                const res = await fetch('{{ route("vendor.system.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ action }),
                });
                const data = await res.json();
                out.textContent = (data.success ? '✓ ' : '✗ ') + data.message + '\n\n' + (data.output || '');
            } catch (e) {
                out.textContent = '✗ ' + e.message;
            } finally {
                btn.disabled = false;
            }
        });
    });
})();
</script>
@endpush
