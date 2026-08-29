@extends('layouts.app')

@section('title', 'Edit PO #' . $purchase->purchase_no . ' - POS System')
@section('page_heading', 'Edit Purchase Order')

@section('content')
<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-wrapper {
        width: 100% !important;
        position: relative !important;
    }
    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        font-size: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        min-height: 38px !important;
        box-shadow: none !important;
    }
    .ts-control:focus-within {
        border-color: #6366f1 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15) !important;
    }
    .ts-dropdown {
        background-color: #ffffff !important;
        border-radius: 0.75rem !important;
        font-size: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        z-index: 99999 !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        background-color: #ffffff !important;
    }
    .ts-dropdown .option {
        padding: 0.5rem 0.75rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155 !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background-color: #eef2ff !important;
        color: #4f46e5 !important;
        font-weight: 600 !important;
    }
</style>

<div x-data="editPurchaseManager()" class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែប័ណ្ណទិញចូល៖ #{{ $purchase->purchase_no ?? ('PO-' . $purchase->purchase_id) }}</h2>
            <p class="text-xs sm:text-sm text-gray-500">កែសម្រួលអ្នកផ្គត់ផ្គង់ បរិមាណទំនិញ ឬព័ត៌មានទូទាត់ប្រាក់</p>
        </div>
        <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
            <div class="flex items-center gap-2 text-rose-700 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchases.update', $purchase->purchase_id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Supplier & Date Info -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 flex items-center gap-2">
                <i class="fa-solid fa-truck-field"></i> ព័ត៌មានអ្នកផ្គត់ផ្គង់ & កាលបរិច្ឆេទ
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">អ្នកផ្គត់ផ្គង់ <span class="text-rose-500">*</span></label>
                    <select id="supplierSelect" name="supplier_id" placeholder="ជ្រើសរើសអ្នកផ្គត់ផ្គង់..." autocomplete="off" required>
                        <option value="">-- ជ្រើសរើសអ្នកផ្គត់ផ្គង់ --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->supplier_id }}" {{ old('supplier_id', $purchase->supplier_id) == $sup->supplier_id ? 'selected' : '' }}>
                                {{ $sup->supplier_name ?? $sup->name }} ({{ $sup->phone ?? 'គ្មានលេខ' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កាលបរិច្ឆេទបញ្ជាទិញ <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="purchase_date" value="{{ old('purchase_date', optional($purchase->purchase_date)->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked"></i> បញ្ជីទំនិញទិញចូល (Items)
                </h3>
                <button type="button" @click="addItem()" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl text-xs font-bold transition">
                    <i class="fa-solid fa-plus mr-1"></i> ថែមមុខទំនិញ
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                            <th class="py-3 px-4">មុខទំនិញ</th>
                            <th class="py-3 px-4 w-32 text-center">ចំនួន (Qty)</th>
                            <th class="py-3 px-4 w-40 text-right">ថ្លៃដើមរាយ ($)</th>
                            <th class="py-3 px-4 w-36 text-right">សរុប ($)</th>
                            <th class="py-3 px-4 w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="py-3 px-4">
                                    <select :name="`items[${index}][id]`" x-model="item.id" @change="updatePrice(index)" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <option value="">-- ជ្រើសរើសទំនិញ --</option>
                                        <template x-for="prod in availableProducts" :key="prod.product_id">
                                            <option :value="prod.product_id" :selected="prod.product_id == item.id" x-text="`${prod.product_name || prod.name} (${prod.sku || prod.barcode || 'No SKU'})`"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="number" min="1" :name="`items[${index}][qty]`" x-model.number="item.qty" required class="w-full text-center px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <input type="number" step="0.01" min="0" :name="`items[${index}][cost]`" x-model.number="item.cost" required class="w-full text-right px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-800 font-mono" x-text="`$${((item.qty || 0) * (item.cost || 0)).toFixed(2)}`">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button" @click="removeItem(index)" class="p-1.5 text-gray-400 hover:text-rose-600 transition" title="លុប">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment & Summary Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">បញ្ចុះតម្លៃពីអ្នកផ្គត់ផ្គង់ ($)</label>
                        <input type="number" step="0.01" min="0" name="discount_amount" x-model.number="discount" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ពន្ធ / Tax ($)</label>
                        <input type="number" step="0.01" min="0" name="tax_amount" x-model.number="tax" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កំណត់សម្គាល់ (Notes)</label>
                        <textarea name="notes" rows="3" placeholder="ព័ត៌មានបន្ថែម..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('notes', $purchase->notes) }}</textarea>
                    </div>
                </div>

                <div class="space-y-3 bg-gray-50 p-5 rounded-2xl border border-gray-100 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>សរុបបឋម (Subtotal):</span>
                            <span class="font-bold text-gray-800 font-mono" x-text="`$${calculateSubtotal().toFixed(2)}`"></span>
                        </div>
                        <div class="flex justify-between text-xs text-indigo-600 font-bold">
                            <span>ទឹកប្រាក់សរុប (Grand Total):</span>
                            <span class="text-sm font-black font-mono" x-text="`$${calculateTotal().toFixed(2)}`"></span>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">បានទូទាត់ (Paid Amount) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="paid_amount" x-model.number="paidAmount" required class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-emerald-600 focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
                        </div>
                        <div class="flex justify-between text-xs text-rose-600 font-bold pt-2 border-t border-gray-200">
                            <span>នៅជំពាក់ (Due Balance):</span>
                            <span class="font-mono" x-text="`$${Math.max(0, calculateTotal() - (paidAmount || 0)).toFixed(2)}`"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">បោះបង់</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-indigo-500/25 transition">
                    ធ្វើបច្ចុប្បន្នភាពប័ណ្ណទិញចូល
                </button>
            </div>
        </div>
    </form>

</div>

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('supplierSelect')) {
        new TomSelect('#supplierSelect', {
            create: false,
            maxOptions: 5,
            placeholder: 'ស្វែងរកអ្នកផ្គត់ផ្គង់...',
            allowEmptyOption: true,
            dropdownParent: 'body'
        });
    }
});

function editPurchaseManager() {
    return {
        availableProducts: {!! json_encode($products) !!},
        items: {!! json_encode($purchase->items->map(function($i) {
            return [
                'id'   => $i->product_id,
                'qty'  => (int) $i->quantity,
                'cost' => (float) ($i->unit_cost ?? $i->cost_price ?? $i->unit_price ?? 0),
            ];
        })) !!},
        discount: {{ (float) ($purchase->discount ?? $purchase->discount_amount ?? 0) }},
        tax: {{ (float) ($purchase->tax ?? $purchase->tax_amount ?? 0) }},
        paidAmount: {{ (float) ($purchase->paid_amount ?? 0) }},
        
        addItem() {
            this.items.push({
                id: '',
                qty: 1,
                cost: 0.00
            });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            } else {
                alert('ប័ណ្ណទិញចូលត្រូវមានយ៉ាងហោចណាស់មុខទំនិញមួយ!');
            }
        },
        updatePrice(index) {
            const pId = this.items[index].id;
            const prod = this.availableProducts.find(p => p.product_id == pId);
            if (prod) {
                this.items[index].cost = parseFloat(prod.cost_price || prod.unit_cost || 0);
            }
        },
        calculateSubtotal() {
            return this.items.reduce((sum, item) => sum + ((item.qty || 0) * (item.cost || 0)), 0);
        },
        calculateTotal() {
            const sub = this.calculateSubtotal();
            return Math.max(0, (sub - (this.discount || 0)) + (this.tax || 0));
        }
    }
}
</script>
@endsection