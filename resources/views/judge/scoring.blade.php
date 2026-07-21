@extends('layouts.app')

@section('content')
<div class="h-screen flex flex-col bg-[#05070a] overflow-hidden font-sans text-text-primary selection:bg-accent selection:text-white"
     x-data="scoringHUD()"
     @keydown.window="handleShortcuts($event)">

    <!-- TOP TELEMETRY BAR -->
    <header class="h-14 border-b border-white/10 bg-black/40 backdrop-blur-md flex items-center px-6 justify-between shrink-0 z-50">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3 border-r border-white/10 pr-6">
                <div class="w-2 h-2 bg-accent rotate-45 shadow-[0_0_8px_#FF4655]"></div>
                <div class="text-[10px] font-mono tracking-[0.3em] text-white/70">PROTOCOL: HUD_V2.0</div>
            </div>
            <div class="flex flex-col">
                <div class="text-[9px] font-mono text-accent uppercase tracking-widest leading-none">WELCOMING JUDGE</div>
                <h1 class="text-lg font-bebas tracking-[0.1em] text-white leading-none mt-1">{{ strtoupper($judge->name) }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-12">
            <!-- Station Clock -->
            <div class="hidden xl:flex flex-col items-center border-x border-white/10 px-8">
                <div class="text-[8px] font-mono text-text-secondary uppercase tracking-[0.2em]">STATION_CLOCK</div>
                <div id="station-clock" class="text-sm font-mono text-white font-bold tracking-widest">00:00:00 AM</div>
            </div>

            <!-- System Indicators -->
            <div class="hidden lg:flex items-center gap-6 text-[8px] font-mono tracking-widest text-text-secondary">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-success shadow-[0_0_5px_#00D084]"></span> VIDEO_SYNC
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-success shadow-[0_0_5px_#00D084]"></span> DATABASE
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-accent animate-pulse shadow-[0_0_5px_#FF4655]"></span> ENCRYPTION_ACTIVE
                </div>
            </div>

            <div class="h-8 w-[1px] bg-white/10"></div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-[8px] font-mono text-text-secondary uppercase">SEGMENT_LOCK</div>
                    <div class="text-sm font-bebas tracking-widest text-white">{{ strtoupper($activeExposure->name) }}</div>
                </div>
                <form action="{{ route('judge.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="w-8 h-8 border border-white/10 flex items-center justify-center hover:bg-danger/20 hover:border-danger transition-all group">
                        <svg class="w-4 h-4 text-text-secondary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- MAIN CONSOLE -->
    <div class="flex-1 flex overflow-hidden relative">

        @if($isLocked)
        <!-- LOCK OVERLAY -->
        <div class="absolute inset-0 z-[100] bg-black/60 backdrop-blur-md flex items-center justify-center animate-fade-in">
            <div class="tactical-card p-12 max-w-md w-full border-t-4 border-accent text-center bg-[#0a0f14]">
                <div class="w-20 h-20 border-2 border-accent mx-auto flex items-center justify-center mb-8 rotate-45">
                    <svg class="w-10 h-10 text-accent -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-4xl font-bebas tracking-widest text-white mb-2">BALLOT LOCKED</h2>
                <p class="text-xs font-mono text-text-secondary uppercase tracking-widest mb-8 leading-relaxed">
                    Session for contestant #{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }} has been encrypted and finalized.
                </p>
                <div class="space-y-3">
                    <div class="p-3 bg-white/5 border border-white/10 text-[9px] font-mono text-text-secondary uppercase flex justify-between">
                        <span>HASH_ID</span>
                        <span class="text-white">{{ $existingScores->first()->ballot_hash ?? 'N/A' }}</span>
                    </div>
                    <div class="text-[8px] font-mono text-accent uppercase tracking-tighter italic">Contact Admin to request manual override</div>
                </div>
                <div class="flex gap-3 mt-10">
                    <button type="button" onclick="window.location.reload()" class="flex-1 border border-white/20 py-4 text-xs font-mono text-white/50 hover:text-white transition-colors uppercase tracking-widest">
                        [ REFRESH HUD ]
                    </button>
                    <a href="{{ route('judge.scoring', ['contest_uuid' => $contest->uuid, 'judge_slug' => Str::slug($judge->name), 'target' => $contestants->where('id', '>', $contestant->id)->first()?->id ?? $contestants->first()->id]) }}"
                       class="flex-[2] tactical-button py-4 text-sm text-center flex items-center justify-center">PROCEED TO NEXT TARGET</a>
                </div>
            </div>
        </div>
        @endif

        <!-- LEFT: OBSERVER VIEWPORT -->
        <div class="flex-1 flex flex-col bg-black relative">
            <!-- Timeline HUD -->
            <div class="absolute top-0 left-0 right-0 p-6 z-20 flex justify-between items-start pointer-events-none">
                <div class="bg-black/40 backdrop-blur-sm border-l-2 border-accent p-3">
                    <div class="text-[8px] font-mono text-accent uppercase mb-1">PERFORMANCE_TIME</div>
                    <div class="text-2xl font-mono text-white tracking-tighter" id="performance-timer">00:00 / 03:00</div>
                    <div class="w-48 h-1 bg-white/10 mt-2 relative">
                        <div class="absolute top-0 left-0 h-full bg-accent shadow-[0_0_5px_#FF4655]" id="timer-progress" style="width: 0%"></div>
                    </div>
                </div>

                <div class="text-right bg-black/40 backdrop-blur-sm border-r-2 border-accent p-3">
                    <div class="text-[8px] font-mono text-text-secondary uppercase mb-1">SCORING_WINDOW</div>
                    <div class="text-lg font-bebas text-success tracking-widest">OPEN</div>
                </div>
            </div>

            <!-- YouTube IFrame -->
            <div id="player" class="w-full h-full opacity-80 group-hover:opacity-100 transition-opacity duration-700"></div>

            <!-- Bottom Video HUD -->
            <div class="absolute bottom-0 left-0 right-0 p-8 z-20 bg-gradient-to-t from-black to-transparent pointer-events-none">
                <div class="flex items-end justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2 py-0.5 bg-accent text-[9px] font-mono text-white">#{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[10px] font-mono text-white/50 uppercase tracking-widest">TRANSMISSION_ACTIVE</span>
                        </div>
                        <h2 class="text-5xl font-bebas text-white tracking-widest leading-none">{{ $contestant->name }}</h2>
                        <div class="text-sm font-mono text-text-secondary uppercase mt-2 tracking-[0.3em]">{{ $contestant->team }}</div>
                    </div>

                    <!-- Telemetry -->
                    <div class="flex gap-4">
                        <div class="p-3 bg-black/60 border border-white/10 text-right min-w-[120px]">
                            <div class="text-[8px] font-mono text-text-secondary uppercase mb-1">CONFIDENCE</div>
                            <div class="flex items-center gap-1">
                                <div class="flex-1 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-success" style="width: 92%"></div>
                                </div>
                                <span class="text-[10px] font-mono text-white">92%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: SCORING CONSOLE -->
        <aside class="w-[480px] bg-[#0a0f14] border-l border-white/10 flex flex-col shrink-0 z-30 shadow-2xl relative">
            <!-- Dashboard Header -->
            <div class="p-6 border-b border-white/10 bg-gradient-to-br from-white/5 to-transparent">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="text-[9px] font-mono text-accent uppercase tracking-[0.2em] mb-1">OFFICIAL_BALLOT</div>
                        <h3 class="text-2xl font-bebas text-white tracking-widest">JUDGING_CONSOLE</h3>
                    </div>
                    <div class="text-right">
                        <div class="text-[9px] font-mono text-text-secondary uppercase mb-1">EST_WEIGHTED</div>
                        <div class="text-4xl font-mono text-white font-bold leading-none" id="total-display">0.00</div>
                    </div>
                </div>

                <!-- Competitor Comparison (Redacted) -->
                <div class="grid grid-cols-2 gap-2 text-[8px] font-mono uppercase text-text-secondary">
                    <div class="p-2 bg-black/40 border border-white/5 flex justify-between items-center">
                        <span>PREV_AVG</span>
                        <span class="text-white/20 select-none italic">[REDACTED]</span>
                    </div>
                    <div class="p-2 bg-black/40 border border-white/5 flex justify-between items-center">
                        <span>SYS_RANK</span>
                        <span class="text-white/20 select-none italic">[HIDDEN]</span>
                    </div>
                </div>
            </div>

            <!-- Scoring Area -->
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-6 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]">
                <form id="ballot-form" action="{{ route('judge.scoring.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="contestant_id" value="{{ $contestant->id }}">
                    <input type="hidden" name="exposure_id" value="{{ $activeExposure->id }}">

                    @foreach($criteria as $index => $item)
                        @php($existing = $existingScores->get($item->id))
                        <div class="p-5 border border-white/10 bg-black/40 hover:border-accent/40 transition-all duration-300 relative group overflow-hidden"
                             :class="activeCriterion === {{ $index }} ? 'border-accent/60 bg-accent/[0.02]' : ''">

                            <div class="absolute top-0 right-0 p-2 text-[8px] font-mono text-white/10">0{{ $index+1 }}</div>

                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <h4 class="text-xs font-mono font-bold text-white uppercase tracking-wider mb-1">{{ $item->name }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[8px] font-mono px-1.5 py-0.5 bg-white/5 text-text-secondary border border-white/10 uppercase">Weight: {{ $item->percentage }}%</span>
                                        <span class="text-[8px] font-mono text-text-secondary opacity-50 uppercase">Input ID: #{{ $item->id }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-mono text-accent font-bold leading-none score-val" id="score-{{ $item->id }}">{{ number_format($existing->score ?? 0, 0) }}</div>
                                    <div class="text-[8px] font-mono text-text-secondary uppercase mt-1">RAW_SCORE</div>
                                </div>
                            </div>

                            <!-- Input Control -->
                            <div class="space-y-4">
                                <div class="relative h-1.5 bg-white/5 rounded-full overflow-hidden cursor-pointer group">
                                    <div class="absolute inset-y-0 left-0 bg-accent transition-all duration-300 shadow-[0_0_8px_#FF4655]"
                                         id="slider-fill-{{ $item->id }}"
                                         style="width: {{ (($existing->score ?? 0) / $item->max_score) * 100 }}%"></div>
                                    <input type="range"
                                           name="scores[{{ $item->id }}]"
                                           min="{{ $item->min_score }}"
                                           max="{{ $item->max_score }}"
                                           step="1"
                                           value="{{ $existing->score ?? 0 }}"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer range-slider"
                                           data-id="{{ $item->id }}"
                                           data-weight="{{ $item->percentage }}"
                                           data-max="{{ $item->max_score }}">
                                </div>

                                <!-- Comments Box -->
                                <div class="mt-4">
                                    <textarea name="comments[{{ $item->id }}]"
                                              placeholder="CRITERION_NOTES..."
                                              class="w-full bg-black/60 border border-white/5 p-2 text-[10px] font-mono text-white focus:border-accent/40 outline-none transition-all h-12 uppercase resize-none">{{ $existing->comment ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Control Actions -->
                    <div class="pt-6 space-y-4">
                        <div class="flex gap-4">
                            <button type="submit" name="save_draft" value="1"
                                    class="flex-1 border-2 border-white/10 py-5 text-sm font-bebas tracking-widest text-text-secondary uppercase hover:border-accent hover:text-white transition-all">
                                SAVE DRAFT
                            </button>
                            <button type="submit" name="finalize" value="1"
                                    class="flex-[2] tactical-button py-5 text-xl flex flex-col items-center justify-center group relative overflow-hidden"
                                    onclick="return confirm('FINAL_BALLOT_LOCK: Are you sure you want to finalize this score?')">
                                <span class="text-xs font-mono tracking-widest text-white/50 mb-1 group-hover:text-white transition-colors uppercase leading-none">Execute Command</span>
                                <span class="tracking-[0.2em] leading-none">LOCK_BALLOT</span>
                                <div class="absolute inset-0 bg-white/5 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                            </button>
                        </div>

                        <div class="p-4 bg-white/5 border border-white/10">
                            <div class="text-[9px] font-mono text-text-secondary uppercase mb-3 text-center tracking-widest">Keyboard Shortcuts</div>
                            <div class="grid grid-cols-2 gap-y-2 text-[8px] font-mono text-text-secondary">
                                <div class="flex justify-between border-b border-white/5 pb-1 mr-4"><span>1-5</span><span class="text-white">SELECT_FIELD</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-1"><span>↑↓</span><span class="text-white">SCORE_ADJ</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-1 mr-4"><span>SPACE</span><span class="text-white">PLAY_PAUSE</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-1"><span>ENTER</span><span class="text-white">SAVE_DRAFT</span></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </aside>
    </div>

    <!-- FOOTER: TARGET SELECTOR -->
    <footer class="h-28 border-t border-white/10 bg-[#05070a] flex items-center px-4 gap-4 overflow-x-auto shrink-0 z-40 custom-scrollbar">
        @foreach($contestants as $c)
            @php($isScored = in_array($c->id, $allScoredIds))
            @php($isSelected = $c->id === $contestant->id)
            <a href="{{ route('judge.scoring', ['contest_uuid' => $contest->uuid, 'judge_slug' => Str::slug($judge->name), 'target' => $c->id]) }}"
               class="flex-shrink-0 w-52 h-20 border transition-all relative group overflow-hidden flex items-center px-3 gap-3
                      {{ $isSelected ? 'border-accent bg-accent/5' : ($isScored ? 'border-success/30 bg-success/5' : 'border-white/5 hover:border-white/30 bg-white/[0.02]') }}">

                <!-- Target Image / Number Container -->
                <div class="w-14 h-14 bg-white/5 border border-white/10 shrink-0 relative overflow-hidden">
                    @if($c->photo)
                        <img src="{{ asset('storage/' . $c->photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @endif
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <span class="text-xs font-bebas text-white tracking-widest">#{{ $c->number }}</span>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-[11px] font-bebas text-white tracking-widest truncate">{{ strtoupper($c->name) }}</div>

                    <div class="flex items-center gap-1.5 mt-2">
                        @if($isScored)
                            <span class="w-1.5 h-1.5 rounded-full bg-success shadow-[0_0_5px_#00D084]"></span>
                            <span class="text-[8px] font-mono text-success uppercase">✓ Submitted</span>
                        @elseif($isSelected)
                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse shadow-[0_0_5px_#FF4655]"></span>
                            <span class="text-[8px] font-mono text-accent uppercase">⏳ Scoring...</span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                            <span class="text-[8px] font-mono text-white/30 uppercase">Pending</span>
                        @endif
                    </div>
                </div>

                @if($isSelected)
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-accent shadow-[0_0_10px_#FF4655]"></div>
                @endif
            </a>
        @endforeach
    </footer>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 4px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--color-accent); }

    @keyframes tactical-reveal {
        0% { clip-path: polygon(0 0, 0 0, 0 100%, 0 100%); opacity: 0; transform: translateX(-20px); }
        100% { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); opacity: 1; transform: translateX(0); }
    }
    .animate-tactical-reveal { animation: tactical-reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    .tactical-button {
        @apply border border-accent bg-accent/10 text-white font-bebas uppercase tracking-[0.2em] transition-all hover:bg-accent hover:shadow-[0_0_20px_rgba(255,70,85,0.4)];
        clip-path: polygon(10px 0, 100% 0, calc(100% - 10px) 100%, 0 100%);
    }

    input[type=range]::-webkit-slider-thumb { appearance: none; width: 0; height: 0; }
</style>

@push('scripts')
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    function scoringHUD() {
        return {
            activeCriterion: 0,
            criteriaCount: {{ count($criteria) }},

            handleShortcuts(e) {
                if (e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT') return;

                // 1-5 Select Criteria
                if (e.key >= '1' && e.key <= '9' && e.key <= this.criteriaCount) {
                    this.activeCriterion = parseInt(e.key) - 1;
                    const el = document.querySelectorAll('.range-slider')[this.activeCriterion];
                    el.focus();
                }

                // Space for Pause/Play
                if (e.code === 'Space') {
                    e.preventDefault();
                    if (window.player) {
                        const state = window.player.getPlayerState();
                        state === 1 ? window.player.pauseVideo() : window.player.playVideo();
                    }
                }
            }
        }
    }

    let player;
    function getYoutubeId(url) {
        if (!url) return null;
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    const videoId = getYoutubeId("{{ $contestant->performance_url }}") || "sy7npYYPyN8";

    function onYouTubeIframeAPIReady() {
        window.player = new YT.Player('player', {
            height: '100%', width: '100%', videoId: videoId,
            playerVars: { 'autoplay': 1, 'controls': 0, 'modestbranding': 1, 'rel': 0, 'iv_load_policy': 3 },
            events: { 'onReady': onPlayerReady, 'onStateChange': onPlayerStateChange }
        });
    }

    function onPlayerReady(event) {
        event.target.playVideo();
        startTimer();
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            startTimer();
        } else {
            clearInterval(window.timerInterval);
        }
    }

    function startTimer() {
        clearInterval(window.timerInterval);
        window.timerInterval = setInterval(() => {
            if (window.player && window.player.getCurrentTime) {
                const cur = window.player.getCurrentTime();
                const dur = window.player.getDuration() || 180;
                const progress = (cur / dur) * 100;

                document.getElementById('timer-progress').style.width = progress + '%';
                const format = s => new Date(s * 1000).toISOString().substr(14, 5);
                document.getElementById('performance-timer').innerText = `${format(cur)} / ${format(dur)}`;
            }
        }, 1000);
    }

    // Logic for syncing and totals
    document.addEventListener('DOMContentLoaded', () => {
        // Station Clock Initialization
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            document.getElementById('station-clock').innerText = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        const sliders = document.querySelectorAll('.range-slider');
        const totalDisplay = document.getElementById('total-display');

        function updateTotals() {
            let weightedSum = 0;
            sliders.forEach(slider => {
                const val = parseFloat(slider.value) || 0;
                const weight = parseFloat(slider.dataset.weight) / 100;
                weightedSum += val * weight;

                // Visual Sync
                const id = slider.dataset.id;
                document.getElementById(`score-${id}`).innerText = Math.round(val);
                document.getElementById(`slider-fill-${id}`).style.width = (val / slider.dataset.max * 100) + '%';
            });
            totalDisplay.innerText = weightedSum.toFixed(2);
        }

        sliders.forEach(slider => {
            slider.addEventListener('input', updateTotals);
        });

        updateTotals();
    });
</script>
@endpush
@endsection
