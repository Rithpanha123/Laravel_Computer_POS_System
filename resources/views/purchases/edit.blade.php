@extends('layouts.app')

@section('title', 'Edit PO #' . $purchase->purchase_no . ' - POS System')
@section('page_heading', 'Edit Purchase Order')

@section('content')
<!-- Custom CSS for specialized animations -->
<style>
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .floating-particle {
        animation: floating 6s ease-in-out infinite;
    }
    .card-3d-hover {
        transition: transform 0.3s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.3s ease;
    }
    .card-3d-hover:hover {
        transform: translateY(-3px) rotateX(0.5deg) rotateY(-0.5deg);
        box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.15);
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        box-shadow: 0 0 25px -5px rgba(99, 102, 241, 0.2);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div x-data="editPurchaseManager()" class="max-w-5xl mx-auto space-y-6 relative">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/3 -right-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header -->
    <div class="flex items-center justify-between relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">កែប្រែប័ណ្ណទិញចូល៖ #{{ $purchase->purchase_no }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">កែសម្រួលអ្នកផ្គត់ផ្គង់ បរិមាណទំនិញ ឬព័ត៌មានទូទាត់ប្រាក់</p>
        </div>
        <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 rounded-2xl relative z-10">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 dark:text-rose-300 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchases.update', $purchase->purchase_id) }}" method="POST" class="space-y-6 relative z-10">
        @csrf
        @method('PUT')

        <!-- Supplier & Date Info -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                <i class="fa-solid fa-truck-field"></i> ព័ត៌មានអ្នកផ្គត់ផ្គង់ & កាលបរិច្ឆេទ
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">អ្នកផ្គត់ផ្គង់ <span class="text-rose-500">*</span></label>
                    <select name="supplier_id" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->supplier_id }}" {{ old('supplier_id', $purchase->supplier_id) == $sup->supplier_id ? 'selected' : '' }}>
                                {{ $sup->supplier_name ?? $sup->name }} ({{ $sup->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កាលបរិច្ឆេទបញ្ជាទិញ <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="purchase_date" value="{{ old('purchase_date', optional($purchase->purchase_date)->format('Y-m-d\TH:i')) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked"></i> បញ្ជីទំនិញទិញចូល (Items)
                </h3>
                <button type="button" @click="addItem()" class="magnetic-btn px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-xl text-xs font-bold transition border border-indigo-100 dark:border-indigo-900">
                    <i class="fa-solid fa-plus mr-1"></i> ថែមមុខទំនិញ
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                            <th class="py-3 px-4">មុខទំនិញ</th>
                            <th class="py-3 px-4 w-32 text-center">ចំនួន (Qty)</th>
                            <th class="py-3 px-4 w-40 text-right">ថ្លៃដើមរាយ ($)</th>
                            <th class="py-3 px-4 w-36 text-right">សរុប ($)</th>
                            <th class="py-3 px-4 w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-blue-50/20 dark:hover:bg-slate-700/40 transition">
                                <td class="py-3 px-4">
                                    <select :name="`items[${index}][id]`" x-model="item.id" @change="updatePrice(index)" required class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <option value="">-- ជ្រើសរើសទំនិញ --</option>
                                        <template x-for="prod in availableProducts" :key="prod.product_id">
                                            <option :value="prod.product_id" :selected="prod.product_id == item.id" x-text="`${prod.product_name} (${prod.product_code || 'No SKU'})`"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="number" min="1" :name="`items[${index}][qty]`" x-model.number="item.qty" required class="w-full text-center px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <input type="number" step="0.01" min="0" :name="`items[${index}][cost]`" x-model.number="item.cost" required class="w-full text-right px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-800 dark:text-white" x-text="`$${(item.qty * item.cost).toFixed(2)}`">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button" @click="removeItem(index)" class="p-1.5 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition" title="លុប">
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
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">បញ្ចុះតម្លៃពីអ្នកផ្គត់ផ្គង់ ($)</label>
                        <input type="number" step="0.01" min="0" name="discount" x-model.number="discount" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ពន្ធ / Tax ($)</label>
                        <input type="number" step="0.01" min="0" name="tax" x-model.number="tax" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កំណត់សម្គាល់ (Notes)</label>
                        <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('notes', $purchase->notes) }}</textarea>
                    </div>
                </div>

                <div class="space-y-3 bg-gray-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                            <span>សរុបបឋម (Subtotal):</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200" x-text="`$${calculateSubtotal().toFixed(2)}`"></span>
                        </div>
                        <div class="flex justify-between text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                            <span>ទឹកប្រាក់សរុប (Grand Total):</span>
                            <span class="text-sm font-black" x-text="`$${calculateTotal().toFixed(2)}`"></span>
                        </div>
                        <div class="pt-2 border-t border-gray-200 dark:border-slate-700">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">បានទូទាត់ (Paid Amount) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" name="paid_amount" x-model.number="paidAmount" required class="w-full px-3.5 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-emerald-600 dark:text-emerald-400 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="flex justify-between text-xs text-rose-600 dark:text-rose-400 font-bold pt-2 border-t border-gray-200 dark:border-slate-700">
                            <span>នៅជំពាក់ (Due Balance):</span>
                            <span x-text="`$${Math.max(0, calculateTotal() - paidAmount).toFixed(2)}`"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
                <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">បោះបង់</a>
                <button type="submit" class="magnetic-btn px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-indigo-500/25 transition">
                    ធ្វើបច្ចុប្បន្នភាពប័ណ្ណទិញចូល
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function editPurchaseManager() {
    return {
        availableProducts: {!! json_encode($products) !!},
        items: {!! json_encode($purchase->items->map(function($i) {
            return [
                'id'   => $i->product_id,
                'qty'  => (int) $i->quantity,
                'cost' => (float) ($i->unit_cost ?? $i->cost_price ?? $i->unit_price),
            ];
        })) !!},
        discount: {{ (float) ($purchase->discount ?? $purchase->discount_amount ?? 0) }},
        tax: {{ (float) ($purchase->tax ?? $purchase->tax_amount ?? 0) }},
        paidAmount: {{ (float) $purchase->paid_amount }},
        
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
                this.items[index].cost = parseFloat(prod.cost_price || 0);
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