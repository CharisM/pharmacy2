@php
    $currentRoute = request()->route()->getName();
@endphp

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">💊</div>
            <div class="logo-text">
                <div class="logo-main">Medicare</div>
                <div class="logo-sub">Pharmacy</div>
            </div>
        </div>
    </div>

    <div class="profile-card">
        <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="profile-avatar" />
        <div class="profile-details">
            <h3>{{ auth()->user()->name }}</h3>
            <p>Administrator</p>
        </div>
    </div>

    <div class="realtime-clock">
        <div class="clock-time" id="clockTime">--:--:--</div>
        <div class="clock-date" id="clockDate">Loading...</div>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-label">MENU</span>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="#" class="nav-link {{ $currentRoute === 'admin.products' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
            </svg>
            <span>Products</span>
        </a>

        <a href="#" class="nav-link {{ $currentRoute === 'admin.orders' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 17H5a2 2 0 0 0-2 2v0a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2h-4"/>
                <path d="M12 3v14"/><path d="M8 7l4-4 4 4"/>
            </svg>
            <span>Orders</span>
        </a>

        <a href="{{ route('admin.users') }}"
           class="nav-link {{ $currentRoute === 'admin.users' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Users</span>
        </a>

        <a href="#" class="nav-link {{ $currentRoute === 'admin.settings' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span>Settings</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
           class="logout-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Sign Out
        </a>
    </div>
</aside>

<form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

<style>
    .admin-sidebar {
        background: linear-gradient(180deg, #0f2050 0%, #1e3a8a 60%, #1d4ed8 100%);
        width: 260px;
        min-height: 100vh;
        position: fixed;
        top: 0; left: 0;
        display: flex;
        flex-direction: column;
        padding: 20px 14px;
        gap: 20px;
        box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        z-index: 100;
        overflow-y: auto;
    }

    .sidebar-header {
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon { font-size: 28px; }

    .logo-main {
        color: #fff;
        font-weight: 800;
        font-size: 1.05rem;
        line-height: 1.1;
    }

    .logo-sub {
        color: #93c5fd;
        font-weight: 600;
        font-size: 0.8rem;
        line-height: 1.1;
    }

    .profile-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: rgba(255,255,255,0.08);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.12);
    }

    .profile-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #60a5fa;
        flex-shrink: 0;
    }

    .profile-details h3 {
        margin: 0;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .profile-details p {
        margin: 2px 0 0;
        color: #93c5fd;
        font-size: 0.75rem;
    }

    /* Real-time Clock */
    .realtime-clock {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 12px 14px;
        text-align: center;
    }

    .clock-time {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 2px;
        font-variant-numeric: tabular-nums;
        font-family: 'Courier New', monospace;
    }

    .clock-date {
        color: #93c5fd;
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Nav */
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .nav-label {
        color: rgba(255,255,255,0.4);
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 0 10px 8px;
        margin-bottom: 2px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 13px;
        border-radius: 10px;
        color: rgba(255,255,255,0.65);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
        transform: translateX(3px);
    }

    .nav-link.active {
        background: rgba(96,165,250,0.2);
        color: #93c5fd;
        border-left: 3px solid #60a5fa;
        padding-left: 10px;
    }

    .nav-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* Footer */
    .sidebar-footer {
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px;
        background: rgba(239,68,68,0.15);
        border: 1px solid rgba(239,68,68,0.3);
        color: #fca5a5;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }

    .logout-btn:hover {
        background: rgba(239,68,68,0.28);
        border-color: rgba(239,68,68,0.5);
        color: #fff;
    }
</style>

<script>
    (function () {
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        function tick() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clockTime').textContent = `${h}:${m}:${s}`;
            document.getElementById('clockDate').textContent =
                `${days[now.getDay()]}, ${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
