<x-admin-layout>
    <x-slot name="title">Results & Reports</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary uppercase tracking-tighter">{{ $currentContest?->name ?? 'CONTEST FOLDERS' }}</span>
            <span>/</span>
            <span class="text-text-primary">RESULTS</span>
        </div>
    </x-slot>

    @if (! $currentContest)
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">RESULT FOLDERS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Choose a contest folder to view finalized tabulation results.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($contests as $contest)
                <a href="{{ route('results.show', $contest) }}" class="tactical-card block p-6 border-l-2 {{ $contest->status === 'active' ? 'border-success' : 'border-accent' }} hover:border-white transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="h-12 w-14 border border-accent/50 bg-accent/5 p-2 text-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <span class="text-[9px] font-mono uppercase tracking-widest {{ $contest->status === 'active' ? 'text-success' : 'text-text-secondary' }}">{{ $contest->status }}</span>
                    </div>
                    <h3 class="mt-6 text-2xl tracking-wide text-white">{{ $contest->name }}</h3>
                    <p class="mt-2 text-[10px] font-mono uppercase tracking-widest text-text-secondary">{{ $contest->type }} contest &middot; {{ $contest->contestants_count }} contestants</p>
                    <p class="mt-6 text-[10px] font-mono uppercase tracking-widest text-accent">View results &rarr;</p>
                </a>
            @empty
                <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contests found. Results will appear once contests are created.</div>
            @endforelse
        </div>
    @else
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">RESULTS & REPORTS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">{{ $currentContest->name }} &middot; tabulation summary.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('results') }}" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors flex items-center">
                    ALL FOLDERS
                </a>
                <button class="tactical-button bg-accent">
                    PUBLISH RESULTS
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-4">
                <div class="tactical-card p-6 border-t-2 border-accent">
                    <h3 class="text-lg mb-6 uppercase tracking-widest">Quick Filters</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[8px] font-mono text-text-secondary uppercase mb-2">Category</label>
                            <select class="w-full bg-background-secondary border border-border px-3 py-2 text-[10px] font-mono text-white outline-none">
                                <option>OVERALL RESULTS</option>
                                <option>MALE CATEGORY</option>
                                <option>FEMALE CATEGORY</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tactical-card p-6 bg-background-secondary/50">
                    <h4 class="text-xs mb-4 font-mono text-accent uppercase tracking-widest">SYSTEM ACTIONS</h4>
                    <div class="space-y-2">
                        <button class="w-full py-3 border border-border text-[10px] font-mono text-text-secondary uppercase tracking-widest hover:border-accent hover:text-white transition-all text-left px-4">Recalculate Totals</button>
                        <button class="w-full py-3 border border-border text-[10px] font-mono text-text-secondary uppercase tracking-widest hover:border-accent hover:text-white transition-all text-left px-4">Official Export</button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    @php
                        $reportTypes = [
                            ['SCORE SHEET', 'detailed_scores.pdf', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', route('results.scoresheet', $currentContest)],
                            ['RESULT REPORT', 'summary_report.pdf', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v12', route('results.print', $currentContest)],
                            ['RANKINGS', 'official_list.pdf', 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z', route('results.rankings', $currentContest)],
                            ['EXPORT DATA', 'tabulation.xlsx', 'M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4', route('results.export', $currentContest)]
                        ];
                    @endphp

                    @foreach($reportTypes as $report)
                    <div class="tactical-card p-6 flex flex-col items-center justify-center text-center group cursor-pointer hover:border-accent transition-all relative overflow-hidden" onclick="window.location.href='{{ $report[3] }}'">
                        <!-- Tactical Background Shape -->
                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-white/5 rotate-45 group-hover:bg-accent/10 transition-colors"></div>

                        <div class="w-12 h-12 mb-4 flex items-center justify-center border border-white/10 group-hover:border-accent transition-colors rotate-45">
                            <svg class="w-6 h-6 text-white group-hover:text-accent transition-colors -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $report[2] }}"></path>
                            </svg>
                        </div>

                        <div class="text-xs font-bebas tracking-[0.2em] text-white mb-1 uppercase">{{ $report[0] }}</div>
                        <div class="text-[8px] font-mono text-text-secondary uppercase truncate w-full px-2 tracking-tighter opacity-50">{{ $report[1] }}</div>

                        <div class="mt-5 w-full h-px bg-white/10 relative">
                            <div class="absolute inset-0 bg-accent scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                        </div>

                        <div class="mt-4 text-[9px] font-mono text-text-secondary uppercase tracking-[0.3em] group-hover:text-accent transition-colors">INIT_GENERATE</div>
                    </div>
                    @endforeach
                </div>

                <div class="tactical-card p-8 border-t-2 border-accent/20">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2 h-2 bg-accent shadow-[0_0_8px_#FF4655]"></div>
                        <h3 class="text-xl tracking-widest uppercase">CONSOLIDATED_DATA_LOG</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] text-text-secondary border-b border-border uppercase tracking-widest font-mono">
                                    <th class="pb-3 font-medium">Rank</th>
                                    <th class="pb-3 font-medium">Contestant</th>
                                    @foreach($exposures as $exp)
                                    <th class="pb-3 font-medium text-center">{{ strtoupper($exp->name) }}</th>
                                    @endforeach
                                    <th class="pb-3 font-medium text-right text-accent">GRAND TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/30">
                                @foreach($contestants as $index => $res)
                                <tr class="hover:bg-card-elevated/50 transition-colors">
                                    <td class="py-4 text-sm font-mono {{ $index === 0 ? 'text-accent' : 'text-text-secondary' }}">{{ sprintf('%02d', $index + 1) }}</td>
                                    <td class="py-4 text-sm font-medium tracking-wide uppercase">{{ $res->name }}</td>
                                    @foreach($exposures as $exp)
                                    <td class="py-4 text-xs font-mono text-center {{ $exp->carry_over ? 'text-white' : 'text-text-secondary opacity-50' }}">
                                        {{ number_format($res->segment_scores[$exp->id] ?? 0, 2) }}
                                    </td>
                                    @endforeach
                                    <td class="py-4 text-right text-sm font-mono font-bold text-accent">{{ number_format($res->grand_total, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-admin-layout>
