@extends('layouts.app')

@section('content')
<div class="min-h-screen lg:h-screen flex flex-col bg-[#05070a] lg:overflow-hidden font-sans text-text-primary selection:bg-accent selection:text-white"
     x-data="scoringHUD()"
     @keydown.window="handleShortcuts($event)">

    <!-- TOP TELEMETRY BAR -->
    <header class="h-14 border-b border-white/10 bg-black/40 backdrop-blur-md flex items-center px-4 lg:px-6 justify-between shrink-0 z-50">
        <div class="flex items-center gap-4 lg:gap-6">
            <div class="hidden sm:flex items-center gap-3 border-r border-white/10 pr-6">
                <div class="w-2 h-2 bg-accent rotate-45 shadow-[0_0_8px_#FF4655]"></div>
                <div class="text-[10px] font-mono tracking-[0.3em] text-white/70">PROTOCOL: HUD_V2.0</div>
            </div>
            <div class="flex flex-col">
                <div class="text-[8px] font-mono text-accent uppercase tracking-widest leading-none">JUDGE</div>
                <h1 class="text-sm lg:text-lg font-bebas tracking-[0.1em] text-white leading-none mt-1">{{ strtoupper($judge->name) }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-4 lg:gap-12">
            <!-- Station Clock -->
            <div class="hidden xl:flex flex-col items-center border-x border-white/10 px-8">
                <div class="text-[8px] font-mono text-text-secondary uppercase tracking-[0.2em]">STATION_CLOCK</div>
                <div id="station-clock" class="text-sm font-mono text-white font-bold tracking-widest">00:00:00 AM</div>
            </div>

            <div class="flex items-center gap-2 lg:gap-4">
                <div class="text-right">
                    <div class="text-[8px] font-mono text-text-secondary uppercase">SEGMENT</div>
                    <div class="text-xs lg:text-sm font-bebas tracking-widest text-white">{{ strtoupper($activeExposure->name) }}</div>
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
    <div class="flex-1 flex flex-col lg:flex-row lg:overflow-hidden relative">

        @if($isLocked)
        <!-- LOCK OVERLAY -->
        <div class="fixed lg:absolute inset-0 z-[100] bg-black/60 backdrop-blur-md flex items-center justify-center animate-fade-in p-4">
            <div class="tactical-card p-8 lg:p-12 max-w-md w-full border-t-4 border-accent text-center bg-[#0a0f14]">
                <div class="w-16 h-16 lg:w-20 lg:h-20 border-2 border-accent mx-auto flex items-center justify-center mb-6 lg:mb-8 rotate-45">
                    <svg class="w-8 h-8 lg:w-10 lg:h-10 text-accent -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-2xl lg:text-4xl font-bebas tracking-widest text-white mb-2 uppercase">Ballot Locked</h2>
                <p class="text-[10px] lg:text-xs font-mono text-text-secondary uppercase tracking-widest mb-6 lg:mb-8 leading-relaxed">
                    Session for contestant #{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }} has been encrypted and finalized.
                </p>
                <div class="space-y-3">
                    <div class="p-3 bg-white/5 border border-white/10 text-[8px] lg:text-[9px] font-mono text-text-secondary uppercase flex justify-between gap-2 overflow-hidden">
                        <span>HASH_ID</span>
                        <span class="text-white truncate">{{ $existingScores->first()->ballot_hash ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-8 lg:mt-10">
                    <button type="button" onclick="window.location.reload()" class="flex-1 border border-white/20 py-3 lg:py-4 text-[10px] lg:text-xs font-mono text-white/50 hover:text-white transition-colors uppercase tracking-widest">
                        [ REFRESH HUD ]
                    </button>
                    <a href="{{ route('judge.scoring', ['contest_uuid' => $contest->uuid, 'judge_slug' => Str::slug($judge->name), 'target' => $contestants->where('id', '>', $contestant->id)->first()?->id ?? $contestants->first()->id]) }}"
                       class="flex-[2] tactical-button py-3 lg:py-4 text-xs lg:text-sm text-center flex items-center justify-center uppercase">NEXT TARGET</a>
                </div>
            </div>
        </div>
        @endif

        <!-- LEFT: OBSERVER VIEWPORT -->
        <div class="w-full h-[300px] sm:h-[400px] lg:flex-1 lg:h-full flex flex-col bg-black relative shrink-0">
            <!-- Timeline HUD -->
            <div class="absolute top-0 left-0 right-0 p-4 lg:p-6 z-20 flex justify-between items-start pointer-events-none">
                <div class="bg-black/40 backdrop-blur-sm border-l-2 border-accent p-2 lg:p-3">
                    <div class="text-[7px] lg:text-[8px] font-mono text-accent uppercase mb-1">TIME</div>
                    <div class="text-base lg:text-2xl font-mono text-white tracking-tighter" id="performance-timer">00:00 / 03:00</div>
                    <div class="w-32 lg:w-48 h-1 bg-white/10 mt-2 relative">
                        <div class="absolute top-0 left-0 h-full bg-accent shadow-[0_0_5px_#FF4655]" id="timer-progress" style="width: 0%"></div>
                    </div>
                </div>

                <div class="text-right bg-black/40 backdrop-blur-sm border-r-2 border-accent p-2 lg:p-3">
                    <div class="text-[7px] lg:text-[8px] font-mono text-text-secondary uppercase mb-1">WINDOW</div>
                    <div class="text-base lg:text-lg font-bebas text-success tracking-widest">OPEN</div>
                </div>
            </div>

            <!-- YouTube IFrame -->
            <div id="player" class="w-full h-full opacity-80 group-hover:opacity-100 transition-opacity duration-700"></div>

            <!-- Bottom Video HUD -->
            <div class="absolute bottom-0 left-0 right-0 p-4 lg:p-8 z-20 bg-gradient-to-t from-black to-transparent pointer-events-none">
                <div class="flex items-end justify-between">
                    <div>
                        <div class="flex items-center gap-2 lg:gap-3 mb-1 lg:mb-2">
                            <span class="px-1.5 py-0.5 bg-accent text-[8px] lg:text-[9px] font-mono text-white">#{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[8px] lg:text-[10px] font-mono text-white/50 uppercase tracking-widest">LIVE</span>
                        </div>
                        <h2 class="text-3xl lg:text-5xl font-bebas text-white tracking-widest leading-none">{{ $contestant->name }}</h2>
                        <div class="text-[10px] lg:text-sm font-mono text-text-secondary uppercase mt-1 lg:mt-2 tracking-[0.2em] lg:tracking-[0.3em]">{{ $contestant->team }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: SCORING CONSOLE -->
        <aside class="w-full lg:w-[480px] bg-[#0a0f14] border-l border-white/10 flex flex-col shrink-0 lg:z-30 shadow-2xl relative">
            <!-- Dashboard Header -->
            <div class="p-4 lg:p-6 border-b border-white/10 bg-gradient-to-br from-white/5 to-transparent sticky top-0 z-10 bg-[#0a0f14]">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-[8px] lg:text-[9px] font-mono text-accent uppercase tracking-[0.2em] mb-1 leading-none">OFFICIAL_BALLOT</div>
                        <h3 class="text-xl lg:text-2xl font-bebas text-white tracking-widest uppercase">Console</h3>
                    </div>
                    <div class="text-right">
                        <div class="text-[8px] lg:text-[9px] font-mono text-text-secondary uppercase mb-1 leading-none">TOTAL</div>
                        <div class="text-3xl lg:text-4xl font-mono text-white font-bold leading-none" id="total-display">0.00</div>
                    </div>
                </div>
            </div>

            <!-- Scoring Area -->
            <div class="flex-1 lg:overflow-y-auto p-4 lg:p-6 custom-scrollbar space-y-4 lg:space-y-6 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]">
                <form id="ballot-form" action="{{ route('judge.scoring.submit') }}" method="POST" class="space-y-4 lg:space-y-6">
                    @csrf
                    <input type="hidden" name="contestant_id" value="{{ $contestant->id }}">
                    <input type="hidden" name="exposure_id" value="{{ $activeExposure->id }}">

                    @foreach($criteria as $index => $item)
                        @php($existing = $existingScores->get($item->id))
                        <div class="p-4 lg:p-5 border border-white/10 bg-black/40 hover:border-accent/40 transition-all duration-300 relative group overflow-hidden"
                             :class="activeCriterion === {{ $index }} ? 'border-accent/60 bg-accent/[0.02]' : ''">

                            <div class="absolute top-0 right-0 p-2 text-[8px] font-mono text-white/10">0{{ $index+1 }}</div>

                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <h4 class="text-[10px] lg:text-xs font-mono font-bold text-white uppercase tracking-wider mb-1">{{ $item->name }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[7px] lg:text-[8px] font-mono px-1.5 py-0.5 bg-white/5 text-text-secondary border border-white/10 uppercase">Weight: {{ $item->percentage }}%</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl lg:text-3xl font-mono text-accent font-bold leading-none score-val" id="score-{{ $item->id }}">{{ number_format($existing->score ?? 0, 0) }}</div>
                                    <div class="text-[7px] lg:text-[8px] font-mono text-text-secondary uppercase mt-1">RAW</div>
                                </div>
                            </div>

                            <!-- Input Control -->
                            <div class="space-y-4">
                                <div class="relative h-2 lg:h-1.5 bg-white/5 rounded-full overflow-hidden cursor-pointer group">
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
                                              placeholder="NOTES..."
                                              class="w-full bg-black/60 border border-white/5 p-2 text-[9px] lg:text-[10px] font-mono text-white focus:border-accent/40 outline-none transition-all h-10 lg:h-12 uppercase resize-none">{{ $existing->comment ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Control Actions -->
                    <div class="pt-4 lg:pt-6 space-y-4 pb-12 lg:pb-0">
                        <div class="flex flex-col sm:flex-row gap-3 lg:gap-4">
                            <button type="submit" name="save_draft" value="1"
                                    class="flex-1 border-2 border-white/10 py-4 lg:py-5 text-xs lg:text-sm font-bebas tracking-widest text-text-secondary uppercase hover:border-accent hover:text-white transition-all">
                                Save Draft
                            </button>
                            <button type="submit" name="finalize" value="1"
                                    class="flex-[2] tactical-button py-4 lg:py-5 text-lg lg:text-xl flex flex-col items-center justify-center group relative overflow-hidden"
                                    onclick="return confirm('LOCK_BALLOT: Are you sure?')">
                                <span class="text-[8px] lg:text-xs font-mono tracking-widest text-white/50 mb-1 group-hover:text-white transition-colors uppercase leading-none">Execute</span>
                                <span class="tracking-[0.2em] leading-none uppercase">Lock Ballot</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </aside>
    </div>

    <!-- FOOTER: TARGET SELECTOR -->
    <footer class="h-24 lg:h-28 border-t border-white/10 bg-[#05070a] flex items-center px-4 gap-4 overflow-x-auto shrink-0 z-40 custom-scrollbar sticky bottom-0">
        @foreach($contestants as $c)
            @php($isScored = in_array($c->id, $allScoredIds))
            @php($isSelected = $c->id === $contestant->id)
            <a href="{{ route('judge.scoring', ['contest_uuid' => $contest->uuid, 'judge_slug' => Str::slug($judge->name), 'target' => $c->id]) }}"
               class="flex-shrink-0 w-44 lg:w-52 h-16 lg:h-20 border transition-all relative group overflow-hidden flex items-center px-3 gap-3
                      {{ $isSelected ? 'border-accent bg-accent/5' : ($isScored ? 'border-success/30 bg-success/5' : 'border-white/5 hover:border-white/30 bg-white/[0.02]') }}">

                <!-- Target Image / Number Container -->
                <div class="w-10 h-10 lg:w-14 lg:h-14 bg-white/5 border border-white/10 shrink-0 relative overflow-hidden">
                    @if($c->photo)
                        <img src="{{ asset('storage/' . $c->photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @endif
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                        <span class="text-[10px] lg:text-xs font-bebas text-white tracking-widest">#{{ $c->number }}</span>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-[10px] lg:text-[11px] font-bebas text-white tracking-widest truncate">{{ strtoupper($c->name) }}</div>

                    <div class="flex items-center gap-1.5 mt-1 lg:mt-2">
                        @if($isScored)
                            <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-success shadow-[0_0_5px_#00D084]"></span>
                            <span class="text-[7px] lg:text-[8px] font-mono text-success uppercase">✓ In</span>
                        @elseif($isSelected)
                            <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-accent animate-pulse shadow-[0_0_5px_#FF4655]"></span>
                            <span class="text-[7px] lg:text-[8px] font-mono text-accent uppercase">⏳ Active</span>
                        @else
                            <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-white/20"></span>
                            <span class="text-[7px] lg:text-[8px] font-mono text-white/30 uppercase">Wait</span>
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
