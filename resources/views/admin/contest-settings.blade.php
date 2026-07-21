<x-admin-layout>
    <x-slot name="title">Contest Settings</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <a href="{{ route('contests') }}" class="hover:text-accent">CONTESTS</a>
            <span>/</span>
            <span class="text-text-primary uppercase tracking-tighter">{{ $contest->name }}</span>
            <span>/</span>
            <span class="text-text-primary">SETTINGS</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-4xl mb-1">CONTEST SETTINGS</h2>
                <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Manage configuration for {{ $contest->name }}.</p>
            </div>
            <button type="submit" form="contest-settings-form" class="tactical-button bg-success">
                SAVE CHANGES
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Settings Navigation -->
            <div class="lg:col-span-1 space-y-1">
                @foreach([
                    'GENERAL' => true,
                    'BRANDING' => false,
                    'EXPOSURES' => false,
                    'CONTESTANTS' => false,
                    'JUDGES' => false,
                    'ACTIVATE' => false
                ] as $tab => $active)
                <button class="w-full flex items-center px-6 py-4 text-sm font-bebas tracking-widest transition-all {{ $active ? 'border-r-4 border-accent bg-accent/5 text-white' : 'text-text-secondary hover:text-white hover:bg-white/5 border-r border-border' }}">
                    {{ $tab }}
                </button>
                @endforeach
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="tactical-card p-8">
                    <h3 class="text-xl mb-6">GENERAL INFORMATION</h3>
                    <form id="contest-settings-form" action="{{ route('contests.update', $contest) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Contest Name</label>
                            <input type="text" name="name" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" value="{{ $contest->name }}">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Contest Type</label>
                                <select name="type" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono appearance-none">
                                    <option value="double" {{ $contest->type == 'double' ? 'selected' : '' }}>Double (Male/Female)</option>
                                    <option value="single" {{ $contest->type == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="group" {{ $contest->type == 'group' ? 'selected' : '' }}>Group</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Status</label>
                                <select name="status" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono appearance-none">
                                    <option value="pending" {{ $contest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $contest->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ $contest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Male Contestants</label>
                                <input type="number" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" value="{{ $contest->contestants()->where('gender', 'male')->count() }}" readonly>
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Female Contestants</label>
                                <input type="number" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" value="{{ $contest->contestants()->where('gender', 'female')->count() }}" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Tabulator Name</label>
                            <input type="text" name="tabulator_name" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" value="{{ $contest->tabulator_name }}">
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Description (Optional)</label>
                            <textarea name="description" rows="4" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono resize-none" placeholder="Enter description...">{{ $contest->description }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Panel Preview -->
            <div class="lg:col-span-1 space-y-6">
                <div class="tactical-card p-4 text-center">
                    <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden" form="contest-settings-form">
                    <div id="logo-preview-wrapper" class="aspect-square bg-card-elevated border border-border mb-4 relative flex items-center justify-center group overflow-hidden cursor-pointer" onclick="document.getElementById('logo-input').click()">
                        <div class="absolute inset-0 bg-accent/5 group-hover:bg-accent/10 transition-colors"></div>
                        <img id="logo-preview-img"
                             src="{{ $contest->logo ? asset('storage/' . $contest->logo) : 'https://via.placeholder.com/300x300/171C24/FF4655?text=CONTEST+LOGO' }}"
                             alt="Logo Preview"
                             class="max-w-full max-h-full object-contain relative z-10">
                        <div class="absolute bottom-0 left-0 right-0 bg-accent py-1 text-[8px] font-mono text-white opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-widest z-20">Click to change</div>
                    </div>
                    <div class="text-[10px] font-mono text-text-secondary uppercase tracking-widest">{{ $contest->name }}</div>
                    <div class="text-lg font-bebas tracking-widest text-white mt-1">{{ $contest->status }} EDITION</div>
                </div>

                <script>
                    document.getElementById('logo-input').addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = function (evt) {
                            document.getElementById('logo-preview-img').src = evt.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>

                <div class="tactical-card p-6 bg-background-secondary/50">
                    <h4 class="text-xs mb-3 font-mono text-accent">SYSTEM STATUS</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[8px] font-mono uppercase">
                            <span class="text-text-secondary">CONFIGURATION</span>
                            <span class="text-success">COMPLETE</span>
                        </div>
                        <div class="flex justify-between text-[8px] font-mono uppercase">
                            <span class="text-text-secondary">DATA INTEGRITY</span>
                            <span class="text-success">VERIFIED</span>
                        </div>
                        <div class="flex justify-between text-[8px] font-mono uppercase">
                            <span class="text-text-secondary">STATUS</span>
                            <span class="text-{{ $contest->status == 'active' ? 'success' : 'warning' }}">{{ $contest->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
