<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">Manage Users</h2>
            <a href="{{ route('admin.users.create') }}" style="display:inline-block; background-color:#c9a84c; color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                + New User
            </a>
        </div>
    </x-slot>

    <style>
        .alert-success {
            background-color: rgba(74,222,128,0.08);
            border: 1px solid rgba(74,222,128,0.25);
            color: #4ade80;
            font-size: 0.8rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.25rem;
        }
        .table-wrap {
            background-color: #0c1c30;
            border: 1px solid rgba(201,168,76,0.18);
            border-radius: 8px;
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead tr {
            background-color: #07111f;
            border-bottom: 1px solid rgba(201,168,76,0.15);
        }
        .data-table th {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7a99;
            padding: 0.85rem 1.25rem;
            text-align: left;
        }
        .data-table td {
            font-size: 0.82rem;
            color: #c8cfe0;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: rgba(255,255,255,0.02); }
        .role-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.2rem 0.6rem;
            border-radius: 3px;
        }
        .role-admin   { background-color: rgba(201,168,76,0.15); color: #c9a84c; }
        .role-supervisor { background-color: rgba(96,165,250,0.12); color: #60a5fa; }
        .role-viewer  { background-color: rgba(255,255,255,0.06); color: #6b7a99; }
        .action-link {
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            color: #c9a84c;
            margin-right: 0.75rem;
        }
        .action-link:hover { text-decoration: underline; }
        .action-delete {
            font-size: 0.75rem;
            font-weight: 500;
            color: #f87171;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            padding: 0;
        }
        .action-delete:hover { text-decoration: underline; }
        .table-empty {
            text-align: center;
            color: #4a5a72;
            font-size: 0.82rem;
            padding: 3rem 1rem;
        }
        .pagination-wrap {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.04);
        }
        /* Override Breeze pagination */
        .pagination-wrap nav span, .pagination-wrap nav a {
            font-size: 0.75rem;
            color: #6b7a99;
        }
        .pagination-wrap nav a:hover { color: #c9a84c; }
        @media (max-width: 640px) {
            .data-table th:nth-child(4),
            .data-table td:nth-child(4) { display: none; }
        }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
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
                        <td style="color:#6b7a99;">{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td style="color:#4a5a72;">{{ $user->created_at->format('d M Y') }}</td>
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
        @if($users->hasPages())
            <div class="pagination-wrap">{{ $users->links() }}</div>
        @endif
    </div>

</x-app-layout>
