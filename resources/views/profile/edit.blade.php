<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:0.95rem; font-weight:600; color:var(--txt);">Profile</h2>
    </x-slot>

    <style>
        .profile-section {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.75rem;
            margin-bottom: 1.25rem;
            max-width: 560px;
        }
        .profile-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--txt);
            margin-bottom: 0.35rem;
        }
        .profile-section-desc {
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .profile-form-group { margin-bottom: 1.1rem; }
        .profile-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.4rem;
        }
        .profile-input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--txt);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            padding: 0.6rem 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .profile-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 2px rgba(201,168,76,0.1);
        }
        .profile-error { font-size: 0.72rem; color: #f87171; margin-top: 0.3rem; }
        [data-theme="light"] .profile-error { color: #dc2626; }
        .profile-btn {
            background-color: var(--gold);
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
        .profile-btn:hover { background-color: var(--gold-lt); }
        .profile-btn-danger {
            background-color: transparent;
            color: #f87171;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid rgba(248,113,113,0.35);
            border-radius: 3px;
            padding: 0.6rem 1.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        [data-theme="light"] .profile-btn-danger { color: #dc2626; border-color: rgba(220,38,38,0.35); }
        .profile-btn-danger:hover { background-color: rgba(248,113,113,0.08); }
        .profile-saved { font-size: 0.78rem; color: #4ade80; margin-left: 0.75rem; }
        [data-theme="light"] .profile-saved { color: #166534; }
        .profile-alert-success { background-color: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.25); color: #4ade80; font-size: 0.8rem; padding: 0.65rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
        [data-theme="light"] .profile-alert-success { background-color: rgba(22,163,74,0.08); border-color: rgba(22,163,74,0.3); color: #166534; }
        .profile-divider { border: none; border-top: 1px solid var(--border-2); margin: 1.25rem 0; }
        .profile-confirm-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .profile-confirm-modal.open { display: flex; }
        .profile-confirm-box {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 2rem;
            max-width: 420px;
            width: 100%;
        }
        .profile-confirm-box h3 { font-size: 1rem; font-weight: 600; color: var(--txt); margin-bottom: 0.5rem; }
        .profile-confirm-box p { font-size: 0.82rem; color: var(--muted); margin-bottom: 1.5rem; line-height: 1.6; }
        .profile-confirm-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        @media (max-width: 640px) {
            .profile-section { padding: 1.25rem; }
            .profile-confirm-actions { flex-direction: column; }
            .profile-btn, .profile-btn-danger { width: 100%; text-align: center; }
        }
    </style>

    <!-- Update Profile Information -->
    <div class="profile-section">
        <h3 class="profile-section-title">Profile Information</h3>
        <p class="profile-section-desc">Update your account name and email address.</p>

        @if(session('status') === 'profile-updated')
            <div class="profile-alert-success">Profile updated successfully.</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="profile-form-group">
                <label class="profile-label" for="name">Full Name</label>
                <input id="name" name="name" type="text" class="profile-input" value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label" for="email">Email Address</label>
                <input id="email" name="email" type="email" class="profile-input" value="{{ old('email', $user->email) }}" required>
                @error('email')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-top:1.5rem;">
                <button type="submit" class="profile-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="profile-section">
        <h3 class="profile-section-title">Update Password</h3>
        <p class="profile-section-desc">Use a strong password of at least 8 characters.</p>

        @if(session('status') === 'password-updated')
            <div class="profile-alert-success">Password updated successfully.</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="profile-form-group">
                <label class="profile-label" for="current_password">Current Password</label>
                <input id="current_password" name="current_password" type="password" class="profile-input" autocomplete="current-password">
                @error('current_password', 'updatePassword')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label" for="password">New Password</label>
                <input id="password" name="password" type="password" class="profile-input" autocomplete="new-password">
                @error('password', 'updatePassword')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div class="profile-form-group">
                <label class="profile-label" for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="profile-input" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')<p class="profile-error">{{ $message }}</p>@enderror
            </div>

            <div style="margin-top:1.5rem;">
                <button type="submit" class="profile-btn">Update Password</button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="profile-section">
        <h3 class="profile-section-title">Delete Account</h3>
        <p class="profile-section-desc">Once your account is deleted, all data will be permanently removed. This action cannot be undone.</p>

        <button type="button" class="profile-btn-danger" onclick="document.getElementById('deleteModal').classList.add('open')">
            Delete Account
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="profile-confirm-modal" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="profile-confirm-box">
            <h3>Confirm Account Deletion</h3>
            <p>Are you sure you want to delete your account? Enter your password to confirm. This action is permanent and cannot be undone.</p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="profile-form-group">
                    <label class="profile-label" for="delete_password">Password</label>
                    <input id="delete_password" name="password" type="password" class="profile-input" placeholder="Enter your password">
                    @error('password', 'userDeletion')<p class="profile-error">{{ $message }}</p>@enderror
                </div>
                <div class="profile-confirm-actions">
                    <button type="submit" class="profile-btn-danger">Yes, Delete My Account</button>
                    <button type="button" class="profile-btn" onclick="document.getElementById('deleteModal').classList.remove('open')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
