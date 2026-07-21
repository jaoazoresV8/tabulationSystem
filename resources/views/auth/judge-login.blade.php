@extends('layouts.app')

@section('content')
<div class="flex-1 flex items-center justify-center relative overflow-hidden bg-background-primary">
    <!-- Animated Tactical Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(var(--color-accent) 1px, transparent 1px); background-size: 80px 80px;">
        </div>
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-background-primary via-transparent to-background-primary"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg p-8">
        <div class="tactical-card p-12 relative bg-background-secondary/80 backdrop-blur-xl">
            <!-- Header -->
            <div class="flex flex-col items-center mb-10 text-center">
                <div class="w-16 h-16 bg-accent flex items-center justify-center mb-6">
                    <span class="text-white font-bebas text-3xl">V</span>
                </div>
                <h1 class="text-3xl tracking-[0.3em] text-white">JUDGE PORTAL</h1>
                <p class="text-[10px] font-mono text-text-secondary mt-2 uppercase tracking-widest">AUTHENTICATION REQUIRED TO ACCESS SCORING INTERFACE</p>
                <div class="h-0.5 w-16 bg-accent mt-6"></div>
            </div>

            <form action="{{ route('judge.login.post', request()->route('contest_uuid')) }}" method="POST" class="space-y-8">
                @csrf
                <div>
                    <label class="block text-[10px] font-mono text-text-secondary uppercase tracking-[0.2em] mb-3 text-center">ENTER YOUR UNIQUE ACCESS CODE</label>
                    <input type="text"
                           name="access_code"
                           class="w-full bg-background-primary border border-border px-6 py-5 text-4xl text-white focus:border-accent focus:outline-none transition-all font-mono text-center tracking-[0.5em] uppercase placeholder:text-border"
                           placeholder="XXXXXX"
                           maxlength="6"
                           required
                           autofocus>
                    @error('access_code')
                        <span class="text-danger text-[10px] font-mono uppercase mt-2 block text-center">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="tactical-button w-full py-5 text-2xl cursor-pointer">
                    AUTHORIZE SESSION
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-border/30">
                <div class="flex flex-col items-center">
                    <div class="text-[10px] font-mono text-text-secondary uppercase mb-4">TARGET CONTEST</div>
                    <div class="px-4 py-2 bg-card-elevated border border-border">
                        <div class="text-lg font-bebas tracking-widest text-white">
                            {{ $targetContest->name ?? (\App\Models\Contest::where('status', 'active')->first()->name ?? 'NO ACTIVE CONTEST') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Message -->
        <div class="mt-8 text-center text-[8px] font-mono text-text-secondary uppercase tracking-[0.2em]">
            Need help? Contact the chief tabulator immediately. <br>
            <span class="text-accent">AUTHORIZED PERSONNEL ONLY</span>
        </div>
    </div>
</div>
@endsection
