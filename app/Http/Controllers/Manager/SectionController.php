<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SectionController extends Controller
{
    /**
     * Exibe a listagem de seções (com busca, ordenação e paginação).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $sortableFields = ['id', 'name', 'created_at'];

        $sort = $request->query('sort', 'name');
        $order = strtolower($request->query('order', 'asc')) === 'desc' ? 'desc' : 'asc';

        if (! in_array($sort, $sortableFields, true)) {
            $sort = 'name';
        }

        $query = Section::query();

        // Filtro de busca por nome
        if ($request->has('search') && ! empty($request->query('search'))) {
            $search = $request->query('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Carrega opcionalmente as categorias para evitar N+1 queries
        if ($request->boolean('with_categories')) {
            $query->with('categories');
        }

        $perPage = (int) $request->query('per_page', 15);
        $sections = $query->orderBy($sort, $order)->paginate($perPage);

        return SectionResource::collection($sections);
    }

    /**
     * Cria uma nova seção.
     */
    public function store(StoreSectionRequest $request): JsonResponse
    {
        $section = Section::create($request->validated());

        return response()->json([
            'message' => 'Seção criada com sucesso.',
            'data' => new SectionResource($section),
        ], 201);
    }

    /**
     * Exibe os detalhes de uma seção específica.
     */
    public function show(Request $request, Section $section): SectionResource
    {
        if ($request->boolean('with_categories')) {
            $section->load('categories');
        }

        return new SectionResource($section);
    }

    /**
     * Atualiza os dados de uma seção existente.
     */
    public function update(UpdateSectionRequest $request, Section $section): JsonResponse
    {
        $section->update($request->validated());

        return response()->json([
            'message' => 'Seção atualizada com sucesso.',
            'data' => new SectionResource($section),
        ]);
    }

    /**
     * Remove uma seção (impede caso haja categorias vinculadas).
     */
    public function destroy(Section $section): JsonResponse
    {
        // Regra de integridade: impede deletar se houver categorias atreladas
        if ($section->categories()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir esta seção pois existem categorias vinculadas a ela.',
            ], 422);
        }

        $section->delete();

        return response()->json([
            'message' => 'Seção excluída com sucesso.',
        ]);
    }
}
