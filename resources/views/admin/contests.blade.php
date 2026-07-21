<x-admin-layout>
    <x-slot name="title">Manage Contests</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary">CONTESTS</span>
        </div>
    </x-slot>

    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-4xl mb-1 text-white">SYSTEM CONTESTS</h2>
            <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">List of all initialized and active contests in the system.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('contests.create') }}" class="tactical-button">
                + INIT NEW CONTEST
            </a>
        </div>
    </div>

    <div class="tactical-panel bg-panel/30">
        <table class="w-full text-left font-mono">
            <thead>
                <tr class="text-[9px] text-text-secondary uppercase tracking-[0.3em] border-b border-border/50">
                    <th class="p-6 font-normal">Ident Code</th>
                    <th class="p-6 font-normal">Contest Designation</th>
                    <th class="p-6 font-normal">Type</th>
                    <th class="p-6 font-normal">Judges</th>
                    <th class="p-6 font-normal">Contestants</th>
                    <th class="p-6 font-normal text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border/20 text-xs">
                @forelse($contests as $contest)
                <tr class="hover:bg-white/[0.02] transition-colors group cursor-pointer" onclick="window.location='{{ route('contests.settings', $contest->id) }}'">
                    <td class="p-6 text-text-secondary">#C{{ sprintf('%03d', $contest->id) }}</td>
                    <td class="p-6 text-text-primary tracking-widest uppercase font-bold">{{ $contest->name }}</td>
                    <td class="p-6 text-text-secondary uppercase">{{ $contest->type }}</td>
                    <td class="p-6 text-text-secondary">{{ $contest->judges()->count() }}</td>
                    <td class="p-6 text-text-secondary">{{ $contest->contestants()->count() }}</td>
                    <td class="p-6 text-right">
                        <div class="inline-flex items-center gap-2 px-3 py-1 border border-accent/20 bg-accent/5">
                             <span class="w-1 h-1 {{ $contest->status == 'active' ? 'bg-success' : 'bg-accent' }}"></span>
                             <span class="text-[9px] {{ $contest->status == 'active' ? 'text-success' : 'text-accent' }} uppercase tracking-widest">{{ $contest->status }}</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-text-secondary uppercase tracking-[0.3em] font-mono">
                        No contests found in mission database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
