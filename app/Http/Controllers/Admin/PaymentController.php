<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

use App\Models\Payments;
use App\Exports\PaymentsExport;
use App\Models\MenuItems;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'payment_id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $perPage = $request->input('per_page', 10);

        $query = Payments::with(['employee', 'order.customer']);

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('payment_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('final_price', 'like', '%' . $searchTerm . '%')
                ->orWhere('discount_amount', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('employee', function($q2) use ($searchTerm) {
                    $q2->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('order.customer', function($q2) use ($searchTerm) {
                    $q2->where('name', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('order', function($q2) use ($searchTerm) {
                    $q2->where('total_price', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Lọc theo khoảng ngày nếu có
        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
                $query->whereBetween('payment_time', [$start, $end]);
            } catch (\Exception $e) {
                
            }
        }
    

        if ($sortField === 'payment_id') {
            $query->orderByRaw("CAST(payment_id AS DECIMAL) $sortDirection");
        } elseif ($sortField === 'employee_name') {
            $query->join('employees', 'payments.employee_id', '=', 'employees.employee_id')
                ->orderBy('employees.name', $sortDirection)
                ->select('payments.*');
        } elseif ($sortField === 'customer_name') {
            $query->join('orders', 'payments.order_id', '=', 'orders.order_id')
                ->join('customers', 'orders.customer_id', '=', 'customers.customer_id')
                ->orderBy('customers.name', $sortDirection)
                ->select('payments.*');
        } elseif ($sortField === 'total_price') {
            $query->join('orders', 'payments.order_id', '=', 'orders.order_id')
                ->orderBy('orders.total_price', $sortDirection)
                ->select('payments.*');
        } elseif ($sortField === 'payment_method') {
            $query->orderByRaw("CASE payment_method 
                WHEN 'cash' THEN 2 
                WHEN 'bank_transfer' THEN 1 
        ELSE 3 END $sortDirection");
        } elseif ($sortField === 'final_price') {
            $query->orderBy('final_price', $sortDirection);
        } elseif ($sortField === 'discount_amount') {
            $query->orderBy('discount_amount', $sortDirection);

        }else {
            // Nếu không khớp, sắp xếp theo payment_id
            $query->orderByRaw("CAST(payment_id AS DECIMAL) $sortDirection");
        }

        // $payments = $query->get();

        $payments = $query->paginate($perPage)->appends(request()->except('page'));


        return view('admin.payment.index')
            ->with('payments', $payments)
            ->with('sortField', $sortField)
            ->with('sortDirection', $sortDirection);
    }


    public function show($id)
    {
        $payment = Payments::with([
            'employee',
            'order.customer',
            'order.table',
            'promotion',
            'order.orderItems' 
        ])->findOrFail($id);

        if ($payment->order && $payment->order->orderItems) {
            $orderItems = $payment->order->orderItems->map(function ($orderItem) {
                $item = MenuItems::find($orderItem->item_id);
                $orderItem->item = $item;
                return $orderItem;
            });
            $payment->order->orderItems = $orderItems;
        }

        return response()->json($payment);
    }

    public function exportExcel()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
}