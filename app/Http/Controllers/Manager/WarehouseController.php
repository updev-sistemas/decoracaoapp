<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Http\Requests\Warehouse\ViewWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource with filtering and custom pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');

        // Parametrização de Ordenação
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = strtolower($request->input('sort_order', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Whitelist de colunas permitidas para ordenação por segurança
        $allowedSortColumns = ['id', 'name', 'local', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'id';
        }

        $query = Warehouse::with('addresses');

        // Filtro por nome ou localização
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('local', 'like', "%{$search}%");
            });
        }

        // Aplica a ordenação
        $paginator = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'data'         => WarehouseResource::collection($paginator->items()),
            'total'        => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'next_page'    => $paginator->nextPageUrl() ? $paginator->currentPage() + 1 : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse, ViewWarehouseRequest $request): WarehouseResource
    {
        $warehouse->load('addresses');

        return new WarehouseResource($warehouse);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        $warehouse = Warehouse::create($request->validated());

        return (new WarehouseResource($warehouse))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): WarehouseResource
    {
        $data = $request->all();

        $warehouse->update($data);

        return new WarehouseResource($warehouse);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse): JsonResponse|Response
    {
        // Verifica se há endereços vinculados antes de deletar
        if ($warehouse->addresses()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir o armazém pois ele possui endereços cadastrados.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // Status 422
        }

        $warehouse->delete();

        return response()->noContent(); // Status 204
    }
}
