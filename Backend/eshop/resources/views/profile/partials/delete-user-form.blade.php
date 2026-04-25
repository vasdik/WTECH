<section>
    <h5 class="mb-2 text-danger">Delete Account</h5>
    <p class="text-secondary small mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted.
    </p>

    <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
        @method('DELETE')

        <div class="mb-3">
            <label for="delete_password" class="form-label">Confirm your password</label>
            <input
                id="delete_password"
                    name="password"
                    type="password"
                class="form-control input-rounded @error('password', 'userDeletion') is-invalid @enderror"
                placeholder="Password"
            >
            @error('password', 'userDeletion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>

        <button
            type="submit"
            class="btn btn-danger"
            onclick="return confirm('Are you sure you want to delete your account?')"
        >
            Delete Account
        </button>
        </form>
    </section>