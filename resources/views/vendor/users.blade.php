@extends('layouts.vendor')

@section('title', 'Users')
@section('page_title', 'User Control Center')
@section('page_subtitle', 'Full super admin control for all user accounts')

@section('content')
    <div class="stats-grid" style="margin-bottom: 18px;">
        <div class="stats-card">
            <div class="stats-card-icon customers"><i class="fa-solid fa-users"></i></div>
            <div class="stats-card-info">
                <h3>Total Users</h3>
                <h2>{{ $summary['total'] }}</h2>
                <p>All registered accounts</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stats-card-icon orders"><i class="fa-solid fa-user-shield"></i></div>
            <div class="stats-card-info">
                <h3>Admins + Vendors</h3>
                <h2>{{ $summary['admins'] + $summary['vendors'] }}</h2>
                <p>{{ $summary['admins'] }} admins, {{ $summary['vendors'] }} vendors</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stats-card-icon products"><i class="fa-solid fa-user-check"></i></div>
            <div class="stats-card-info">
                <h3>Active Users</h3>
                <h2>{{ $summary['total'] - $summary['blocked'] }}</h2>
                <p>Can log in right now</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stats-card-icon sales"><i class="fa-solid fa-user-xmark"></i></div>
            <div class="stats-card-info">
                <h3>Blocked Users</h3>
                <h2>{{ $summary['blocked'] }}</h2>
                <p>Login currently disabled</p>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header sa-users-header">
            <div>
                <h3>All User Accounts</h3>
                <p class="card-subtext">Create, impersonate, reset passwords, change roles, block, and remove</p>
            </div>
            <div class="sa-users-header-tools">
                <input type="text" id="userSearchInput" class="form-control admin-search-input" placeholder="Search by name, email or role">
                <button type="button" class="sa-btn sa-btn-primary" data-sa-modal="#saUserCreateModal">
                    <i class="fa-solid fa-user-plus"></i> New User
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Activity</th>
                        <th>Phone</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $index => $managedUser)
                        @php
                            $activityText = match ($managedUser->role) {
                                'customer' => $managedUser->orders_count . ' orders',
                                'delivery' => $managedUser->delivery_orders_count . ' assigned',
                                default => '-',
                            };
                        @endphp
                        <tr class="{{ $managedUser->is_active ? '' : 'user-row-inactive' }}" data-search="{{ strtolower($managedUser->name . ' ' . $managedUser->email . ' ' . $managedUser->role) }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="customer-avatar" style="width:36px;height:36px;font-size:14px;">
                                        {{ strtoupper(substr($managedUser->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $managedUser->name }}</strong>
                                        @if($managedUser->id === auth()->id())
                                            <span class="self-pill">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $managedUser->email }}</td>
                            <td>
                                <select
                                    class="admin-role-select js-admin-role-select"
                                    data-url="{{ route('vendor.users.update', $managedUser->id) }}"
                                    data-previous="{{ $managedUser->role }}"
                                    {{ $managedUser->id === auth()->id() ? 'disabled' : '' }}
                                >
                                    @foreach(['admin', 'vendor', 'delivery', 'customer'] as $role)
                                        <option value="{{ $role }}" {{ $managedUser->role === $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>{{ $activityText }}</td>
                            <td>{{ $managedUser->number ?? 'N/A' }}</td>
                            <td>{{ $managedUser->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="user-status-wrap">
                                    <label class="status-switch">
                                        <input
                                            type="checkbox"
                                            class="js-admin-status-toggle"
                                            data-url="{{ route('vendor.users.update', $managedUser->id) }}"
                                            {{ $managedUser->is_active ? 'checked' : '' }}
                                            {{ $managedUser->id === auth()->id() ? 'disabled' : '' }}
                                        >
                                        <span class="status-slider"></span>
                                    </label>
                                    <span class="user-status-pill js-user-status-label {{ $managedUser->is_active ? 'active' : 'blocked' }}">
                                        {{ $managedUser->is_active ? 'Active' : 'Blocked' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="sa-actions">
                                    @if($managedUser->id !== auth()->id())
                                        <a href="{{ route('vendor.users.impersonate', $managedUser->id) }}"
                                           class="sa-btn sa-btn-ghost" title="Login as this user">
                                            <i class="fa-solid fa-user-secret"></i>
                                        </a>
                                    @endif
                                    <button type="button" class="sa-btn sa-btn-ghost js-sa-reset-pass"
                                        title="Reset password"
                                        data-url="{{ route('vendor.users.password', $managedUser->id) }}"
                                        data-name="{{ $managedUser->name }}">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="sa-btn sa-btn-danger js-admin-delete-btn"
                                        data-url="{{ route('vendor.users.destroy', $managedUser->id) }}"
                                        data-name="{{ $managedUser->name }}"
                                        {{ $managedUser->id === auth()->id() ? 'disabled' : '' }}
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding: 24px; color: #8a7769;">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create user modal --}}
    <div class="sa-modal" id="saUserCreateModal">
        <div class="sa-modal-dialog">
            <form method="POST" action="{{ route('vendor.users.store') }}">
                @csrf
                <div class="sa-modal-head">
                    <h3>Create New User</h3>
                    <button type="button" class="sa-modal-close" data-sa-close>&times;</button>
                </div>
                <div class="sa-modal-body sa-grid-2">
                    <div>
                        <label>Full Name</label>
                        <input class="sa-input" type="text" name="name" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input class="sa-input" type="email" name="email" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input class="sa-input" type="text" name="password" required>
                    </div>
                    <div>
                        <label>Role</label>
                        <select class="sa-input" name="role" required>
                            <option value="customer">Customer</option>
                            <option value="delivery">Delivery Boy</option>
                            <option value="vendor">Vendor</option>
                            <option value="admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input class="sa-input" type="text" name="number">
                    </div>
                    <div>
                        <label>Address</label>
                        <input class="sa-input" type="text" name="address">
                    </div>
                </div>
                <div class="sa-modal-foot">
                    <button type="button" class="sa-btn sa-btn-ghost" data-sa-close>Cancel</button>
                    <button type="submit" class="sa-btn sa-btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/super-admin.js') }}"></script>
    <script src="{{ asset('js/vendor/users.js') }}"></script>
    <script>
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        document.querySelectorAll('.js-sa-reset-pass').forEach(btn => {
            btn.addEventListener('click', async () => {
                const newPass = prompt(`Set a new password for ${btn.dataset.name}:`);
                if (!newPass || newPass.length < 6) {
                    if (newPass !== null) alert('Password must be at least 6 characters.');
                    return;
                }
                try {
                    const res = await fetch(btn.dataset.url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ password: newPass }),
                    });
                    const data = await res.json();
                    alert(data.message || (data.success ? 'Password updated.' : 'Failed.'));
                } catch (e) {
                    alert('Request failed: ' + e.message);
                }
            });
        });
    })();
    </script>
@endpush
