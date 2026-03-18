<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\Service;
use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        $bills = Bill::with('patient')->latest()->paginate(10);
        return view('billing.index', compact('bills'));
    }

    public function create(Request $request)
    {
        $patients = Patient::all();
        $services = Service::where('active', true)->get();
        $selectedPatientId = $request->patient_id;
        return view('billing.create', compact('patients', 'services', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'services' => 'required|array',
            'services.*.id' => 'nullable|exists:services,id',
            'services.*.custom_name' => 'nullable|string|max:255',
            'services.*.qty' => 'required|integer|min:1',
            'services.*.price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $items = [];

            foreach ($request->services as $s) {
                $serviceId = isset($s['id']) && $s['id'] !== '' ? $s['id'] : null;
                $customName = $s['custom_name'] ?? null;
                
                // If it's a predefined service, get its price
                if ($serviceId) {
                    $service = Service::find($serviceId);
                    $price = floatval($service->price);
                    $name = $service->name;
                } else {
                    // Custom charge - use the submitted price
                    $price = isset($s['price']) && $s['price'] !== '' ? floatval($s['price']) : 0;
                    $name = $customName;
                }
                
                $qty = intval($s['qty']);
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;
                
                $items[] = [
                    'service_id' => $serviceId,
                    'description' => $name,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discount = $request->discount ?? 0;
            $total = $subtotal - $discount;

            // Debug: Log what we're saving
            // logger()->info('Bill Data', ['subtotal' => $subtotal, 'discount' => $discount, 'total' => $total, 'items' => $items]);

            $bill = Bill::create([
                'bill_number' => 'BILL-' . strtoupper(uniqid()),
                'patient_id' => $request->patient_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'unpaid', // Default to unpaid until payment is confirmed
            ]);

            foreach ($items as $item) {
                $bill->items()->create($item);
            }

            return redirect()->route('billing.show', $bill)->with('success', 'Bill generated successfully.');
        });
    }

    public function show(Bill $bill)
    {
        $bill->load('patient', 'items.service');
        return view('billing.show', compact('bill'));
    }

    public function markAsPaid(Bill $bill)
    {
        $bill->update(['status' => 'paid']);
        return redirect()->route('billing.show', $bill)->with('success', 'Payment confirmed! Bill marked as paid.');
    }

    public function markAsUnpaid(Bill $bill)
    {
        $bill->update(['status' => 'unpaid']);
        return redirect()->route('billing.show', $bill)->with('success', 'Bill marked as unpaid.');
    }
}
