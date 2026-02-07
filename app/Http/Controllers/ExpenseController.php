<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Expense;
use App\Models\Paroisse;
use Illuminate\View\View;
use App\Traits\LogsErrors;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FlashAlert;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ExpenseController extends Controller
{
    use LogsErrors;

    public function __construct()
    {
        $this->middleware('permission:view_expenses')->only(['index']);
        $this->middleware('permission:create_expenses')->only(['create', 'store']);
        $this->middleware('permission:edit_expenses')->only(['edit', 'update']);
        $this->middleware('permission:delete_expenses')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        try {
            $user = $request->user();

            $query = Expense::query()
                ->with('paroisse')
                ->orderByDesc('date_depense');

            if (! $user->hasRole('super_admin')) {
                $query->where('paroisse_id', $user->paroisse_id);
            }

            if ($request->filled('categorie_charge')) {
                $query->where('categorie_charge', $request->string('categorie_charge')->value());
            }

            if ($request->filled('type_charge')) {
                $query->where('type_charge', $request->string('type_charge')->value());
            }

            if ($request->filled('paroisse_id')) {
                $query->where('paroisse_id', $request->integer('paroisse_id'));
            }

            if ($request->filled('date_from')) {
                $query->whereDate('date_depense', '>=', $request->date('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('date_depense', '<=', $request->date('date_to'));
            }

            if ($request->filled('q')) {
                $q = $request->string('q')->lower()->value();
                $query->where(function ($sub) use ($q): void {
                    $sub->whereRaw('LOWER(fournisseur) LIKE ?', ["%{$q}%"])
                        ->orWhereRaw('LOWER(facture_reference) LIKE ?', ["%{$q}%"])
                        ->orWhereRaw('LOWER(notes) LIKE ?', ["%{$q}%"])
                        ->orWhereRaw('LOWER(libelle) LIKE ?', ["%{$q}%"]);
                });
            }

            $expenses = $query->paginate(15)->withQueryString();

            $paroisses = $user->hasRole('super_admin')
                ? Paroisse::orderBy('nom')->get()
                : collect();

            return view('expenses.index', [
                'expenses' => $expenses,
                'paroisses' => $paroisses,
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Erreur lors du chargement de la liste des dépenses');
            FlashAlert::error('Une erreur est survenue lors du chargement des dépenses.');

            return view('expenses.index', [
                'expenses' => collect(),
                'paroisses' => collect(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        $paroisses = $user->hasRole('super_admin')
            ? Paroisse::orderBy('nom')->get()
            : Paroisse::whereKey($user->paroisse_id)->get();

        return view('expenses.create', [
            'expense' => new Expense(),
            'paroisses' => $paroisses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'paroisse_id' => ['nullable', 'exists:paroisses,id'],
                'categorie_charge' => ['required', 'in:charge_fixe,charge_variable,charge_exceptionnelle,alimentation_popote'],
                'type_charge' => ['nullable', 'in:carburant,hosties,internet,maintenance_materiel,gaz,eau,electricite,gardiennage,salaire_ouvrier,autre,alimentation'],
                'date_depense' => ['required', 'date'],
                'montant' => ['required', 'numeric', 'min:0'],
                'jour_semaine' => ['nullable', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'],
                'libelle' => ['nullable', 'string', 'max:500'],
                'facture_reference' => ['nullable', 'string', 'max:255'],
                'fournisseur' => ['nullable', 'string', 'max:255'],
                'methode_paiement' => ['required', 'in:especes,cheque,virement,carte,mobile_money'],
                'piece_facture' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:4096'],
                'piece_recu' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:4096'],
                'notes' => ['nullable', 'string'],
            ]);

            if ($validated['categorie_charge'] === 'alimentation_popote') {
                $request->validate(['libelle' => ['required', 'string', 'max:500']]);
                $validated['type_charge'] = 'alimentation';
                if (empty($validated['jour_semaine'])) {
                    $d = \Carbon\Carbon::parse($validated['date_depense']);
                    $validated['jour_semaine'] = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'][$d->dayOfWeek];
                }
                $validated['facture_reference'] = $validated['facture_reference'] ?? null;
                $validated['fournisseur'] = $validated['fournisseur'] ?? null;
            } else {
                $request->validate(['type_charge' => ['required', 'in:carburant,hosties,internet,maintenance_materiel,gaz,eau,electricite,gardiennage,salaire_ouvrier,autre']]);
                $validated['libelle'] = null;
                $validated['jour_semaine'] = null;
            }

            if (! $user->hasRole('super_admin')) {
                $validated['paroisse_id'] = $user->paroisse_id;
            }

            if ($request->hasFile('piece_facture')) {
                $validated['piece_facture_path'] = $request->file('piece_facture')->store('expenses/factures', 'public');
            }

            if ($request->hasFile('piece_recu')) {
                $validated['piece_recu_path'] = $request->file('piece_recu')->store('expenses/recus', 'public');
            }

            $validated['created_by'] = $user->id;

            Expense::create($validated);

            FlashAlert::success('Dépense enregistrée avec succès.');

            return redirect()->route('expenses.index');
        } catch (Throwable $e) {
            $this->logError($e, 'Erreur lors de la création de la dépense', ['data' => $request->all()]);
            FlashAlert::error('Une erreur est survenue lors de l\'enregistrement de la dépense.');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Expense $expense): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasRole('super_admin') && $expense->paroisse_id !== $user->paroisse_id) {
            FlashAlert::error('Vous ne pouvez modifier que les dépenses de votre paroisse.');

            return redirect()->route('expenses.index');
        }

        $paroisses = $user->hasRole('super_admin')
            ? Paroisse::orderBy('nom')->get()
            : Paroisse::whereKey($user->paroisse_id)->get();

        return view('expenses.edit', [
            'expense' => $expense,
            'paroisses' => $paroisses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        try {
            $user = $request->user();

            if (! $user->hasRole('super_admin') && $expense->paroisse_id !== $user->paroisse_id) {
                FlashAlert::error('Vous ne pouvez modifier que les dépenses de votre paroisse.');

                return redirect()->route('expenses.index');
            }

            $validated = $request->validate([
                'paroisse_id' => ['nullable', 'exists:paroisses,id'],
                'categorie_charge' => ['required', 'in:charge_fixe,charge_variable,charge_exceptionnelle,alimentation_popote'],
                'type_charge' => ['nullable', 'in:carburant,hosties,internet,maintenance_materiel,gaz,eau,electricite,gardiennage,salaire_ouvrier,autre,alimentation'],
                'date_depense' => ['required', 'date'],
                'montant' => ['required', 'numeric', 'min:0'],
                'jour_semaine' => ['nullable', 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche'],
                'libelle' => ['nullable', 'string', 'max:500'],
                'facture_reference' => ['nullable', 'string', 'max:255'],
                'fournisseur' => ['nullable', 'string', 'max:255'],
                'methode_paiement' => ['required', 'in:especes,cheque,virement,carte,mobile_money'],
                'piece_facture' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:4096'],
                'piece_recu' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:4096'],
                'notes' => ['nullable', 'string'],
            ]);

            if ($validated['categorie_charge'] === 'alimentation_popote') {
                $request->validate(['libelle' => ['required', 'string', 'max:500']]);
                $validated['type_charge'] = 'alimentation';
                if (empty($validated['jour_semaine'])) {
                    $d = \Carbon\Carbon::parse($validated['date_depense']);
                    $validated['jour_semaine'] = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'][$d->dayOfWeek];
                }
                $validated['facture_reference'] = $validated['facture_reference'] ?? null;
                $validated['fournisseur'] = $validated['fournisseur'] ?? null;
            } else {
                $request->validate(['type_charge' => ['required', 'in:carburant,hosties,internet,maintenance_materiel,gaz,eau,electricite,gardiennage,salaire_ouvrier,autre']]);
                $validated['libelle'] = null;
                $validated['jour_semaine'] = null;
            }

            if (! $user->hasRole('super_admin')) {
                $validated['paroisse_id'] = $user->paroisse_id;
            }

            if ($request->hasFile('piece_facture')) {
                if ($expense->piece_facture_path) {
                    Storage::disk('public')->delete($expense->piece_facture_path);
                }
                $validated['piece_facture_path'] = $request->file('piece_facture')->store('expenses/factures', 'public');
            }

            if ($request->hasFile('piece_recu')) {
                if ($expense->piece_recu_path) {
                    Storage::disk('public')->delete($expense->piece_recu_path);
                }
                $validated['piece_recu_path'] = $request->file('piece_recu')->store('expenses/recus', 'public');
            }

            $expense->update($validated);

            FlashAlert::success('Dépense mise à jour avec succès.');

            return redirect()->route('expenses.index');
        } catch (Throwable $e) {
            $this->logError($e, 'Erreur lors de la mise à jour de la dépense', ['expense_id' => $expense->id]);
            FlashAlert::error('Une erreur est survenue lors de la mise à jour de la dépense.');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Expense $expense): RedirectResponse
    {
        try {
            $user = $request->user();

            if (! $user->hasRole('super_admin') && $expense->paroisse_id !== $user->paroisse_id) {
                FlashAlert::error('Vous ne pouvez supprimer que les dépenses de votre paroisse.');

                return redirect()->route('expenses.index');
            }

            $expense->delete();

            FlashAlert::success('Dépense supprimée avec succès.');
        } catch (Throwable $e) {
            $this->logError($e, 'Erreur lors de la suppression de la dépense', ['expense_id' => $expense->id]);
            FlashAlert::error('Une erreur est survenue lors de la suppression de la dépense.');
        }

        return redirect()->route('expenses.index');
    }
}
