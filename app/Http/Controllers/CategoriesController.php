<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Colocation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function store(Request $request, $colocationId)
    {
        
        $colocation = Colocation::with('users', 'categories')->findOrFail($colocationId);

        try {

            $this->authorize('manageCategories', $colocation);
        } catch (AuthorizationException $e) {
            return back()->with('error', 'Seul le propriétaire peut ajouter des catégories.');
        }

        $data = $request->validate([
            'name' => 'required|min:2|max:50|unique:categories,name'
        ]);
        $category = $colocation->categories()->create([
            'name' => $data['name'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Catégorie "' . $category->name . '" ajoutée avec succès.');
    }

    public function destroy($colocationId, $categoryId)
    {
        $colocation = Colocation::findOrFail($colocationId);
        $category = Categories::where('colocation_id', $colocationId)
                              ->where('id', $categoryId)
                              ->firstOrFail();
        try {
            $this->authorize('manageCategories', $colocation);
        } catch (AuthorizationException $e) {
            return back()->with('error', 'Seul le propriétaire peut supprimer des catégories.');
        }

        if ($category->colocation_id !== $colocation->id) {
            abort(404, 'Cette catégorie n\'appartient pas à cette colocation.');
        }

        $categoryName = $category->name;
        $category->delete();

        return back()->with('success', 'Catégorie "' . $categoryName . '" supprimée.');
    }
}
