<div class="mobile-menu bg-secondary fixed-bottom start-50 translate-middle rounded" role="menu">
    <div class="d-flex justify-content-between">
        <a href="{{ route('home.index') }}">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-home"></i>
            </div>
        </a>
        <button class="btn btn-transparent p-0"
            @click="document.documentElement.dataset.mobileMenu !== 'nav' ? document.documentElement.dataset.mobileMenu='nav' : delete document.documentElement.dataset.mobileMenu">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-menu"></i>
            </div>
        </button>
        <button class="btn btn-transparent p-0">
            <div class="d-flex align-items-center text-white">
                <i class="fs-4 ti ti-user"></i>
            </div>
        </button>
    </div>
</div>


<html>

<body>
    <div>
        <aside role="complementary"aria-label="Menu">
            <nav role="navigation">
                <ul>
                    <li><a href="/home">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contat">Contact</a></li>
                </ul>
                <ul>
                    <li><a href="/agents">Agents</a></li>
                    <li><a href="/library">Library</a></li>
                </ul>
                <ul>
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/settings">Settings</a></li>
                    <li><a href="/logout}">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <div>
            <main role="main" aria-label="Main">
                <section>Main content</section>
            </main>
            <footer aria-label="Footer">
                <p>Footer content</p>
            </footer>
        </div>
    </div>
    <div class="d-none" aria-label="Mobile menu">
        <nav role="navigation">
            <ul>
                <li><a href="/home">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contat">Contact</a></li>
            </ul>
            <ul>
                <li><a href="/agents">Agents</a></li>
                <li><a href="/library">Library</a></li>
            </ul>
            <ul>
                <li><a href="/profile">Profile</a></li>
                <li><a href="/settings">Settings</a></li>
                <li><a href="/logout}">Logout</a></li>
            </ul>
        </nav>
    </div>
    <div role="alert" aria-live="assertive">
        <p>Alert message</p>
    </div>
</body>

</html>
