<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Exibe a listagem de categorias com busca, ordenação e paginação.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');

        // Configuração de Ordenação com Whitelist de segurança
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = strtolower($request->input('sort_order', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSortColumns = ['id', 'name', 'section_id', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'id';
        }

        $query = Category::with('section');

        // Filtro opcional por seção
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->input('section_id'));
        }

        // Filtro por termo de busca no nome ou descrição
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $paginator = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'data'         => CategoryResource::collection($paginator->items()),
            'total'        => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'next_page'    => $paginator->nextPageUrl() ? $paginator->currentPage() + 1 : null,
        ]);
    }

    /**
     * Cadastra uma nova categoria.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        $category->load('section');

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Exibe uma categoria específica.
     */
    public function show(Category $category): CategoryResource
    {
        $category->load(['section', 'items']);

        return new CategoryResource($category);
    }

    /**
     * Atualiza uma categoria existente.
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category->update($request->validated());
        $category->load('section');

        return new CategoryResource($category);
    }

    /**
     * Remove uma categoria do banco de dados.
     */
    public function destroy(Category $category): JsonResponse|Response
    {
        // Regra de Integridade: impede exclusão se houver itens vinculados
        if ($category->items()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir a categoria pois existem itens cadastrados nela.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // Status 422
        }

        $category->delete();

        return response()->noContent(); // Status 204
    }
}
