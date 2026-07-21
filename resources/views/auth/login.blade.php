@extends('layouts.app')

@section('content')
<div class="flex-1 flex items-center justify-center relative overflow-hidden bg-background-primary">
    <!-- Animated Tactical Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(var(--color-accent) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        <!-- Simple floating particles using CSS animation -->
        <div class="absolute inset-0">
            @for ($i = 0; $i < 20; $i++)
            <div class="absolute bg-accent/20 w-1 h-1 rounded-full animate-pulse"
                 style="top: {{ rand(0, 100) }}%; left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 5000) }}ms; animation-duration: {{ rand(3000, 7000) }}ms;"></div>
            @endfor
        </div>
    </div>

    <div class="relative z-10 w-full max-w-md p-8">
        <div class="flex flex-col items-center mb-12">
            <!-- Valorant-style Logo Placeholder -->
            <div class="w-24 h-24 mb-6 relative group">
                <div class="absolute inset-0 bg-accent rotate-45 group-hover:rotate-90 transition-transform duration-700"></div>
                <div class="absolute inset-2 bg-background-primary rotate-45 group-hover:rotate-90 transition-transform duration-700"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-white font-bebas text-5xl">V</span>
                </div>
            </div>
            <h1 class="text-4xl tracking-[0.2em] mb-2">OPERATIONS LOGIN</h1>
            <div class="h-1 w-12 bg-accent"></div>
        </div>

        <div class="tactical-card p-10 relative">
            <!-- Corner Accents -->
            <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-accent"></div>
            <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-accent"></div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Operator Email</label>
                    <input type="email" name="email" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" placeholder="admin@ccdi.edu.ph" required>
                    @error('email')
                        <span class="text-danger text-[8px] font-mono uppercase mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-2">Access Code</label>
                    <input type="password" name="password" class="w-full bg-background-secondary border border-border px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors font-mono" placeholder="••••••••" required>
                    @error('password')
                        <span class="text-danger text-[8px] font-mono uppercase mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-4 h-4 border border-border peer-checked:bg-accent peer-checked:border-accent transition-all"></div>
                        <span class="text-[10px] font-mono text-text-secondary uppercase tracking-widest group-hover:text-text-primary">Remember Device</span>
                    </label>
                    <a href="#" class="text-[10px] font-mono text-accent uppercase tracking-widest hover:underline">Forgot Access?</a>
                </div>

                <button type="submit" class="tactical-button w-full py-4 text-xl cursor-pointer">
                    INITIALIZE SESSION
                </button>
            </form>
        </div>

        <!-- System Status Footer -->
        <div class="mt-8 flex justify-center gap-8 text-[10px] font-mono text-text-secondary uppercase tracking-tighter">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-success rounded-full"></span>
                SERVER: ONLINE
            </div>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-success rounded-full"></span>
                ENCRYPTION: ACTIVE
            </div>
            <div class="flex items-center gap-2 text-warning">
                V1.0.4-STABLE
            </div>
        </div>
    </div>
</div>
@endsection
