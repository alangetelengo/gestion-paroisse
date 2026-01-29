<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParoisseRequest;
use App\Http\Requests\UpdateParoisseRequest;
use App\Helpers\FlashAlert;
use App\Models\Paroisse;
use App\Traits\LogsErrors;
use Illuminate\Http\Request;

class ParoisseController extends Controller
{
    use LogsErrors;

    /**
     * Affiche la liste des paroisses
     */
    public function index()
    {
        try {
            $paroisses = Paroisse::with('curé')
                ->when(auth()->check() && !auth()->user()->hasRole('super_admin'), function ($query) {
                    // Si pas super admin, voir seulement sa paroisse
                    if (auth()->user()->paroisse_id) {
                        return $query->where('id', auth()->user()->paroisse_id);
                    }
                    return $query->whereRaw('1 = 0'); // Aucun résultat si pas de paroisse
                })
                ->latest()
                ->paginate(15);

            return view('paroisses.index', compact('paroisses'));
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la récupération des paroisses', $e);
            FlashAlert::error('Une erreur est survenue lors de la récupération des paroisses.');
            return redirect()->route('dashboard');
        }
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        try {
            $members = \App\Models\Member::where('statut', 'actif')->get();
            return view('paroisses.create', compact('members'));
        } catch (\Exception $e) {
            // Si la table members n'existe pas encore, passer un tableau vide
            $members = collect();
            return view('paroisses.create', compact('members'));
        }
    }

    /**
     * Enregistre une nouvelle paroisse
     */
    public function store(StoreParoisseRequest $request)
    {
        try {
            $validated = $request->validated();

            $paroisse = Paroisse::create($validated);

            $this->logInfo("Paroisse créée : {$paroisse->nom}", ['paroisse_id' => $paroisse->id]);
            FlashAlert::success("La paroisse a été créée avec succès.");

            return redirect()->route('paroisses.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la création de la paroisse', $e, ['data' => $request->all()]);
            FlashAlert::error('Une erreur est survenue lors de la création de la paroisse.');
            return back()->withInput();
        }
    }

    /**
     * Affiche une paroisse
     */
    public function show(Paroisse $paroisse)
    {
        $paroisse->load(['curé', 'configurations']);
        return view('paroisses.show', compact('paroisse'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Paroisse $paroisse)
    {
        try {
            $members = \App\Models\Member::where('statut', 'actif')->get();
            return view('paroisses.edit', compact('paroisse', 'members'));
        } catch (\Exception $e) {
            // Si la table members n'existe pas encore, passer un tableau vide
            $members = collect();
            return view('paroisses.edit', compact('paroisse', 'members'));
        }
    }

    /**
     * Met à jour une paroisse
     */
    public function update(UpdateParoisseRequest $request, Paroisse $paroisse)
    {
        try {
            $validated = $request->validated();

            $paroisse->update($validated);

            $this->logInfo("Paroisse mise à jour : {$paroisse->nom}", ['paroisse_id' => $paroisse->id]);
            FlashAlert::success("La paroisse a été mise à jour avec succès.");

            return redirect()->route('paroisses.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la mise à jour de la paroisse', $e, [
                'paroisse_id' => $paroisse->id,
                'data' => $request->all()
            ]);
            FlashAlert::error('Une erreur est survenue lors de la mise à jour de la paroisse.');
            return back()->withInput();
        }
    }

    /**
     * Supprime une paroisse
     */
    public function destroy(Paroisse $paroisse)
    {
        try {
            $paroisseNom = $paroisse->nom;
            $paroisse->update(['actif' => false]); // Soft delete en désactivant

            $this->logInfo("Paroisse désactivée : {$paroisseNom}", ['paroisse_id' => $paroisse->id]);
            FlashAlert::success("La paroisse a été désactivée avec succès.");

            return redirect()->route('paroisses.index');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la suppression de la paroisse', $e, ['paroisse_id' => $paroisse->id]);
            FlashAlert::error('Une erreur est survenue lors de la suppression de la paroisse.');
            return back();
        }
    }
}
