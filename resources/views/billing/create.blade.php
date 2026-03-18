<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Bill') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('billing.store') }}" method="POST" x-data="billingForm()">
                        @csrf
                        <div class="mb-6">
                            <x-label for="patient_id" value="Select Patient" />
                            <select id="patient_id" name="patient_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $selectedPatientId == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ $patient->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description/Service</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Subtotal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr>
                                            <td class="px-4 py-2">
                                                <select x-model="row.type" @change="updateRowType(index)" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                                    <option value="service">Service</option>
                                                    <option value="custom">Custom Charge</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-2">
                                                <template x-if="row.type === 'service'">
                                                    <select :name="'services['+index+'][id]'" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" @change="updatePrice(index, $event.target.value)" required>
                                                        <option value="">-- Select Service --</option>
                                                        @foreach($services as $service)
                                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </template>
                                                <template x-if="row.type === 'custom'">
                                                    <input type="text" :name="'services['+index+'][custom_name]'" x-model="row.customName" placeholder="Enter charge description" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                                </template>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'services['+index+'][qty]'" x-model.number="row.qty" min="1" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" @input="calculateRow(index)" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'services['+index+'][price]'" x-model="row.price" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" @input="calculateRow(index)" step="0.01" min="0" placeholder="0.00" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" x-model="row.subtotal" class="block w-full border-gray-300 bg-gray-100 rounded-md shadow-sm text-sm" readonly>
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button" @click="removeRow(index)" class="text-red-600 hover:text-red-900 font-bold" x-show="rows.length > 1">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-6">
                            <button type="button" @click="addRow()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                + Add Service / Charge
                            </button>
                        </div>

                        <div class="flex flex-col items-end space-y-2 border-t pt-4">
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-600 font-bold">Subtotal:</span>
                                <span class="text-lg font-bold" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <x-label for="discount" value="Discount:" />
                                <x-input id="discount" name="discount" x-model.number="discount" @input="calculateTotal()" class="w-32 text-right" type="number" step="0.01" min="0" />
                            </div>
                            <div class="flex items-center space-x-4 border-t-2 border-double pt-2">
                                <span class="text-xl font-black text-gray-800 uppercase">Grand Total:</span>
                                <span class="text-2xl font-black text-blue-600" x-text="formatCurrency(total)"></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-4">
                            <x-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Generate Bill & Save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function billingForm() {
            return {
                rows: [{ type: 'service', id: '', customName: '', qty: 1, price: 0, subtotal: 0 }],
                discount: 0,
                subtotal: 0,
                total: 0,
                servicesData: {!! $services->pluck('price', 'id')->toJson() !!},

                addRow() {
                    this.rows.push({ type: 'service', id: '', customName: '', qty: 1, price: 0, subtotal: 0 });
                },

                removeRow(index) {
                    this.rows.splice(index, 1);
                    this.calculateTotal();
                },

                updateRowType(index) {
                    const row = this.rows[index];
                    if (row.type === 'service') {
                        row.id = '';
                        row.price = 0;
                    } else {
                        row.id = '';
                        row.price = 0;
                    }
                    this.calculateRow(index);
                },

                updatePrice(index, id) {
                    this.rows[index].id = id;
                    this.rows[index].price = this.servicesData[id] || 0;
                    this.calculateRow(index);
                },

                calculateRow(index) {
                    let row = this.rows[index];
                    row.subtotal = (row.qty * parseFloat(row.price || 0)).toFixed(2);
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.subtotal = this.rows.reduce((sum, row) => sum + parseFloat(row.subtotal || 0), 0).toFixed(2);
                    this.total = (this.subtotal - (this.discount || 0)).toFixed(2);
                },

                formatCurrency(val) {
                    return 'LKR ' + parseFloat(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
</x-app-layout>