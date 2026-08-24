<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PO #{{ $purchase->purchase_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #6366f1; padding-bottom: 10px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; }
        .badge-paid { background-color: #d1fae5; color: #065f46; }
        .badge-due { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h2 style="margin: 0; color: #4338ca;">COMPUTER STORE & POS</h2>
                    <p style="margin: 3px 0; font-size: 11px; color: #666;">Purchase Order / Goods Receipt Note</p>
                </td>
                <td class="text-right">
                    <h3 style="margin: 0;">#{{ $purchase->purchase_no }}</h3>
                    <p style="margin: 3px 0; font-size: 11px; color: #666;">Date: {{ optional($purchase->purchase_date)->format('d M Y, h:i A') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Info Overview -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <strong>Supplier:</strong><br>
                {{ $purchase->supplier->supplier_name ?? $purchase->supplier->name ?? 'N/A' }}<br>
                Phone: {{ $purchase->supplier->phone ?? '-' }}<br>
                Address: {{ $purchase->supplier->address ?? '-' }}
            </td>
            <td style="width: 50%; vertical-align: top;" class="text-right">
                <strong>Purchased By:</strong> {{ $purchase->user->full_name ?? 'Staff' }}<br>
                <strong>Payment Status:</strong> 
                <span class="badge {{ strtoupper($purchase->payment_status) === 'PAID' ? 'badge-paid' : 'badge-due' }}">
                    {{ strtoupper($purchase->payment_status) }}
                </span><br>
                <strong>Payment Method:</strong> {{ $purchase->payment_method ?? 'Cash' }}
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Cost ($)</th>
                <th class="text-right">Total ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->product_name ?? 'Product #' . $item->product_id }}</strong>
                    @if(optional($item->product)->product_code)
                        <br><small style="color: #666;">Code: {{ $item->product->product_code }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->unit_cost ?? $item->cost_price ?? $item->unit_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->subtotal ?? ($item->quantity * ($item->unit_cost ?? $item->cost_price ?? $item->unit_price)), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Calculations -->
    <table style="width: 100%; margin-top: 15px;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-right">${{ number_format($purchase->subtotal ?? $purchase->total_amount, 2) }}</td>
                    </tr>
                    @if(($purchase->discount_amount ?? 0) > 0)
                    <tr>
                        <td>Discount:</td>
                        <td class="text-right">-${{ number_format($purchase->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="font-weight: bold; font-size: 14px; color: #4338ca;">
                        <td>Grand Total:</td>
                        <td class="text-right">${{ number_format($purchase->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Paid:</td>
                        <td class="text-right" style="color: #059669;">${{ number_format($purchase->paid_amount, 2) }}</td>
                    </tr>
                    @if($purchase->due_amount > 0)
                    <tr style="font-weight: bold; color: #dc2626;">
                        <td>Due Balance:</td>
                        <td class="text-right">${{ number_format($purchase->due_amount, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <!-- Signatures -->
    <table style="width: 100%; margin-top: 50px; text-align: center;">
        <tr>
            <td style="width: 50%;">
                <p>Supplier Representative</p>
                <br><br><br>
                _______________________
            </td>
            <td style="width: 50%;">
                <p>Authorized Receiver</p>
                <br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>
</html>