<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:var(--txt);">Manage Users</h2>
            <a href="{{ route('admin.users.create') }}" style="display:inline-block; background-color:var(--gold); color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                + New User
            </a>
        </div>
    </x-slot>

    <style>
        .alert-success { background-color: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.25); color: #4ade80; font-size: 0.8rem; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; }
        [data-theme="light"] .alert-success { background-color: rgba(22,163,74,0.08); border-color: rgba(22,163,74,0.3); color: #166534; }
        .table-wrap { background-color: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background-color: var(--table-head); border-bottom: 1px solid var(--border); }
        .data-table th { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); padding: 0.85rem 1.25rem; text-align: left; }
        .data-table td { font-size: 0.82rem; color: var(--txt-2); padding: 0.9rem 1.25rem; border-bottom: 1px solid var(--border-2); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: var(--row-hover); }
        .role-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 3px; }
        .role-admin   { background-color: rgba(201,168,76,0.15); color: var(--gold); }
        .role-supervisor { background-color: rgba(96,165,250,0.12); color: #60a5fa; }
        .role-viewer  { background-color: rgba(255,255,255,0.06); color: var(--muted); }
        [data-theme="light"] .role-viewer { background-color: rgba(0,0,0,0.06); }
        .action-link { font-size: 0.75rem; font-weight: 500; text-decoration: none; color: var(--gold); margin-right: 0.75rem; }
        .action-link:hover { text-decoration: underline; }
        .action-delete { font-size: 0.75rem; font-weight: 500; color: #f87171; background: none; border: none; cursor: pointer; font-family: 'Inter', sans-serif; padding: 0; }
        [data-theme="light"] .action-delete { color: #dc2626; }
        .action-delete:hover { text-decoration: underline; }
        .table-empty { text-align: center; color: var(--muted-2); font-size: 0.82rem; padding: 3rem 1rem; }
        .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid var(--border-2); }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Desktop Table --}}
    <div class="table-wrap desktop-table-wrap">
        <div class="table-scroll-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td style="color:var(--muted);">{{ $user->email }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td style="color:var(--muted-2);">{{ $user->created_at->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-link">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="pagination-wrap">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Mobile Card List --}}
    <div class="mobile-card-list">
        @forelse($users as $user)
            <div class="mobile-card">
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Name</span>
                    <span class="mobile-card-value" style="font-weight:600;">{{ $user->name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Email</span>
                    <span class="mobile-card-value" style="color:var(--muted); font-size:0.75rem;">{{ $user->email }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Role</span>
                    <span class="mobile-card-value"><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Joined</span>
                    <span class="mobile-card-value" style="color:var(--muted-2);">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="mobile-card-actions">
                    <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="mobile-card" style="text-align:center; color:var(--muted-2);">No users found.</div>
        @endforelse
        @if($users->hasPages())
            <div style="margin-top:1rem;">{{ $users->links() }}</div>
        @endif
    </div>

</x-app-layout>
