<div class="d-flex justify-content-between mb-3">
    <h5>Dashboard</h5>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">
            Logout
        </button>
    </form>
</div>
