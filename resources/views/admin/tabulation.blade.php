<x-admin-layout>
    <x-slot name="title">Live Tabulation</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
                <span>/</span>
                <span class="text-text-primary uppercase tracking-tighter">{{ $currentContest?->name ?? 'CONTEST FOLDERS' }}</span>
                <span>/</span>
                <span class="text-accent">LIVE TABULATION</span>
            </div>
            @if($currentContest)
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-success animate-ping rounded-full"></span>
                    <span class="text-[10px] font-mono text-success uppercase tracking-widest">LIVE DATA FEED</span>
                </div>
                <div class="h-4 w-[1px] bg-border"></div>
                <div class="flex flex-col items-end">
                    <div class="text-[8px] font-mono text-accent uppercase tracking-widest leading-none mb-1">CURRENT SEGMENT MONITOR</div>
                    <div class="text-xs font-mono text-white flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-accent/20 border border-accent/30 text-[9px]">{{ strtoupper($activeExposure->type ?? 'N/A') }}</span>
                        {{ $activeExposure ? strtoupper($activeExposure->name) : 'NO ACTIVE SEGMENT' }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    @if (! $currentContest)
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">TABULATION FOLDERS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Choose a contest folder to monitor live judge scoring.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($contests as $contest)
                <a href="{{ route('contests.tabulation', $contest) }}" class="tactical-card block p-6 border-l-2 {{ $contest->status === 'active' ? 'border-success' : 'border-accent' }} hover:border-white transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="h-12 w-14 border border-accent/50 bg-accent/5 p-2 text-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <span class="text-[9px] font-mono uppercase tracking-widest {{ $contest->status === 'active' ? 'text-success' : 'text-text-secondary' }}">{{ $contest->status }}</span>
                    </div>
                    <h3 class="mt-6 text-2xl tracking-wide text-white">{{ $contest->name }}</h3>
                    <p class="mt-2 text-[10px] font-mono uppercase tracking-widest text-text-secondary">{{ $contest->type }} contest &middot; {{ $contest->contestants_count ?? 0 }} contestants</p>
                    <p class="mt-6 text-[10px] font-mono uppercase tracking-widest text-accent">Monitor Live &rarr;</p>
                </a>
            @empty
                <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contests found. Tabulation rooms will appear once contests are created.</div>
            @endforelse
        </div>
    @else
        @if(!$activeExposure)
        <div class="flex h-full items-center justify-center">
            <div class="tactical-card max-w-xl p-10 text-center border-t-2 border-warning">
                <div class="mb-3 text-[10px] font-mono uppercase tracking-[0.3em] text-warning">Tabulation standing by</div>
                <h2 class="text-3xl tracking-widest text-white">NO ACTIVE SEGMENT</h2>
                <p class="mt-4 text-[11px] font-mono leading-relaxed text-text-secondary">Activate an exposure in the Node Flow before opening live tabulation. Judges will remain on standby until a segment is active.</p>
                <div class="flex gap-4 justify-center mt-6">
                    <a href="{{ route('tabulation') }}" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent">ALL FOLDERS</a>
                    <a href="{{ route('contests.exposures', $currentContest) }}" class="tactical-button inline-block">OPEN NODE FLOW</a>
                </div>
            </div>
        </div>
        @else
        <div class="flex flex-col gap-6 h-full">
            <!-- Top Controls -->
            <div class="flex justify-between items-center bg-background-secondary/50 p-4 border border-border">
                <div class="flex gap-4 items-center">
                    <a href="{{ route('tabulation') }}" class="text-[10px] font-mono text-text-secondary hover:text-accent uppercase tracking-widest">&larr; Folders</a>
                    <div class="h-4 w-[1px] bg-border mx-2"></div>
                    <form action="{{ route('contests.tabulation', $currentContest) }}" method="GET" id="exposure-form">
                        <select name="exposure_id" onchange="document.getElementById('exposure-form').submit()" class="bg-background-primary border border-border px-4 py-2 text-xs font-mono text-white outline-none focus:border-accent appearance-none min-w-[200px]">
                            @foreach($exposures as $exp)
                            <option value="{{ $exp->id }}" {{ $activeExposure->id == $exp->id ? 'selected' : '' }}>
                                {{ strtoupper($exp->name) }} ({{ strtoupper($exp->status) }})
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="flex gap-3">
                    @if($activeExposure->status === 'locked')
                    <form action="{{ route('exposures.status', $activeExposure) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="px-6 py-2 border border-accent text-[10px] font-mono text-accent uppercase tracking-widest hover:bg-accent/10 transition-colors">ACTIVATE SEGMENT</button>
                    </form>
                    @elseif($activeExposure->status === 'active')
                    <form action="{{ route('exposures.status', $activeExposure) }}" method="POST" onsubmit="return confirm('FINAL_LOCK_ALERT: Once finalized, this segment will be marked as complete. Continue?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="tactical-button {{ $isSegmentComplete ? 'bg-success border-success' : 'bg-accent' }}">
                            {{ $isSegmentComplete ? 'FINALIZE & LOCK' : 'FORCE LOCK SEGMENT' }}
                        </button>
                    </form>
                    @else
                    <a href="{{ route('results.show', $currentContest) }}" class="tactical-button bg-success border-success">
                        VIEW FINAL REPORTS
                    </a>
                    @endif
                </div>
            </div>

            @if($activeExposure->status === 'active' && $isSegmentComplete)
            <div class="bg-success/10 border border-success/30 p-4 animate-pulse flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs font-mono text-success uppercase tracking-widest">SEGMENT_COMPLETE: ALL JUDGES HAVE SUBMITTED BALLOTS.</span>
                </div>
                <div class="text-[9px] font-mono text-success uppercase tracking-tighter italic">READY FOR CONSOLIDATION</div>
            </div>
            @endif

            <div class="flex-1 grid grid-cols-1 xl:grid-cols-4 gap-6 overflow-hidden">
                <!-- Main Table Area -->
                <div class="xl:col-span-3 flex flex-col overflow-hidden">
                    <div class="tactical-card flex-1 flex flex-col overflow-hidden">
                        <!-- Status Banner -->
                        <div class="bg-accent/5 border-b border-border p-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-mono text-accent uppercase tracking-widest font-bold">
                                    MONITORING: {{ strtoupper($activeExposure->name) }} PORTION
                                </span>
                            </div>
                            <span class="text-[9px] font-mono text-text-secondary uppercase">
                                Status: <span class="text-white italic">Calculating Segment Averages (Not Final Grand Total)</span>
                            </span>
                        </div>

                        <div class="overflow-x-auto custom-scrollbar flex-1">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 z-10 bg-card">
                                    <tr class="text-[10px] text-text-secondary border-b border-border uppercase tracking-widest font-mono">
                                        <th class="p-4 bg-card w-16">#</th>
                                        <th class="p-4 bg-card min-w-[200px]">CONTESTANT</th>
                                        @foreach($judges as $judge)
                                        <th class="p-4 bg-card text-center border-l border-border/30">
                                            {{ strtoupper($judge->name) }}
                                            <div class="text-[8px] {{ $judge->is_online ? 'text-success' : 'text-text-secondary' }} mt-1">● {{ $judge->is_online ? 'ONLINE' : 'OFFLINE' }}</div>
                                        </th>
                                        @endforeach
                                        <th class="p-4 bg-card text-right border-l border-border/30 text-accent font-bold">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border/20">
                                    @foreach($contestants as $contestant)
                                    <tr class="hover:bg-accent/5 transition-colors group cursor-pointer"
                                        onclick="window.location.href='{{ route('contests.tabulation', [$currentContest, 'contestant_id' => $contestant->id, 'exposure_id' => $activeExposure->id]) }}'">
                                        <td class="p-4 font-mono text-xs text-text-secondary group-hover:text-accent">{{ $contestant->number }}</td>
                                        <td class="p-4">
                                            <div class="text-sm font-medium tracking-wide uppercase">{{ $contestant->name }}</div>
                                        </td>
                                        @php $contestantTotal = 0; $judgesScored = 0; @endphp
                                        @foreach($judges as $judge)
                                        @php
                                            $judgeScores = $scores[$contestant->id][$judge->id] ?? null;
                                            $judgeAvg = 0;
                                            if ($judgeScores) {
                                                foreach($activeExposure->criteria as $criterion) {
                                                    $s = $judgeScores->where('criterion_id', $criterion->id)->first();
                                                    if ($s) {
                                                        $judgeAvg += $s->score * ($criterion->percentage / 100);
                                                    }
                                                }
                                                $contestantTotal += $judgeAvg;
                                                $judgesScored++;
                                            }
                                        @endphp
                                        <td class="p-4 text-center border-l border-border/10 font-mono text-sm">
                                            {{ $judgeScores ? number_format($judgeAvg, 2) : '---' }}
                                        </td>
                                        @endforeach
                                        <td class="p-4 text-right border-l border-border/10 font-mono text-sm font-bold text-accent">
                                            {{ $judgesScored > 0 ? number_format($contestantTotal / $judgesScored, 2) : '0.00' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Inspector Panel -->
                <div class="xl:col-span-1 flex flex-col gap-6">
                    <div class="tactical-card p-6 border-t-2 border-accent">
                        <h3 class="text-lg mb-4">NOW SCORING</h3>
                        @if($activeContestant)
                        <div class="aspect-square bg-card-elevated border border-border mb-4 relative overflow-hidden">
                            <div class="absolute inset-0 bg-accent/5"></div>
                            @if($activeContestant->photo)
                                <img src="{{ asset('storage/' . $activeContestant->photo) }}" alt="Contestant" class="w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-border/20 text-6xl font-bebas">{{ $activeContestant->number }}</div>
                            @endif
                            <div class="absolute top-2 left-2 px-2 py-0.5 bg-accent text-[10px] font-mono text-white">{{ $activeContestant->number }}</div>
                        </div>
                        <div class="text-center mb-6">
                            <h4 class="text-2xl font-bebas tracking-[0.1em] text-white">{{ strtoupper($activeContestant->name) }}</h4>
                            <div class="text-[10px] font-mono text-text-secondary uppercase">Representing: <span class="text-white">{{ $activeContestant->team ?? 'N/A' }}</span></div>
                        </div>

                        <!-- LIVE VIDEO CONTROL -->
                        <div class="mb-6 p-4 bg-black/40 border border-white/10">
                            <div class="text-[8px] font-mono text-accent uppercase tracking-widest mb-3 leading-none">Transmission Control (Live Feed)</div>
                            <form action="{{ route('contestants.performance.update', $activeContestant) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="flex gap-2">
                                    <input type="text"
                                           name="performance_url"
                                           value="{{ $activeContestant->performance_url }}"
                                           class="flex-1 bg-background-primary border border-border px-3 py-2 text-[10px] font-mono text-white outline-none focus:border-accent"
                                           placeholder="YouTube URL...">
                                    <button type="submit" class="px-3 py-2 bg-accent text-white text-[9px] font-mono uppercase tracking-widest hover:brightness-110 transition-all">
                                        LINK
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="text-center py-8 text-text-secondary font-mono text-xs uppercase">No active contestant selected</div>
                        @endif

                        <div class="space-y-3">
                            <div class="text-[10px] font-mono text-text-secondary uppercase tracking-widest border-b border-border pb-2">Judge Status</div>
                            @foreach($judges as $judge)
                            @php $submitted = $judgeStatuses[$judge->id] ?? false; @endphp
                            <div class="flex justify-between items-center text-[10px] font-mono uppercase">
                                <span class="text-text-secondary">{{ strtoupper($judge->name) }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="{{ $submitted ? 'text-success' : 'text-warning' }}">{{ $submitted ? '✓ SUBMITTED' : '• SCORING...' }}</span>
                                    @if($submitted)
                                    <form action="{{ route('contests.tabulation.unlock', $currentContest) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="judge_id" value="{{ $judge->id }}">
                                        <input type="hidden" name="contestant_id" value="{{ $activeContestant->id }}">
                                        <input type="hidden" name="exposure_id" value="{{ $activeExposure->id }}">
                                        <button type="submit" class="text-[8px] text-accent hover:text-white transition-colors" title="Unlock Ballot">
                                            [UNLOCK]
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tactical-card p-4 bg-background-secondary/50 flex-1">
                        <h4 class="text-xs mb-4 font-mono text-accent uppercase tracking-widest">ACTIVITY LOG</h4>
                        <div class="space-y-4 font-mono text-[8px] uppercase text-text-secondary">
                            <div class="border-l border-accent/30 pl-3 py-1">
                                <div class="text-white">LIVE</div>
                                <div>MONITORING CONNECTED SESSIONS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</x-admin-layout>
