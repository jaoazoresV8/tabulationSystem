<x-admin-layout>
    <x-slot name="title">Live Leaderboard</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
                <span>/</span>
                <span class="text-text-primary uppercase tracking-tighter">{{ $currentContest->name }}</span>
                <span>/</span>
                <span class="text-accent">LIVE LEADERBOARD</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-4">
        <div class="text-center mb-12">
            <div class="inline-block px-4 py-1 border border-accent bg-accent/5 text-[10px] font-mono text-accent uppercase tracking-[0.3em] mb-4">REAL-TIME RANKINGS</div>
            <h2 class="text-6xl font-bebas tracking-widest text-white">{{ strtoupper($activeExposure->name) }}</h2>
            <p class="text-text-secondary font-mono text-xs uppercase tracking-[0.2em] mt-2">{{ $currentContest->name }} // {{ strtoupper($activeExposure->type) }}</p>
        </div>

        @if($rankings->count() >= 3)
        <!-- Top 3 Podium Style -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 items-end">
            <!-- Rank 2 -->
            @isset($rankings[1])
            <div class="tactical-card p-8 border-t-4 border-t-border bg-gradient-to-b from-card to-background-primary order-2 md:order-1 h-[300px] flex flex-col justify-end relative">
                <div class="absolute top-0 right-0 p-4 font-bebas text-6xl text-border/20">02</div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-card-elevated border border-border mb-4 flex items-center justify-center text-4xl">🥈</div>
                    <div class="text-sm font-mono text-text-secondary uppercase mb-1">{{ $rankings[1]->number }}</div>
                    <h4 class="text-2xl font-bebas tracking-wider text-white">{{ $rankings[1]->name }}</h4>
                    <div class="text-2xl font-mono text-text-secondary mt-2">{{ number_format($rankings[1]->final_score, 2) }}</div>
                </div>
            </div>
            @endisset

            <!-- Rank 1 -->
            @isset($rankings[0])
            <div class="tactical-card p-10 border-t-4 border-t-accent bg-gradient-to-b from-card-elevated to-background-primary order-1 md:order-2 h-[380px] flex flex-col justify-end relative scale-105 shadow-[0_0_50px_rgba(255,70,85,0.15)]">
                <div class="absolute top-0 right-0 p-4 font-bebas text-8xl text-accent/10">01</div>
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 px-4 py-1 bg-accent text-white font-bebas tracking-[0.2em] text-sm">CURRENT LEADER</div>
                <div class="text-center">
                    <div class="w-28 h-28 mx-auto bg-card border-2 border-accent mb-6 flex items-center justify-center text-6xl shadow-[0_0_30px_rgba(255,70,85,0.3)]">🥇</div>
                    <div class="text-sm font-mono text-accent uppercase mb-1">{{ $rankings[0]->number }}</div>
                    <h4 class="text-4xl font-bebas tracking-widest text-white">{{ $rankings[0]->name }}</h4>
                    <div class="text-4xl font-mono text-accent mt-2">{{ number_format($rankings[0]->final_score, 2) }}</div>
                </div>
            </div>
            @endisset

            <!-- Rank 3 -->
            @isset($rankings[2])
            <div class="tactical-card p-8 border-t-4 border-t-border bg-gradient-to-b from-card to-background-primary order-3 h-[260px] flex flex-col justify-end relative">
                <div class="absolute top-0 right-0 p-4 font-bebas text-6xl text-border/20">03</div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-card-elevated border border-border mb-4 flex items-center justify-center text-4xl">🥉</div>
                    <div class="text-sm font-mono text-text-secondary uppercase mb-1">{{ $rankings[2]->number }}</div>
                    <h4 class="text-2xl font-bebas tracking-wider text-white">{{ $rankings[2]->name }}</h4>
                    <div class="text-2xl font-mono text-text-secondary mt-2">{{ number_format($rankings[2]->final_score, 2) }}</div>
                </div>
            </div>
            @endisset
        </div>

        <!-- Remaining Ranks -->
        <div class="space-y-3">
            @foreach($rankings->slice(3) as $index => $rank)
            <div class="tactical-card p-4 flex items-center group hover:bg-white/5 transition-colors">
                <div class="w-16 font-bebas text-2xl text-text-secondary group-hover:text-white transition-colors">{{ sprintf('%02d', $index + 4) }}</div>
                <div class="w-20 font-mono text-xs text-accent">{{ $rank->number }}</div>
                <div class="flex-1 font-bebas tracking-widest text-xl text-white">{{ $rank->name }}</div>
                <div class="flex items-center gap-6">
                    <div class="font-mono text-lg text-text-secondary group-hover:text-white transition-colors">{{ number_format($rank->final_score, 2) }}</div>
                    <div class="w-8 text-center font-bold text-text-secondary">-</div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="tactical-card p-20 text-center border-dashed border-2 border-border">
            <div class="text-accent text-6xl mb-6">📡</div>
            <h3 class="text-2xl text-white">WAITING FOR DATA FEED</h3>
            <p class="text-text-secondary font-mono text-xs uppercase mt-2">Rankings will be generated once scores are submitted by judges.</p>
        </div>
        @endif

        <div class="mt-12 flex justify-center gap-12">
            <div class="text-center">
                <div class="text-[10px] font-mono text-text-secondary uppercase mb-2">JUDGE COMPLETION</div>
                <div class="flex gap-1">
                    @for($i = 1; $i <= $totalJudges; $i++)
                    <div class="w-8 h-1 {{ $i <= $judgesFinalized ? 'bg-success' : 'bg-border' }}"></div>
                    @endfor
                </div>
                <div class="text-[10px] font-mono text-success mt-1 uppercase tracking-tighter">{{ $judgesFinalized }}/{{ $totalJudges }} JUDGES FINALIZED</div>
            </div>
            <div class="text-center">
                <div class="text-[10px] font-mono text-text-secondary uppercase mb-2">SYSTEM INTEGRITY</div>
                <div class="text-[10px] font-mono text-success uppercase tracking-widest">VERIFIED [AES-256]</div>
            </div>
        </div>
    </div>
</x-admin-layout>
