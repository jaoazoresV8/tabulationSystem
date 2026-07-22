<x-admin-layout>
    <x-slot name="title">Admin Accounts</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
                <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
                <span>/</span>
                <span class="text-accent">ADMIN ACCOUNTS</span>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-success rounded-full"></span>
                    <span class="text-[10px] font-mono text-success uppercase tracking-widest">SYSTEM SECURITY</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-8">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-4xl mb-1">OPERATOR ACCESS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Manage authorized administrative accounts.</p>
            </div>
            <button onclick="document.getElementById('create-admin-modal').classList.remove('hidden')" class="btn-tactical btn-tactical-primary">
                CREATE NEW OPERATOR
            </button>
        </div>

        @if(session('success'))
            <div class="bg-success/10 border border-success/30 p-4 flex items-center gap-3">
                <div class="w-1.5 h-1.5 bg-success rounded-full"></div>
                <span class="text-xs font-mono text-success uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-danger/10 border border-danger/30 p-4 flex items-center gap-3">
                <div class="w-1.5 h-1.5 bg-danger rounded-full"></div>
                <span class="text-xs font-mono text-danger uppercase tracking-widest">{{ session('error') }}</span>
            </div>
        @endif

        <div class="tactical-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] text-text-secondary border-b border-border uppercase tracking-widest font-mono">
                        <th class="p-4 bg-card">OPERATOR NAME</th>
                        <th class="p-4 bg-card">EMAIL ADDRESS</th>
                        <th class="p-4 bg-card">CREATED</th>
                        <th class="p-4 bg-card text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/20">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-accent/5 transition-colors">
                        <td class="p-4">
                            <div class="text-sm font-medium tracking-wide uppercase">{{ $admin->name }}</div>
                            @if($admin->id === auth()->id())
                                <span class="text-[8px] font-mono text-accent uppercase tracking-tighter">[ CURRENT_SESSION ]</span>
                            @endif
                        </td>
                        <td class="p-4 font-mono text-xs text-text-secondary">{{ $admin->email }}</td>
                        <td class="p-4 font-mono text-[10px] text-text-secondary uppercase">
                            {{ $admin->created_at->format('M d, Y') }}
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-3">
                                <button onclick="openEditModal('{{ $admin->id }}', '{{ $admin->name }}', '{{ $admin->email }}')" class="text-[10px] font-mono text-text-secondary hover:text-white uppercase">[ EDIT ]</button>
                                @if($admin->id !== auth()->id())
                                    <form action="{{ route('admin.accounts.destroy', $admin) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to revoke this operator\'s access?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] font-mono text-danger hover:text-white uppercase">[ REVOKE ]</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="create-admin-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="tactical-card w-full max-w-md border-t-2 border-accent">
            <div class="p-6 border-b border-border flex justify-between items-center">
                <h3 class="text-xl">NEW OPERATOR</h3>
                <button onclick="document.getElementById('create-admin-modal').classList.add('hidden')" class="text-text-secondary hover:text-white">&times;</button>
            </div>
            <form action="{{ route('admin.accounts.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">FULL NAME</label>
                    <input type="text" name="name" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">EMAIL ADDRESS</label>
                    <input type="email" name="email" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">PASSWORD</label>
                    <input type="password" name="password" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">CONFIRM PASSWORD</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="document.getElementById('create-admin-modal').classList.add('hidden')" class="flex-1 py-3 border border-border text-[10px] font-mono uppercase tracking-widest hover:bg-white/5 transition-colors">CANCEL</button>
                    <button type="submit" class="flex-1 btn-tactical btn-tactical-primary py-3">AUTHORIZE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-admin-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="tactical-card w-full max-w-md border-t-2 border-accent">
            <div class="p-6 border-b border-border flex justify-between items-center">
                <h3 class="text-xl">UPDATE OPERATOR</h3>
                <button onclick="document.getElementById('edit-admin-modal').classList.add('hidden')" class="text-text-secondary hover:text-white">&times;</button>
            </div>
            <form id="edit-admin-form" method="POST" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">FULL NAME</label>
                    <input type="text" name="name" id="edit-name" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">EMAIL ADDRESS</label>
                    <input type="email" name="email" id="edit-email" required class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div class="p-3 bg-accent/5 border border-accent/20">
                    <div class="text-[8px] font-mono text-accent uppercase mb-2">SECURITY_OVERRIDE</div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">NEW PASSWORD (LEAVE BLANK TO KEEP)</label>
                    <input type="password" name="password" class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">CONFIRM NEW PASSWORD</label>
                    <input type="password" name="password_confirmation" class="w-full bg-background-primary border border-border px-4 py-2 text-sm text-white focus:border-accent outline-none">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="document.getElementById('edit-admin-modal').classList.add('hidden')" class="flex-1 py-3 border border-border text-[10px] font-mono uppercase tracking-widest hover:bg-white/5 transition-colors">CANCEL</button>
                    <button type="submit" class="flex-1 btn-tactical btn-tactical-primary py-3">UPDATE</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id, name, email) {
            const modal = document.getElementById('edit-admin-modal');
            const form = document.getElementById('edit-admin-form');
            const nameInput = document.getElementById('edit-name');
            const emailInput = document.getElementById('edit-email');

            form.action = `/admin/accounts/${id}`;
            nameInput.value = name;
            emailInput.value = email;

            modal.classList.remove('hidden');
        }
    </script>
    @endpush
</x-admin-layout>
