@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center bg-background-primary text-center p-8">
    <div class="w-24 h-24 border-2 border-accent animate-spin mb-8 flex items-center justify-center">
        <div class="w-16 h-16 bg-accent"></div>
    </div>
    <h1 class="text-4xl tracking-widest text-white mb-4">TACTICAL STANDBY</h1>
    <p class="text-text-secondary font-mono text-sm uppercase tracking-widest max-w-md">
        {{ $message ?? 'Waiting for command from central operations centre.' }}
    </p>
    <div class="mt-12">
        <div class="inline-flex items-center gap-2 px-4 py-2 border border-border">
            <span class="w-2 h-2 bg-success animate-ping rounded-full"></span>
            <span class="text-[10px] font-mono text-success uppercase tracking-widest">ENCRYPTED CONNECTION STABLE</span>
        </div>
    </div>
</div>
@endsection
