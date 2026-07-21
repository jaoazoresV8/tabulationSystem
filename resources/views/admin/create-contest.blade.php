<x-admin-layout>
    <x-slot name="title">Create Contest</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary">CREATE NEW CONTEST</span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <h2 class="text-4xl mb-1">CREATE NEW CONTEST</h2>
            <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Fill the basic information and type of your contest.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="tactical-card p-8">
                    <form action="{{ route('contests.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Contest Name</label>
                            <input type="text" name="name" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" placeholder="Enter contest name" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Contest Type</label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach(['SINGLE' => 'Individual contestants', 'DOUBLE' => 'Male and Female', 'GROUP' => 'Teams / Groups'] as $type => $desc)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="{{ $type }}" class="sr-only peer" {{ $type == 'DOUBLE' ? 'checked' : '' }}>
                                    <div class="border border-border p-4 text-center peer-checked:border-accent peer-checked:bg-accent/5 transition-all">
                                        <div class="text-sm font-bebas tracking-widest mb-1 group-hover:text-accent transition-colors">{{ $type }}</div>
                                        <div class="text-[8px] font-mono text-text-secondary uppercase">{{ $desc }}</div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Number of Judges</label>
                                <input type="number" name="judges_count" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" value="5">
                            </div>
                            <div class="col-span-2 flex items-end"><p class="pb-3 text-[9px] font-mono leading-relaxed text-text-secondary uppercase">Contestants are added later inside this contest's dedicated roster folder.</p></div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Description (Optional)</label>
                            <textarea name="description" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono h-24" placeholder="Enter contest description"></textarea>
                        </div>

                        <div class="flex justify-end gap-4 pt-4">
                            <button type="button" class="text-[10px] font-mono text-text-secondary uppercase tracking-widest hover:text-white transition-colors">Reset</button>
                            <button type="submit" class="tactical-button">CREATE CONTEST</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="tactical-card p-6 border-t-2 border-accent">
                    <h3 class="text-lg mb-4">LIVE PREVIEW</h3>
                    <div class="aspect-[3/4] bg-card-elevated border border-border flex flex-col items-center justify-center p-8 text-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-accent/5"></div>
                        <div class="w-24 h-24 border border-border mb-4 flex items-center justify-center">
                            <span class="text-4xl">🖼️</span>
                        </div>
                        <div class="text-[10px] font-mono text-accent uppercase tracking-widest mb-2">Sample Contest</div>
                        <div class="text-2xl font-bebas tracking-widest text-white leading-tight">YOUR CONTEST NAME HERE</div>
                        <div class="mt-6 w-full border-t border-border/50 pt-4 flex justify-between text-[8px] font-mono text-text-secondary uppercase">
                            <span>5 Judges</span>
                            <span>Roster added later</span>
                        </div>
                    </div>
                </div>

                <div class="tactical-card p-6 bg-background-secondary/50">
                    <h4 class="text-xs mb-3 font-mono text-accent">TIP: SYSTEM ARCHITECTURE</h4>
                    <p class="text-[10px] text-text-secondary font-mono leading-relaxed uppercase">
                        Contest types define how tabulation is handled. "Double" type creates separate leaderboards for male and female contestants, often used in pageants.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
