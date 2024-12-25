<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index()
    {
        $orders = Order::with(['customer', 'service'])->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'pickup_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $service = Service::find($request->service_id);
        $total_price = $request->weight * $service->price_per_kg;

        Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'weight' => $request->weight,
            'total_price' => $total_price,
            'status' => 'pending',
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes
        ]);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat');
    }

    public function edit(Order $order)
    {
        $customers = Customer::all();
        $services = Service::all();
        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'status' => ['required', 'string', Rule::in(Order::getStatuses())],
            'pickup_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $service = Service::find($request->service_id);
        $total_price = $request->weight * $service->price_per_kg;

        $oldStatus = $order->status;
        
        $order->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'weight' => $request->weight,
            'total_price' => $total_price,
            'status' => $request->status,
            'pickup_date' => $request->pickup_date,
            'notes' => $request->notes
        ]);

        // Cek jika status berubah menjadi completed
        if ($request->status === Order::STATUS_COMPLETED && $oldStatus !== Order::STATUS_COMPLETED) {
            $this->sendCompletionNotification($order);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil diupdate');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(Order::getStatuses())]
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Jika status berubah menjadi completed, kirim notifikasi
        if ($request->status === Order::STATUS_COMPLETED && $oldStatus !== Order::STATUS_COMPLETED) {
            $this->sendCompletionNotification($order);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui');
    }

    private function sendCompletionNotification(Order $order)
    {
        try {
            $customer = $order->customer;
            if (!$customer || !$customer->telegram_username) {
                Log::warning('Cannot send notification: No customer or Telegram username', [
                    'order_id' => $order->id
                ]);
                return;
            }

            $message = "✨ <b>Notifikasi Laundry</b> ✨\n\n"
                . "Halo <b>{$customer->name}</b>,\n\n"
                . "Pesanan laundry Anda telah selesai dan siap diambil:\n\n"
                . "📋 No. Order: <b>{$order->order_number}</b>\n"
                . "👕 Layanan: <b>{$order->service->name}</b>\n"
                . "⚖️ Berat: <b>{$order->weight} kg</b>\n"
                . "💰 Total: <b>Rp " . number_format($order->total_price, 0, ',', '.') . "</b>\n\n"
                . "Silakan ambil pesanan Anda di toko kami.\n"
                . "Terima kasih telah menggunakan jasa laundry kami! 🙏";

            Log::info('Sending Telegram notification:', [
                'order_id' => $order->id,
                'username' => $customer->telegram_username,
                'message' => $message
            ]);

            $sent = $this->telegramService->sendMessage($customer->telegram_username, $message);

            if (!$sent) {
                Log::warning('Failed to send Telegram notification', [
                    'order_id' => $order->id,
                    'username' => $customer->telegram_username
                ]);
            } else {
                Log::info('Telegram notification sent successfully', [
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending notification:', [
                'message' => $e->getMessage(),
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
