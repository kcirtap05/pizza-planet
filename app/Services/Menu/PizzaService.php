<?php 

namespace App\Services\Menu;

use App\Http\Resources\Menu\PizzaResource;
use App\Models\Menu\Pizza;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class PizzaService {
    
    private $model;

    public function __construct(Pizza $model)
    {
        $this->model = $model;
    }

    public function grid($number_per_page) 
    {
        $start = microtime(true);

        $data = $this->model->paginate($number_per_page); 

        $lists = Cache::remember('all_pizzas', now()->addMinutes(10), function () use ($data) {
            return $data; 
        });

        return response()->json([
            'success' => true,
            'message' => "Found {$lists->total()} pizzas in total",
            'data' => PizzaResource::collection($lists),
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
            $this->model->create($data);
            DB::commit();

            Cache::forget('all_pizzas'); 

            return response()->json([
                'success' => true,
                'message' => 'successfully created!',
                'order' => PizzaResource::collection($data),
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
            $pizza_data = tap($this->model->find($id))->update($data);
            DB::commit();

            Cache::forget('all_pizzas'); 

            return response()->json([
                'success' => true,
                'message' => 'successfully updated!',
                'order' => PizzaResource::collection($pizza_data),
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

            Cache::forget('all_pizzas'); 

            return new PizzaResource($data);

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