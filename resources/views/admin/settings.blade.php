<x-admin-layout>
    <x-slot name="title">System Settings</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary">SYSTEM SETTINGS</span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">SYSTEM SETTINGS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Global configuration for the tabulation system.</p>
            </div>
            <button class="tactical-button bg-success">
                SAVE SYSTEM CONFIG
            </button>
        </div>

        <div class="space-y-8">
            <div class="tactical-card p-8">
                <h3 class="text-xl mb-6">APPEARANCE & INTERFACE</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Color Accent</label>
                        <div class="flex gap-4">
                            @foreach(['#FF4655' => 'VALORANT RED', '#00D084' => 'SUCCESS GREEN', '#F5B941' => 'WARNING GOLD', '#3B82F6' => 'INFO BLUE'] as $color => $name)
                            <button class="w-12 h-12 border-2 {{ $color == '#FF4655' ? 'border-accent' : 'border-transparent' }} flex items-center justify-center p-1">
                                <div class="w-full h-full" style="background-color: {{ $color }}"></div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">UI Density</label>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-accent/10 border border-accent text-[10px] font-mono text-accent uppercase tracking-widest">COMPACT</button>
                            <button class="px-4 py-2 border border-border text-[10px] font-mono text-text-secondary uppercase tracking-widest hover:border-accent transition-colors">DEFAULT</button>
                            <button class="px-4 py-2 border border-border text-[10px] font-mono text-text-secondary uppercase tracking-widest hover:border-accent transition-colors">SPACIOUS</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tactical-card p-8">
                <h3 class="text-xl mb-6">SECURITY & AUTHENTICATION</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-background-secondary/50 border border-border">
                        <div>
                            <div class="text-xs font-bebas tracking-widest text-white mb-1">TWO-FACTOR AUTHENTICATION</div>
                            <div class="text-[8px] font-mono text-text-secondary uppercase">Require admin confirmation for all code generations</div>
                        </div>
                        <div class="w-12 h-6 bg-border relative rounded-full cursor-pointer">
                            <div class="absolute right-1 top-1 w-4 h-4 bg-accent rounded-full"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-background-secondary/50 border border-border">
                        <div>
                            <div class="text-xs font-bebas tracking-widest text-white mb-1">AUTOMATIC BACKUP</div>
                            <div class="text-[8px] font-mono text-text-secondary uppercase">Create database snapshot every 15 minutes during live contests</div>
                        </div>
                        <div class="w-12 h-6 bg-border relative rounded-full cursor-pointer">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-text-secondary rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tactical-card p-8 border-t-2 border-danger/50">
                <h3 class="text-xl mb-6 text-danger">DANGER ZONE</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <button class="flex-1 py-4 border border-danger/30 text-[10px] font-mono text-danger uppercase tracking-widest hover:bg-danger/10 transition-all">FACTORY RESET SYSTEM</button>
                    <button class="flex-1 py-4 border border-danger/30 text-[10px] font-mono text-danger uppercase tracking-widest hover:bg-danger/10 transition-all">PURGE AUDIT LOGS</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
