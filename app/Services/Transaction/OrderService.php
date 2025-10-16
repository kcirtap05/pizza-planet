<?php 

namespace App\Services\Transaction;

use App\Http\Resources\Transaction\OrderResource;
use App\Models\Menu\Pizza;
use App\Models\Menu\Topping;
use App\Models\Transaction\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService {
    private $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function grid($number_per_page) 
    {
        $start = microtime(true);

        $data = $this->model->paginate($number_per_page); 

        $lists = Cache::remember('all_orders', now()->addMinutes(10), function () use ($data) {
            return $data; 
        });

        return response()->json([
            'success' => true,
            'message' => "Found {$lists->total()} orders in total",
            'data' => OrderResource::collection($lists),
            'meta' => [
                'current_page' => $lists->currentPage(),
                'per_page' => $lists->perPage(),
                'total' => $lists->total(),
                'last_page' => $lists->lastPage(),
                'latency' => microtime(true) - $start
            ],
        ]);
    }

    public function create(array $data) 
    {
        $start = microtime(true);
        try {

            DB::beginTransaction();
            if (isset($data['pizza_id'])) {
                $pizza = Pizza::findOrFail($data['pizza_id']);
                $total = $pizza->price;
            } else {
                $toppings = Topping::whereIn('id', $data['toppings'] ?? [])->get();
                $total = 10 + count($toppings);
                $data['custom_toppings'] = $toppings->pluck('name');
            }
            $data['total_price'] = $total;
            $order = $this->model->create($data);

            DB::commit();


            // Mock payment
            \Log::info("Payment processed via {$data['payment_method']} for order #{$order->id}");


            Cache::forget('all_orders'); 

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'latency' => microtime(true) - $start
            ]);

        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'code'   => 500,
                'status'  => 'fail',
                'message' => 'UNHANDLED EXCEPTION',
                'data'    => $e->getMessage(),
            ]);
        }
    }

    public function update($id,$data) 
    {
        $start = microtime(true);
        try {

            DB::beginTransaction();
            $order_data = tap($this->model->find($id))->update($data);
            DB::commit();

            Cache::forget('all_orders'); 

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully updated!',
                'order' => OrderResource::collection($order_data),
                'latency' => microtime(true) - $start
            ]);
            
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'code'   => 500,
                'status'  => 'fail',
                'message' => 'UNHANDLED EXCEPTION',
                'data'    => $e->getMessage(),
            ]);
        }
    }

    public function delete($id) 
    {
        try {

            DB::beginTransaction();
            $data = tap($this->model->find($id))->delete();
            DB::commit();

            Cache::forget('all_orders'); 

            return new OrderResource($data);

        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'code'   => 500,
                'status'  => 'fail',
                'message' => 'UNHANDLED EXCEPTION',
                'data'    => $e->getMessage(),
            ]);
        }
        
    }
}