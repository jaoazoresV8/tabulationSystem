<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Credentials - {{ $currentContest->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .judge-card { break-inside: avoid; border: 1px solid #000; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 no-print">
            <h1 class="text-2xl font-bold">Judge Credentials</h1>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print Now</button>
        </div>

        <div class="grid grid-cols-2 gap-6">
            @foreach($judges as $judge)
                <div class="judge-card bg-white p-6 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="text-center border-b pb-4 mb-4">
                        <h2 class="text-xl font-bold uppercase">{{ $currentContest->name }}</h2>
                        <p class="text-sm text-gray-500 italic">Judge Login Credentials</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-400 block">Judge Name</label>
                            <p class="text-lg font-bold">{{ $judge->name }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded text-center">
                            <label class="text-[10px] uppercase font-bold text-gray-400 block mb-1">Access Code</label>
                            <p class="text-3xl font-mono font-bold tracking-[0.3em]">{{ $judge->access_code }}</p>
                        </div>

                        <div class="text-[10px] text-gray-400">
                            <p>URL: {{ url('/judge/login') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
