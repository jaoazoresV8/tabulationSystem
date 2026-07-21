<x-admin-layout>
    <x-slot name="title">Manage Contestants</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a><span>/</span>
            <span class="text-text-primary uppercase tracking-tighter">{{ $currentContest?->name ?? 'CONTEST FOLDERS' }}</span><span>/</span><span class="text-text-primary">CONTESTANTS</span>
        </div>
    </x-slot>

    @if (! $currentContest)
        <div class="flex justify-between items-end mb-8">
            <div><h2 class="text-4xl mb-1">CONTEST FOLDERS</h2><p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Choose a contest folder before adding or importing contestants.</p></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($contests as $contest)
                <a href="{{ route('contests.contestants', $contest) }}" class="tactical-card block p-6 border-l-2 {{ $contest->status === 'active' ? 'border-success' : 'border-accent' }} hover:border-white transition-colors">
                    <div class="flex items-start justify-between gap-4"><div class="h-12 w-14 border border-accent/50 bg-accent/5 p-2 text-accent"><svg viewBox="0 0 56 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-full w-full" aria-hidden="true"><path d="M3 10H22L27 4H53V34H3V10Z" stroke="currentColor" stroke-width="2"/><path d="M3 15H53" stroke="currentColor" stroke-width="2"/><path d="M22 21L28 16L34 21L28 27L22 21Z" fill="currentColor"/><path d="M8 31H18M38 31H48" stroke="currentColor" stroke-width="2"/></svg></div><span class="text-[9px] font-mono uppercase tracking-widest {{ $contest->status === 'active' ? 'text-success' : 'text-text-secondary' }}">{{ $contest->status }}</span></div>
                    <h3 class="mt-6 text-2xl tracking-wide text-white">{{ $contest->name }}</h3>
                    <p class="mt-2 text-[10px] font-mono uppercase tracking-widest text-text-secondary">{{ $contest->type }} contest &middot; {{ $contest->contestants_count }} contestants</p>
                    <p class="mt-6 text-[10px] font-mono uppercase tracking-widest text-accent">Open roster &rarr;</p>
                </a>
            @empty
                <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contests found. Create a contest to create its roster folder.</div>
            @endforelse
        </div>
    @else
    <div class="flex justify-between items-end mb-8">
        <div><h2 class="text-4xl mb-1">MANAGE CONTESTANTS</h2><p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">{{ $currentContest->name }} roster folder.</p></div>
        <div class="flex gap-4">
            <a href="{{ route('contestants') }}" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors">ALL FOLDERS</a>
            <button type="button" id="bulk-import-button" class="px-6 py-2 border border-border text-[10px] font-mono uppercase tracking-widest hover:border-accent transition-colors">BULK IMPORT</button>
            <button type="button" id="add-contestant-button" class="tactical-button">+ ADD CONTESTANT</button>
        </div>
    </div>

    @if(session('success'))<div class="mb-6 border-l-2 border-success bg-success/5 px-5 py-4 text-[10px] font-mono uppercase tracking-widest text-success">{{ session('success') }}</div>@endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <form method="GET" action="{{ route('contests.contestants', $currentContest) }}" class="tactical-card p-4 mb-6 flex items-center justify-between gap-6">
                <div class="flex-1 relative"><input type="text" name="search" value="{{ request('search') }}" class="w-full bg-background-primary border border-border px-4 py-2 text-sm font-mono focus:border-accent outline-none" placeholder="SEARCH BY NAME OR NUMBER..."></div>
                <div class="flex gap-2"><a href="{{ route('contests.contestants', ['contest' => $currentContest, 'gender' => 'male']) }}" class="px-4 py-2 {{ request('gender') === 'male' ? 'bg-accent/10 border border-accent text-accent' : 'border border-border text-text-secondary' }} text-[10px] font-mono uppercase tracking-widest">MALE</a><a href="{{ route('contests.contestants', ['contest' => $currentContest, 'gender' => 'female']) }}" class="px-4 py-2 {{ request('gender') === 'female' ? 'bg-accent/10 border border-accent text-accent' : 'border border-border text-text-secondary' }} text-[10px] font-mono uppercase tracking-widest">FEMALE</a><a href="{{ route('contests.contestants', $currentContest) }}" class="px-4 py-2 border border-border text-[10px] font-mono text-text-secondary uppercase tracking-widest">ALL</a></div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($contestants as $contestant)
                    <div class="tactical-card p-4 flex items-center gap-4 hover:border-accent transition-all relative group">
                        <div class="w-16 h-16 bg-card-elevated border border-border flex items-center justify-center overflow-hidden">
                            @if($contestant->photo)<img src="{{ asset('storage/' . $contestant->photo) }}" alt="{{ $contestant->name }}" class="h-full w-full object-cover">@else<svg class="h-8 w-8 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0" /></svg>@endif
                        </div>
                        <div class="flex-1 min-w-0"><div class="text-[10px] font-mono text-accent uppercase mb-1">{{ $contestant->number }}</div><h4 class="text-lg tracking-wide truncate">{{ $contestant->name }}</h4><div class="text-[8px] font-mono text-text-secondary uppercase truncate">{{ $contestant->team ?: 'NO TEAM ASSIGNED' }}</div></div>
                        <div class="text-right"><div class="w-2 h-2 rounded-full mx-auto {{ $contestant->is_active ? 'bg-success' : 'bg-danger' }}"></div><div class="text-[8px] font-mono text-text-secondary mt-1 uppercase">{{ $contestant->is_active ? 'ACTIVE' : 'INACTIVE' }}</div></div>

                        <!-- Mini Link for Performance -->
                        @if($contestant->performance_url)
                            <div class="absolute bottom-1 right-2"><span class="text-[7px] font-mono text-success opacity-50 uppercase">Video Linked</span></div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full tactical-card p-12 text-center text-[10px] font-mono uppercase tracking-widest text-text-secondary">No contestants found. Add one manually or import a CSV.</div>
                @endforelse
            </div>
        </div>

        <aside class="tactical-card p-6 border-t-2 border-accent h-fit"><h3 class="text-xl mb-5">ROSTER SUMMARY</h3><div class="space-y-3 font-mono text-[10px] uppercase"><div class="flex justify-between border-b border-border pb-3"><span class="text-text-secondary">Total</span><span class="text-white">{{ $contestants->count() }}</span></div><div class="flex justify-between border-b border-border pb-3"><span class="text-text-secondary">Active</span><span class="text-success">{{ $contestants->where('is_active', true)->count() }}</span></div><div class="flex justify-between"><span class="text-text-secondary">Inactive</span><span class="text-danger">{{ $contestants->where('is_active', false)->count() }}</span></div></div><p class="mt-6 text-[9px] font-mono leading-relaxed text-text-secondary">Ensure performance URLs are correctly formatted YouTube links for the Judge HUD.</p></aside>
    </div>
    @endif

    @if($currentContest)<div id="bulk-import-modal" class="{{ $errors->has('csv_file') ? '' : 'hidden' }} fixed inset-0 z-[1100] flex items-center justify-center bg-black/80 p-4"><div class="w-full max-w-xl tactical-card border border-accent bg-background-secondary p-8 shadow-2xl"><div class="mb-6 flex items-start justify-between"><div><div class="text-[9px] font-mono uppercase tracking-[0.3em] text-accent">Contest roster</div><h3 class="mt-2 text-2xl tracking-widest text-white">BULK IMPORT CSV</h3></div><button type="button" data-close-bulk-modal class="text-xl text-text-secondary hover:text-white">&times;</button></div><p class="mb-5 text-[11px] font-mono leading-relaxed text-text-secondary">Upload a CSV with this exact first row: <span class="text-white">number,name,gender,team</span>. Gender may be male, female, other, or blank.</p><form action="{{ route('contests.contestants.import', $currentContest) }}" method="POST" enctype="multipart/form-data" class="space-y-5">@csrf<input type="file" name="csv_file" accept=".csv,text/csv" required class="block w-full text-[10px] font-mono text-text-secondary file:mr-4 file:border-0 file:bg-accent file:px-3 file:py-2 file:text-white">@error('csv_file')<p class="text-[10px] font-mono text-danger">{{ $message }}</p>@enderror<div class="flex justify-end gap-3"><button type="button" data-close-bulk-modal class="px-4 py-3 text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-white">Cancel</button><button type="submit" class="tactical-button">IMPORT CONTESTANTS</button></div></form></div></div>

    <div id="contestant-modal" class="{{ $errors->any() && !$errors->has('csv_file') ? '' : 'hidden' }} fixed inset-0 z-[1100] flex items-center justify-center bg-black/80 p-4"><div class="w-full max-w-xl tactical-card border border-accent bg-background-secondary p-8 shadow-2xl"><div class="mb-6 flex items-start justify-between"><div><div class="text-[9px] font-mono uppercase tracking-[0.3em] text-accent">Contest roster</div><h3 class="mt-2 text-2xl tracking-widest text-white">ADD CONTESTANT</h3></div><button type="button" data-close-contestant-modal class="text-xl text-text-secondary hover:text-white">&times;</button></div><form action="{{ route('contests.contestants.store', $currentContest) }}" method="POST" enctype="multipart/form-data" class="space-y-5">@csrf<div class="grid grid-cols-2 gap-5"><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Number</label><input name="number" value="{{ old('number') }}" required class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent">@error('number')<p class="mt-1 text-[9px] font-mono text-danger">{{ $message }}</p>@enderror</div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Category</label><select name="gender" class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent"><option value="">Not specified</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div></div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Full name</label><input name="name" value="{{ old('name') }}" required class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent">@error('name')<p class="mt-1 text-[9px] font-mono text-danger">{{ $message }}</p>@enderror</div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Performance URL (YouTube)</label><input name="performance_url" value="{{ old('performance_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent"></div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Team / represented place</label><input name="team" value="{{ old('team') }}" class="w-full border border-border bg-background-primary px-4 py-3 font-mono text-white outline-none focus:border-accent"></div><div><label class="mb-2 block text-[10px] font-mono uppercase tracking-widest text-text-secondary">Photo</label><input type="file" name="photo" accept="image/*" class="block w-full text-[10px] font-mono text-text-secondary"></div><div class="flex justify-end gap-3"><button type="button" data-close-contestant-modal class="px-4 py-3 text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-white">Cancel</button><button type="submit" class="tactical-button">SAVE CONTESTANT</button></div></form></div></div>@endif

    @push('scripts')<script>document.addEventListener('DOMContentLoaded', () => { const add = document.getElementById('contestant-modal'), bulk = document.getElementById('bulk-import-modal'); document.getElementById('add-contestant-button')?.addEventListener('click', () => add.classList.remove('hidden')); document.getElementById('bulk-import-button')?.addEventListener('click', () => bulk.classList.remove('hidden')); add?.querySelectorAll('[data-close-contestant-modal]').forEach(button => button.addEventListener('click', () => add.classList.add('hidden'))); bulk?.querySelectorAll('[data-close-bulk-modal]').forEach(button => button.addEventListener('click', () => bulk.classList.add('hidden'))); });</script>@endpush
</x-admin-layout>
