<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">Create New User</h2>
    </x-slot>

    <style>
        .form-card {
            background-color: #0c1c30;
            border: 1px solid rgba(201,168,76,0.18);
            border-radius: 8px;
            padding: 2rem;
            max-width: 560px;
        }
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #6b7a99;
            margin-bottom: 0.4rem;
        }
        .form-input {
            width: 100%;
            background-color: #07111f;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 4px;
            color: #e8eaf0;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            padding: 0.6rem 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            border-color: #c9a84c;
            box-shadow: 0 0 0 2px rgba(201,168,76,0.1);
        }
        .form-input option { background-color: #0c1c30; }
        .form-error {
            font-size: 0.72rem;
            color: #f87171;
            margin-top: 0.35rem;
        }
        .form-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.75rem;
            flex-wrap: wrap;
        }
        .btn-submit {
            background-color: #c9a84c;
            color: #07111f;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: none;
            border-radius: 3px;
            padding: 0.6rem 1.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-submit:hover { background-color: #e2c97e; }
        .btn-cancel {
            font-size: 0.78rem;
            color: #6b7a99;
            text-decoration: none;
            padding: 0.6rem 1rem;
        }
        .btn-cancel:hover { color: #e8eaf0; }
    </style>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-input" required>
                    <option value="">— Select Role —</option>
                    <option value="admin"      {{ old('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
                    <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="viewer"     {{ old('role') === 'viewer'     ? 'selected' : '' }}>Viewer</option>
                </select>
                @error('role')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" class="form-input" type="password" name="password" required>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

</x-app-layout>
