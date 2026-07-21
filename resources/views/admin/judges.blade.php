<x-admin-layout>
    <x-slot name="title">Manage Judges</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary uppercase tracking-tighter">{{ $currentContest?->name ?? 'CONTEST FOLDERS' }}</span>
            <span>/</span>
            <span class="text-text-primary">JUDGES</span>
        </div>
    </x-slot>

    @if (! $currentContest)
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">JUDGE FOLDERS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Choose a contest folder to manage its assigned judges.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($contests as $contest)
                <a href="{{ route('contests.judges', $contest) }}" class="tactical-card block p-6 border-l-2 {{ $contest->status === 'active' ? 'border-success' : 'border-accent' }} hover:border-white transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="h-12 w-14 border border-accent/50 bg-accent/5 p-2 text-accent">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <span class="text-[9px] font-mono uppercase tracking-widest {{ $contest->status === 'active' ? 'text-success' : 'text-text-secondary' }}">{{ $contest->status }}</span>
                    </div>
                    <h3 class="mt-6 text-2xl tracking-wide text-white">{{ $contest->name }}</h3>
                    <p class="mt-2 text-[10px] font-mono uppercase tracking-widest text-text-secondary">{{ $contest->type }} contest &middot; {{ $contest->judges_count }} judges</p>
                    <p class="mt-6 text-[10px] font-mono uppercase tracking-widest text-accent">Open judge panel &rarr;</p>
                </a>
            @empty
                <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contests found. Create a contest to manage judges.</div>
            @endforelse
        </div>
    @else
        @if(session('success'))
            <div class="mb-6 border-l-2 border-success bg-success/5 px-5 py-4 text-[10px] font-mono uppercase tracking-widest text-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">MANAGE JUDGES</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">{{ $currentContest->name }} &middot; Access control and credentials.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('judges') }}" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors flex items-center">
                    ALL FOLDERS
                </a>
                <button type="button"
                        onclick="copyLoginLink()"
                        class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors flex items-center gap-2 group">
                    <svg class="w-3 h-3 group-hover:text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    COPY LOGIN LINK
                </button>
                <a href="{{ route('judges.print', $currentContest) }}" target="_blank" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors flex items-center">
                    PRINT CREDENTIALS
                </a>
                <button type="button" onclick="openAddModal()" class="tactical-button">
                    + ADD JUDGE
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($judges as $index => $j)
            <div class="tactical-card p-6 group hover:border-accent transition-all relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full {{ $j->is_online ? 'bg-success animate-pulse' : 'bg-border' }}"></span>
                        <span class="text-[8px] font-mono {{ $j->is_online ? 'text-success' : 'text-text-secondary' }} uppercase">{{ $j->is_online ? 'ONLINE' : 'OFFLINE' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-card-elevated border border-border flex items-center justify-center font-bebas text-2xl text-accent">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <h4 class="text-xl tracking-wider">{{ $j->name }}</h4>
                        <div class="text-[8px] font-mono text-text-secondary uppercase">Credentials Verified</div>
                    </div>
                </div>

                <div class="bg-background-secondary p-4 border border-border/50 mb-6">
                    <div class="text-[8px] text-text-secondary font-mono uppercase mb-2 tracking-widest">ACCESS CODE</div>
                    <div class="text-2xl font-mono text-white tracking-[0.2em] group-hover:text-accent transition-colors">{{ $j->access_code }}</div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-[8px] font-mono text-text-secondary uppercase">
                        LAST ACTIVITY: <span class="text-text-primary uppercase">{{ $j->last_activity ? $j->last_activity->diffForHumans() : 'NEVER' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('judges.regenerate', $j) }}" method="POST" onsubmit="return confirm('Regenerate access code for {{ $j->name }}?')">
                            @csrf
                            <button type="submit" class="p-2 border border-border hover:border-accent hover:text-accent transition-all" title="Regenerate Code">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </form>
                        <button type="button" onclick="openEditModal({{ $j->id }}, '{{ $j->name }}')" class="p-2 border border-border hover:border-accent hover:text-accent transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form action="{{ route('judges.destroy', $j) }}" method="POST" onsubmit="return confirm('Remove judge {{ $j->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 border border-border hover:border-danger hover:text-danger transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Add Placeholder -->
            <div onclick="openAddModal()" class="tactical-card border-2 border-dashed border-border flex flex-col items-center justify-center p-6 group hover:border-accent transition-colors cursor-pointer min-h-[250px]">
                <div class="w-12 h-12 rounded-full border border-border flex items-center justify-center text-accent text-2xl group-hover:scale-110 transition-transform">+</div>
                <div class="mt-4 text-[10px] font-mono text-text-secondary uppercase tracking-widest group-hover:text-accent transition-colors">Add New Judge</div>
            </div>
        </div>

        <!-- Modals -->
        <div id="judge-modal" class="hidden fixed inset-0 z-[1100] flex items-center justify-center bg-black/80 p-4">
            <div class="w-full max-w-md tactical-card border border-accent bg-background-secondary p-8 shadow-2xl">
                <div class="mb-6 flex items-start justify-between">
                    <div>
                        <div class="text-[9px] font-mono uppercase tracking-[0.3em] text-accent">Configuration</div>
                        <h3 id="modal-title" class="mt-2 text-2xl tracking-widest text-white">ADD JUDGE</h3>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-xl text-text-secondary hover:text-white">&times;</button>
                </div>

                <form id="judge-form" method="POST" class="space-y-5">
                    @csrf
                    <div id="method-container"></div>
                    <div>
                        <label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Judge Name</label>
                        <input type="text" name="name" id="judge-name" required class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent">
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-3 text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-white">Cancel</button>
                        <button type="submit" class="tactical-button">SAVE JUDGE</button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
        <script>
            const modal = document.getElementById('judge-modal');
            const form = document.getElementById('judge-form');
            const title = document.getElementById('modal-title');
            const nameInput = document.getElementById('judge-name');
            const methodContainer = document.getElementById('method-container');

            function openAddModal() {
                title.innerText = 'ADD JUDGE';
                form.action = "{{ route('judges.store', $currentContest) }}";
                nameInput.value = '';
                methodContainer.innerHTML = '';
                modal.classList.remove('hidden');
            }

            function openEditModal(id, name) {
                title.innerText = 'EDIT JUDGE';
                form.action = `/admin/judges/${id}`;
                nameInput.value = name;
                methodContainer.innerHTML = '@method("PATCH")';
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
            }

            window.onclick = function(event) {
                if (event.target == modal) closeModal();
            }

            function copyLoginLink() {
                const link = "{{ route('judge.login', ['contest_uuid' => $currentContest->uuid]) }}";
                navigator.clipboard.writeText(link).then(() => {
                    alert('Contest-specific Judge Login link copied to clipboard!');
                });
            }
        </script>
        @endpush
    @endif
</x-admin-layout>
