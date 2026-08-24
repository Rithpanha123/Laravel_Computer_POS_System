<?php

namespace App\Exports;

use App\Models\Purchase;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping
{
    protected $purchaseId;

    public function __construct($purchaseId = null)
    {
        $this->purchaseId = $purchaseId;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        if ($this->purchaseId) {
            return Purchase::with(['supplier', 'user', 'items.product'])
                ->where('purchase_id', $this->purchaseId)
                ->get();
        }

        return Purchase::with(['supplier', 'user', 'items.product'])
            ->latest('purchase_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'PO Number',
            'Date',
            'Supplier',
            'Purchased By',
            'Total Amount ($)',
            'Paid Amount ($)',
            'Due Amount ($)',
            'Payment Status',
            'Payment Method',
        ];
    }

    public function map($purchase): array
    {
        return [
            $purchase->purchase_no,
            optional($purchase->purchase_date)->format('Y-m-d H:i'),
            $purchase->supplier->supplier_name ?? $purchase->supplier->name ?? 'N/A',
            $purchase->user->full_name ?? $purchase->user->username ?? 'Staff',
            number_format($purchase->total_amount, 2),
            number_format($purchase->paid_amount, 2),
            number_format($purchase->due_amount, 2),
            strtoupper($purchase->payment_status),
            $purchase->payment_method ?? 'Cash',
        ];
    }
}