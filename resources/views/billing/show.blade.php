<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }} - {{ $bill->bill_number }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm">
                    📄 Print / Save PDF
                </button>
                <button onclick="sendWhatsApp()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    📱 Send WhatsApp
                </button>
                <button onclick="downloadPDF()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    💾 Download PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 invoice-container">
                <div class="flex justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-black text-blue-600 uppercase">Welcare</h1>
                        <p class="text-gray-500 text-sm italic">Premium Healthcare Services</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-2xl font-bold uppercase text-gray-800">Invoice</h2>
                        <p class="text-sm text-gray-600">No: {{ $bill->bill_number }}</p>
                        <p class="text-sm text-gray-600">Date: {{ $bill->created_at->format('M d, Y') }}</p>
                        @if($bill->status == 'paid')
                            <p class="mt-2 text-lg font-bold text-green-600">✓ PAID</p>
                        @else
                            <p class="mt-2 text-lg font-bold text-red-600">✗ UNPAID</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Billed To:</h3>
                        <p class="font-bold text-gray-800">{{ $bill->patient->name }}</p>
                        <p class="text-sm text-gray-600">Code: {{ $bill->patient->code }}</p>
                        <p class="text-sm text-gray-600">{{ $bill->patient->phone }}</p>
                        <p class="text-sm text-gray-600">{{ $bill->patient->address }}</p>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200 mb-8">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Service Description</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase">Price</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bill->items as $item)
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->service ? $item->service->name : $item->description }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 text-right">LKR {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800 text-right font-medium">LKR {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal:</span>
                            <span class="text-gray-800 font-medium">LKR {{ number_format($bill->subtotal, 2) }}</span>
                        </div>
                        @if($bill->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Discount:</span>
                                <span class="text-red-500 font-medium">- LKR {{ number_format($bill->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="text-lg font-bold text-gray-800 uppercase">Total:</span>
                            <span class="text-xl font-black text-blue-600 font-bold">LKR {{ number_format($bill->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-12 border-t pt-8 text-center">
                    <p class="text-gray-400 text-xs uppercase tracking-widest font-bold">Thank you for choosing Welcare</p>
                </div>

                <!-- Payment Actions - Only visible on screen, not in print -->
                @if(session('success'))
                    <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mt-6 no-print">
                    @if($bill->status == 'unpaid')
                        <form action="{{ route('billing.mark-as-paid', $bill) }}" method="POST" class="flex justify-center">
                            @csrf
                            <button type="submit" onclick="return confirm('Confirm payment received?')" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg shadow-lg transform transition hover:scale-105">
                                ✅ Confirm Payment Received
                            </button>
                        </form>
                    @else
                        <form action="{{ route('billing.mark-as-unpaid', $bill) }}" method="POST" class="flex justify-center">
                            @csrf
                            <button type="submit" onclick="return confirm('Mark this bill as unpaid?')" 
                                class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg text-lg shadow-lg transform transition hover:scale-105">
                                ⚠️ Mark as Unpaid
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function sendWhatsApp() {
            const patientName = {!! json_encode($bill->patient->name) !!};
            const billNumber = {!! json_encode($bill->bill_number) !!};
            const date = {!! json_encode($bill->created_at->format('M d, Y')) !!};
            
            let message = `*INVOICE - WELCARE*\n`;
            message += `━━━━━━━━━━━━━━━━━━━\n\n`;
            message += `📋 Invoice No: ${billNumber}\n`;
            message += `📅 Date: ${date}\n\n`;
            message += `👤 Patient: ${patientName}\n\n`;
            message += `━━━━━━━━━━━━━━━━━━━\n`;
            message += `*ITEMS:*\n\n`;
            
            @foreach($bill->items as $item)
                message += `▫️ {{ $item->service ? $item->service->name : $item->description }}\n`;
                message += `   Qty: {{ $item->quantity }} x LKR {{ number_format($item->unit_price, 2) }} = LKR {{ number_format($item->subtotal, 2) }}\n\n`;
            @endforeach
            
            message += `━━━━━━━━━━━━━━━━━━━\n`;
            message += `💰 *SUBTOTAL: LKR {{ number_format($bill->subtotal, 2) }}*\n`;
            @if($bill->discount > 0)
                message += `🎁 Discount: - LKR {{ number_format($bill->discount, 2) }}\n`;
            @endif
            message += `💵 *TOTAL: LKR {{ number_format($bill->total, 2) }}*\n`;
            message += `━━━━━━━━━━━━━━━━━━━\n\n`;
            message += `Thank you for choosing Welcare! 🏥\n\n`;
            message += `📄 To receive this invoice as PDF, please use the "Print" option and select "Save as PDF".`;
            
            // Encode for URL
            const encodedMessage = encodeURIComponent(message);
            
            // Open WhatsApp with the message
            window.open(`https://wa.me/?text=${encodedMessage}`, '_blank');
        }

        function downloadPDF() {
            // Trigger print dialog which allows saving as PDF
            window.print();
        }
    </script>
    
    <style>
        @media print {
            .no-print { display: none !important; }
            .py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
            .shadow-sm { shadow: none !important; }
            body { background: white !important; }
            .invoice-container { border: none !important; shadow: none !important; }
        }
    </style>
</x-app-layout>