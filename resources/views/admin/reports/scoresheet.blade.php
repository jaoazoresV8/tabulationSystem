<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detailed Score Sheet - {{ $currentContest->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } body { background: white; } .page-break { break-after: page; } }
        .font-bebas { font-family: 'Bebas Neue', sans-serif; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6 font-sans text-xs">
    <div class="max-w-6xl mx-auto bg-white p-10 shadow-xl border-t-8 border-gray-900">
        <div class="flex justify-between items-center mb-8 border-b pb-8">
            <div>
                <h1 class="text-4xl font-bebas tracking-widest text-gray-900">{{ strtoupper($currentContest->name) }}</h1>
                <p class="text-[10px] font-mono text-gray-500 mt-1 uppercase tracking-[0.2em]">Detailed Judge Audit // All Segments</p>
            </div>
            <button onclick="window.print()" class="no-print bg-black text-white px-6 py-2 font-bebas tracking-widest hover:bg-gray-800">PRINT_AUDIT</button>
        </div>

        @foreach($exposures as $exposure)
            <div class="mb-12 page-break">
                <div class="bg-gray-100 p-4 border-l-4 border-red-600 mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bebas tracking-widest">{{ strtoupper($exposure->name) }}</h2>
                    <span class="text-[10px] font-mono text-gray-400">Weight: {{ $exposure->weight }}%</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-900 text-white font-mono text-[9px] uppercase tracking-tighter">
                                <th class="border p-2 text-left">Contestant</th>
                                @foreach($judges as $judge)
                                    <th class="border p-2 text-center" colspan="{{ $exposure->criteria->count() }}">
                                        {{ $judge->name }}
                                    </th>
                                @endforeach
                                <th class="border p-2 text-right">Avg</th>
                            </tr>
                            <tr class="bg-gray-200 font-mono text-[8px] uppercase">
                                <th class="border p-2"></th>
                                @foreach($judges as $judge)
                                    @foreach($exposure->criteria as $criterion)
                                        <th class="border p-1 text-center" title="{{ $criterion->name }}">
                                            {{ substr($criterion->name, 0, 3) }}.
                                        </th>
                                    @endforeach
                                @endforeach
                                <th class="border p-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contestants as $contestant)
                                <tr>
                                    <td class="border p-2 font-bold uppercase">{{ $contestant->name }}</td>
                                    @php $segmentSum = 0; $judgesScored = 0; @endphp
                                    @foreach($judges as $judge)
                                        @php
                                            $judgeWeightedSum = 0;
                                            $hasScore = isset($detailedScores[$contestant->id][$exposure->id][$judge->id]);
                                        @endphp
                                        @foreach($exposure->criteria as $criterion)
                                            @php
                                                $scoreObj = $hasScore ? $detailedScores[$contestant->id][$exposure->id][$judge->id]->where('criterion_id', $criterion->id)->first() : null;
                                                $scoreVal = $scoreObj ? $scoreObj->score : 0;
                                                $judgeWeightedSum += $scoreVal * ($criterion->percentage / 100);
                                            @endphp
                                            <td class="border p-1 text-center font-mono text-[10px] {{ $scoreObj ? '' : 'text-gray-300' }}">
                                                {{ number_format($scoreVal, 0) }}
                                            </td>
                                        @endforeach
                                        @php
                                            if($hasScore) { $segmentSum += $judgeWeightedSum; $judgesScored++; }
                                        @endphp
                                    @endforeach
                                    <td class="border p-2 text-right font-bold text-red-600">
                                        {{ $judgesScored > 0 ? number_format($segmentSum / $judgesScored, 2) : '0.00' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="mt-8 text-[9px] font-mono text-gray-400 border-t pt-4 italic">
            Note: Criterion abbreviations (e.g. VOC.) refer to the headers in the order defined in node flow.
        </div>
    </div>
</body>
</html>
