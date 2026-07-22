<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'ADMIN' }} // CCDI TACTICAL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
</head>
<body class="bg-background-primary min-h-screen w-full lg:p-4 flex items-start lg:items-center justify-center overflow-x-hidden">

    <!-- Background Technical Layer -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03]"
         style="background-image: radial-gradient(var(--color-text-primary) 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.02]"
         style="background-image: linear-gradient(0deg, transparent 24%, var(--color-border) 25%, var(--color-border) 26%, transparent 27%, transparent 74%, var(--color-border) 75%, var(--color-border) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, var(--color-border) 25%, var(--color-border) 26%, transparent 27%, transparent 74%, var(--color-border) 75%, var(--color-border) 76%, transparent 77%, transparent); background-size: 100px 100px;"></div>

    <!-- MAIN CONTINUOUS FRAME -->
    <div class="tactical-frame w-full min-h-screen lg:min-h-0 lg:h-full max-w-[1700px] lg:max-h-[950px] z-10">

        <!-- HEADER / SYSTEM STATUS -->
        <header class="h-16 lg:h-20 border-b border-border flex items-center justify-between px-4 lg:px-10 shrink-0 bg-background-secondary/50 relative">
            <div class="flex items-center gap-4 lg:gap-12">
                <!-- Mobile Menu Button -->
                <button id="sidebar-toggle" class="lg:hidden w-10 h-10 border border-border flex items-center justify-center text-text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>

                <div class="flex items-center gap-4 lg:gap-6">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 bg-accent flex items-center justify-center -skew-x-12">
                        <span class="text-white font-bebas text-xl lg:text-2xl skew-x-12">V</span>
                    </div>
                    <div>
                        <h1 class="text-xl lg:text-3xl tracking-[0.2em] leading-none mb-1">CCDI</h1>
                        <div class="text-[7px] lg:text-[9px] font-mono text-text-secondary tracking-[0.4em] uppercase">Operations Interface</div>
                    </div>
                </div>

                <div class="hidden lg:flex gap-10 border-l border-border pl-12 h-10 items-center">
                    <div class="flex flex-col">
                        <span class="text-[8px] font-mono text-text-secondary uppercase tracking-widest mb-1">System Status</span>
                        <span class="text-[10px] font-mono text-success uppercase tracking-widest animate-pulse">● Online // Operational</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] font-mono text-text-secondary uppercase tracking-widest mb-1">Auth Operator</span>
                        <span class="text-[10px] font-mono text-text-primary uppercase tracking-widest">VAL-OP-992 [ADMIN]</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 lg:gap-8">
                <button type="button" data-onboarding-start="admin" class="hidden sm:block border border-border px-3 py-2 text-[9px] font-mono uppercase tracking-widest text-text-secondary hover:border-accent hover:text-white transition-colors">Guided Tour</button>
                <div class="flex flex-col text-right">
                    <span class="text-[8px] font-mono text-text-secondary uppercase tracking-widest mb-1">Station Clock</span>
                    <span id="admin-station-clock" class="text-[10px] lg:text-xs font-mono text-text-primary tracking-widest uppercase">00:00:00 AM</span>
                </div>
                <div class="hidden xs:flex h-8 w-8 lg:h-10 lg:w-10 border border-border bg-panel p-1 items-center justify-center">
                     <img src="https://ui-avatars.com/api/?name=A&background=161B22&color=F4F6F8" class="w-full h-full opacity-60">
                </div>
            </div>
        </header>

        <!-- MAIN BODY (SIDEBAR + CONTENT) -->
        <div class="flex-1 flex overflow-hidden relative">

            <!-- SIDEBAR OVERLAY -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-background-primary/80 z-40 lg:hidden hidden"></div>

            <!-- SIDEBAR -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 border-r border-border bg-background-secondary z-50 transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:flex lg:flex-col lg:bg-background-secondary/30 shrink-0">
                <div class="px-8 pt-10 pb-4 flex justify-between items-center">
                    <div class="text-[10px] font-mono text-accent uppercase tracking-[0.4em] mb-8 lg:mb-8 opacity-70">Main Systems</div>
                    <button id="sidebar-close" class="lg:hidden text-text-secondary hover:text-white mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto custom-scrollbar">
                    <ul class="space-y-1">
                        <x-nav-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-item>
                        <x-nav-item :href="route('contests')" :active="request()->routeIs('contests*')">Contests</x-nav-item>
                        <x-nav-item :href="route('exposures')" :active="request()->routeIs('exposures*')">Exposures</x-nav-item>
                        <x-nav-item :href="route('contestants')" :active="request()->routeIs('contestants*')">Contestants</x-nav-item>
                        <x-nav-item :href="route('judges')" :active="request()->routeIs('judges*')">Judges</x-nav-item>
                        <x-nav-item :href="route('tabulation')" :active="request()->routeIs('tabulation*')">Tabulation</x-nav-item>
                        <x-nav-item :href="route('results')" :active="request()->routeIs('results*')">Results & Reports</x-nav-item>
                        <x-nav-item :href="route('admin.accounts')" :active="request()->routeIs('admin.accounts*')">Operator Accounts</x-nav-item>
                        <x-nav-item :href="route('guide')" :active="request()->routeIs('guide*')">System Guide</x-nav-item>
                    </ul>
                </nav>

                <div class="p-8 border-t border-border bg-background-primary/20">
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 text-[10px] font-mono text-danger uppercase tracking-[0.3em] hover:text-white transition-colors group">
                            <span class="w-1.5 h-1.5 bg-danger"></span>
                            Terminate Session
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-y-auto custom-scrollbar bg-background-primary/10 relative p-4 lg:p-10">
                {{ $slot }}
            </main>

            <!-- Workspace Right Panel (Optional) -->
            @if(isset($rightPanel))
            <aside class="hidden xl:block w-96 border-l border-border bg-background-secondary/50 shrink-0 overflow-y-auto custom-scrollbar">
                {{ $rightPanel }}
            </aside>
            @endif
        </div>

        <!-- FOOTER / STATUS BAR -->
        <footer class="h-12 lg:h-10 border-t border-border bg-background-secondary flex flex-col lg:flex-row items-center px-4 lg:px-10 justify-center lg:justify-between shrink-0 relative z-20 gap-2 lg:gap-0">
            <div class="flex gap-4 lg:gap-10">
                <div class="flex items-center gap-3 text-[7px] lg:text-[9px] font-mono text-text-secondary tracking-widest uppercase">
                    <span class="w-1 lg:w-1.5 h-1 lg:h-1.5 bg-success rounded-full"></span>
                    Enc: AES-256-GCM
                </div>
                <div class="flex items-center gap-3 text-[7px] lg:text-[9px] font-mono text-text-secondary tracking-widest uppercase">
                    <span class="text-text-primary">Latency:</span>
                    <span class="text-success">24ms</span>
                </div>
            </div>
            <div class="text-[7px] lg:text-[9px] font-mono text-text-secondary tracking-widest uppercase text-center">
                CCDI Tactical Operations Center // version 1.0.4-Stable
            </div>
        </footer>
    </div>

    <!-- Technical Glitch Overlay (barely visible) -->
    <div class="scanline-overlay"></div>

    <x-onboarding mode="admin" />

    @stack('scripts')
    <script>
        function updateAdminClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            const el = document.getElementById('admin-station-clock');
            if (el) el.innerText = timeString;
        }
        setInterval(updateAdminClock, 1000);
        updateAdminClock();

        // Mobile Sidebar Toggle
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (toggleBtn && sidebar && overlay) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });
        }

        if (closeBtn && sidebar && overlay) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        if (overlay && sidebar) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>


