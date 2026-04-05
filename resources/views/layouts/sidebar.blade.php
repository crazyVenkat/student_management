<div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">

    <h4 class="text-center mb-4">Admin Panel</h4>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="{{ route('students.index') }}" class="nav-link text-white">
                🎓 Students
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('staff.index') }}" class="nav-link text-white">
                👨‍🏫 Staff
            </a>
        </li>

        <hr class="bg-light">

        <li class="nav-item">
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger">
                    Logout
                </button>
            </form>
        </li>

    </ul>
</div>
