<x-admin-layout>
    <x-slot name="title">Manage Exposures</x-slot>
    <x-slot name="header"><div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest"><a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a><span>/</span><span class="text-text-primary">{{ $currentContest?->name ?? 'CONTEST FOLDERS' }}</span><span>/</span><span class="text-text-primary">EXPOSURES</span></div></x-slot>

    @if (! $currentContest)
        <div class="mb-8"><h2 class="text-4xl mb-1">EXPOSURE FOLDERS</h2><p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Choose a contest folder to manage its connected exposure flow.</p></div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($contests as $contest)
                <a href="{{ route('contests.exposures', $contest) }}" class="tactical-card block p-6 border-l-2 {{ $contest->status === 'active' ? 'border-success' : 'border-accent' }} hover:border-white transition-colors">
                    <div class="flex items-start justify-between gap-4"><x-exposure-folder-icon /><span class="text-[9px] font-mono uppercase tracking-widest {{ $contest->status === 'active' ? 'text-success' : 'text-text-secondary' }}">{{ $contest->status }}</span></div><h3 class="mt-6 text-2xl tracking-wide text-white">{{ $contest->name }}</h3><p class="mt-2 text-[10px] font-mono uppercase tracking-widest text-text-secondary">{{ $contest->type }} contest &middot; {{ $contest->exposures_count }} exposures</p><p class="mt-6 text-[10px] font-mono uppercase tracking-widest text-accent">Open node flow &rarr;</p>
                </a>
            @empty <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contest folders found.</div>
            @endforelse
        </div>
    @else
        <div class="flex justify-between items-end mb-8"><div><h2 class="text-4xl mb-1">EXPOSURE NODE FLOW</h2><p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">{{ $currentContest->name }} &middot; connect nodes to set the contest sequence.</p></div><div class="flex gap-3"><a href="{{ route('exposures') }}" class="px-5 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent">ALL FOLDERS</a><button type="button" id="add-exposure-button" class="tactical-button">+ ADD EXPOSURE</button></div></div>
        @if(session('success'))<div class="mb-6 border-l-2 border-success bg-success/5 px-5 py-4 text-[10px] font-mono uppercase tracking-widest text-success">{{ session('success') }}</div>@endif

        <div class="tactical-card min-h-[460px] overflow-x-auto p-8">
            <div id="node-container" class="flex min-w-max items-center gap-0 py-12">
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="flex h-14 w-14 items-center justify-center border-2 border-success bg-success/5 text-[10px] font-mono text-success shadow-[0_0_15px_rgba(34,197,94,0.2)]">START</div>
                </div>

                @forelse($exposures as $index => $exp)
                    @php($isConnected = $exp->previous_exposure_id || $index === 0)
                    <div class="node-connection-line h-[2px] w-12 {{ $isConnected ? 'bg-success shadow-[0_0_8px_rgba(34,197,94,0.5)]' : 'bg-border/30' }}"></div>
                    <div data-id="{{ $exp->id }}" data-node-card class="relative w-80 border-2 {{ $isConnected ? 'border-success bg-background-secondary shadow-[0_0_30px_rgba(34,197,94,0.05)]' : 'border-border/50 bg-background-secondary/50' }} p-6 transition-all hover:border-white group cursor-move">
                        <!-- Ports: Positioned precisely on the borders to avoid overlapping text -->
                        <span class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 h-4 w-4 rounded-full border-2 {{ $isConnected ? 'border-success bg-success shadow-[0_0_8px_rgba(34,197,94,0.8)]' : 'border-border bg-background-primary' }} z-20"></span>
                        <span class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 h-4 w-4 rounded-full border-2 border-border bg-background-primary group-hover:border-white z-20"></span>

                        <div class="flex items-start justify-between gap-3 pointer-events-none">
                            <div>
                                <h3 class="text-2xl leading-tight tracking-wide text-white">{{ $exp->name }}</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-[11px] font-mono uppercase tracking-widest text-accent">{{ $exp->type }}</span>
                                    @if(!$isConnected)
                                        <span class="text-[8px] font-mono px-1.5 py-0.5 border border-danger/50 text-danger uppercase tracking-tighter">Detached</span>
                                    @endif
                                </div>
                            </div>
                        <div class="flex flex-col items-end gap-2">
                            <form action="{{ route('exposures.status', $exp) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $exp->status === 'active' ? 'completed' : 'active' }}">
                                <button type="submit"
                                        title="{{ $exp->status === 'active' ? 'Mark as Completed' : 'Set as Active Segment' }}"
                                        class="h-3 w-3 rounded-full {{ $exp->status === 'active' ? 'bg-success shadow-[0_0_8px_rgba(34,197,94,1)]' : ($exp->status === 'locked' ? 'bg-danger' : 'bg-text-secondary') }} transition-transform hover:scale-150 cursor-pointer">
                                </button>
                            </form>
                            <span class="text-[10px] font-mono text-text-secondary">#{{ $exp->order }}</span>
                        </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3 text-[11px] font-mono uppercase text-text-secondary pointer-events-none">
                            <span>Weight <b class="text-white">{{ $exp->weight }}%</b></span>
                            <span>Top <b class="text-white">{{ $exp->top_n ?? '-' }}</b></span>
                        </div>

                        <div class="mt-5 border-t border-border/50 pt-4 flex justify-between items-center relative z-30">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('criteria', ['exposure_id' => $exp->id]) }}" class="text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-success pointer-events-auto">Manage criteria &rarr;</a>
                                <span class="text-border">|</span>
                                <form action="{{ route('exposures.status', $exp) }}" method="POST" class="pointer-events-auto">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $exp->status === 'active' ? 'locked' : 'active' }}">
                                    <button type="submit" class="text-[10px] font-mono uppercase tracking-widest {{ $exp->status === 'active' ? 'text-success animate-pulse' : 'text-accent hover:text-white' }}">
                                        {{ $exp->status === 'active' ? '● ONLINE' : '○ SET ACTIVE' }}
                                    </button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('contests.exposures.detach', [$currentContest, $exp]) }}" class="pointer-events-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[8px] font-mono text-danger/50 hover:text-danger uppercase">Detach Node</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="w-full py-16 text-center"><div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center border border-dashed border-accent text-2xl text-accent">+</div><p class="text-[10px] font-mono uppercase tracking-widest text-text-secondary">No exposure nodes yet. Add the first node to start the flow.</p></div>
                @endforelse

                @if($exposures->isNotEmpty())
                    <div class="h-[2px] w-12 bg-border/30"></div>
                    <div class="flex h-14 w-14 items-center justify-center border-2 border-border text-[10px] font-mono text-text-secondary flex-shrink-0">END</div>
                @endif
            </div>
        </div>

        <div id="exposure-modal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 z-[1100] flex items-center justify-center bg-black/80 p-4"><div class="w-full max-w-xl tactical-card border border-accent bg-background-secondary p-8 shadow-2xl"><div class="mb-6 flex items-start justify-between"><div><div class="text-[9px] font-mono uppercase tracking-[0.3em] text-accent">Node configuration</div><h3 class="mt-2 text-2xl tracking-widest text-white">ADD EXPOSURE</h3></div><button type="button" data-close-exposure-modal class="text-xl text-text-secondary hover:text-white">&times;</button></div><form action="{{ route('contests.exposures.store', $currentContest) }}" method="POST" class="space-y-5">@csrf<div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Exposure name</label><input name="name" value="{{ old('name') }}" required class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent">@error('name')<p class="mt-1 text-[9px] font-mono text-danger">{{ $message }}</p>@enderror</div><div class="grid grid-cols-2 gap-5"><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Type</label><select name="type" class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white"><option value="preliminary">Preliminary</option><option value="final">Final</option></select></div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Weight %</label><input type="number" min="0" max="100" step="0.01" name="weight" value="{{ old('weight', 100) }}" required class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white"></div></div><div class="grid grid-cols-2 gap-5"><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Top N (optional)</label><input type="number" min="1" name="top_n" value="{{ old('top_n') }}" class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white"></div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Connect after</label><select name="insert_after" class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white"><option value="">End of flow</option>@foreach($exposures as $exp)<option value="{{ $exp->id }}">{{ $exp->name }}</option>@endforeach</select></div></div><label class="flex items-center gap-3 text-[10px] font-mono uppercase text-text-secondary"><input type="checkbox" name="carry_over" value="1"> Carry scores over</label><div class="flex justify-end gap-3"><button type="button" data-close-exposure-modal class="px-4 py-3 text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-white">Cancel</button><button type="submit" class="tactical-button">ADD NODE</button></div></form></div></div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('exposure-modal');
                document.getElementById('add-exposure-button')?.addEventListener('click', () => modal.classList.remove('hidden'));
                modal?.querySelectorAll('[data-close-exposure-modal]').forEach(button => button.addEventListener('click', () => modal.classList.add('hidden')));

                const container = document.getElementById('node-container');
                if (container) {
                    new Sortable(container, {
                        animation: 150,
                        draggable: '[data-node-card]',
                        filter: '.pointer-events-auto', // Don't drag when clicking buttons/links
                        onEnd: function (evt) {
                            const orders = Array.from(container.querySelectorAll('[data-node-card]')).map(el => el.dataset.id);

                            fetch("{{ route('contests.exposures.reorder', $currentContest) }}", {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ orders: orders })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.reload(); // Reload to refresh connections and UI states
                                }
                            });
                        }
                    });
                }
            });
        </script>
        @endpush
    @endif
</x-admin-layout>
