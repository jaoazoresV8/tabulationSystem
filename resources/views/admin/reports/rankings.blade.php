<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Rankings - {{ $currentContest->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } body { background: white; } }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10 font-sans">
    <div class="max-w-4xl mx-auto bg-white p-12 shadow-xl border-t-8 border-red-600">
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-5xl font-bebas tracking-widest text-gray-900">{{ strtoupper($currentContest->name) }}</h1>
                <p class="text-sm font-mono text-gray-500 mt-2 uppercase tracking-[0.3em]">Official Tabulation Rankings</p>
            </div>
            <button onclick="window.print()" class="no-print bg-red-600 text-white px-6 py-2 font-bebas tracking-widest hover:bg-red-700 transition-colors">PRINT_LOG</button>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white font-bebas tracking-widest text-lg">
                    <th class="p-4 text-left">RANK</th>
                    <th class="p-4 text-left">NO.</th>
                    <th class="p-4 text-left">CONTESTANT NAME</th>
                    <th class="p-4 text-right">FINAL SCORE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contestants as $index => $c)
                <tr class="border-b border-gray-200 {{ $index < 3 ? 'bg-red-50/50' : '' }}">
                    <td class="p-4 font-mono font-bold text-2xl {{ $index < 3 ? 'text-red-600' : 'text-gray-400' }}">
                        {{ sprintf('%02d', $index + 1) }}
                    </td>
                    <td class="p-4 font-mono text-gray-600">#{{ $c->number }}</td>
                    <td class="p-4 font-bebas text-2xl tracking-wide uppercase text-gray-800">{{ $c->name }}</td>
                    <td class="p-4 text-right font-mono font-bold text-xl text-gray-900">
                        {{ number_format($c->grand_total, 2) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-20 grid grid-cols-3 gap-12">
            @foreach($currentContest->judges as $judge)
            <div class="text-center border-t border-gray-400 pt-4">
                <p class="font-bebas text-lg tracking-widest">{{ strtoupper($judge->name) }}</p>
                <p class="text-[10px] font-mono text-gray-400 uppercase">Official Judge</p>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center text-[10px] font-mono text-gray-300 uppercase tracking-[0.5em]">
            Generated: {{ now()->format('Y-m-d H:i:s') }} // Tabulator ID: Admin
        </div>
    </div>
</body>
</html>
