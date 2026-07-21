<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Results - {{ $currentContest->name }}</title>
    <style>
        body { font-family: 'Inter', 'Segoe UI', sans-serif; color: #000; background: #fff; margin: 0; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; text-transform: uppercase; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #ddd; padding: 12px 8px; text-align: left; font-size: 12px; }
        th { background: #f5f5f5; font-weight: bold; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .grand-total { font-weight: bold; background: #fafafa; }
        .footer { margin-top: 60px; }
        .signature-grid { display: grid; grid-template-cols: repeat(3, 1fr); gap: 40px; margin-top: 40px; }
        .sig-box { border-top: 1px solid #000; text-align: center; padding-top: 10px; font-size: 10px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>{{ $currentContest->name }}</h1>
        <p>OFFICIAL CONSOLIDATED RESULTS SUMMARY</p>
        <p>Generated on: {{ now()->format('F d, Y - h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">Rank</th>
                <th>Contestant Name</th>
                @foreach($exposures as $exp)
                <th class="text-center">{{ $exp->name }}<br><small>({{ $exp->weight }}%)</small></th>
                @endforeach
                <th class="text-right">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contestants as $index => $res)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-weight: 500;">{{ $res->name }}</td>
                @foreach($exposures as $exp)
                <td class="text-center">{{ number_format($res->segment_scores[$exp->id] ?? 0, 2) }}</td>
                @endforeach
                <td class="text-right grand-total">{{ number_format($res->grand_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p style="font-size: 10px; margin-bottom: 40px;">CERTIFIED CORRECT BY:</p>

        <div class="signature-grid">
            <div class="sig-box">
                <strong>{{ $currentContest->tabulator_name }}</strong><br>
                CHIEF TABULATOR
            </div>
            <div class="sig-box">
                <br>
                CHAIRMAN OF THE BOARD
            </div>
            <div class="sig-box">
                <br>
                AUDITOR / SYSTEM OPERATOR
            </div>
        </div>
    </div>

    <button class="no-print" onclick="window.location.href='{{ route('results') }}'" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background: #000; color: #fff; border: none; cursor: pointer;">
        Back to Results
    </button>
</body>
</html>
