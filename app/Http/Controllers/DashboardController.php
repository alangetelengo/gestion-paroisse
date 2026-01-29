<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Expense;
use App\Models\Group;
use App\Models\Member;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $paroisseId = $isSuperAdmin ? null : $user->paroisse_id;

        // ============================================
        // STATISTIQUES MEMBRES
        // ============================================
        $memberQuery = Member::query();
        if ($paroisseId) {
            $memberQuery->where('paroisse_id', $paroisseId);
        }

        $statsMembers = [
            'total' => $memberQuery->count(),
            'actifs' => (clone $memberQuery)->where('statut', 'actif')->count(),
            'inactifs' => (clone $memberQuery)->where('statut', 'inactif')->count(),
            'decedes' => (clone $memberQuery)->where('statut', 'décédé')->count(),
            'par_sexe' => [
                'homme' => (clone $memberQuery)->where('sexe', 'homme')->count(),
                'femme' => (clone $memberQuery)->where('sexe', 'femme')->count(),
                'non_renseigne' => (clone $memberQuery)->whereNull('sexe')->orWhere('sexe', '')->count(),
            ],
        ];

        // ============================================
        // STATISTIQUES ÉVÉNEMENTS
        // ============================================
        $eventQuery = Event::query();
        if ($paroisseId) {
            $eventQuery->where('paroisse_id', $paroisseId);
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $statsEvents = [
            'total' => $eventQuery->count(),
            'a_venir' => (clone $eventQuery)->whereDate('date_evenement', '>=', $now->toDateString())->count(),
            'passes' => (clone $eventQuery)->whereDate('date_evenement', '<', $now->toDateString())->count(),
            'ce_mois' => (clone $eventQuery)
                ->whereBetween('date_evenement', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->count(),
            'par_type' => [
                'messe' => (clone $eventQuery)->where('type', 'messe')->count(),
                'célébration' => (clone $eventQuery)->where('type', 'célébration')->count(),
                'activité' => (clone $eventQuery)->where('type', 'activité')->count(),
            ],
        ];

        // ============================================
        // STATISTIQUES FINANCIÈRES
        // ============================================
        $revenueQuery = Revenue::query()->where('revenues.statut', 'valide');
        $expenseQuery = Expense::query()->where('statut', 'valide');

        if ($paroisseId) {
            $revenueQuery->where('revenues.paroisse_id', $paroisseId);
            $expenseQuery->where('paroisse_id', $paroisseId);
        }

        // Mois en cours
        $revenuesMois = (clone $revenueQuery)
            ->whereBetween('date_recette', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('montant');

        $expensesMois = (clone $expenseQuery)
            ->whereBetween('date_depense', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('montant');

        // Année en cours
        $startOfYear = $now->copy()->startOfYear();
        $endOfYear = $now->copy()->endOfYear();

        $revenuesAnnee = (clone $revenueQuery)
            ->whereBetween('date_recette', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->sum('montant');

        $expensesAnnee = (clone $expenseQuery)
            ->whereBetween('date_depense', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->sum('montant');

        // Total général
        $revenuesTotal = (clone $revenueQuery)->sum('montant');
        $expensesTotal = (clone $expenseQuery)->sum('montant');

        // Évolution sur les 6 derniers mois (pour graphique)
        $evolution6Mois = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
            $monthLabel = $monthStart->format('M Y');

            $revenueMonth = (clone $revenueQuery)
                ->whereBetween('date_recette', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('montant');

            $expenseMonth = (clone $expenseQuery)
                ->whereBetween('date_depense', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('montant');

            $evolution6Mois[] = [
                'mois' => $monthLabel,
                'recettes' => (float) $revenueMonth,
                'depenses' => (float) $expenseMonth,
                'solde' => (float) ($revenueMonth - $expenseMonth),
            ];
        }

        // Répartition des recettes par catégorie (pour graphique)
        $repartitionRecettes = (clone $revenueQuery)
            ->select('revenue_categories.nom as categorie', DB::raw('SUM(revenues.montant) as total'))
            ->join('revenue_categories', 'revenues.revenue_category_id', '=', 'revenue_categories.id')
            ->groupBy('revenue_categories.id', 'revenue_categories.nom')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'categorie' => $item->categorie,
                    'total' => (float) $item->total,
                ];
            })
            ->toArray();

        // Répartition des dépenses par catégorie (pour graphique)
        $repartitionDepenses = (clone $expenseQuery)
            ->select('categorie_charge', DB::raw('SUM(montant) as total'))
            ->groupBy('categorie_charge')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                $labels = [
                    'charge_fixe' => 'Charges fixes',
                    'charge_variable' => 'Charges variables',
                    'charge_exceptionnelle' => 'Charges exceptionnelles',
                ];

                return [
                    'categorie' => $labels[$item->categorie_charge] ?? $item->categorie_charge,
                    'total' => (float) $item->total,
                ];
            })
            ->toArray();

        $statsFinances = [
            'recettes_mois' => $revenuesMois,
            'depenses_mois' => $expensesMois,
            'solde_mois' => $revenuesMois - $expensesMois,
            'recettes_annee' => $revenuesAnnee,
            'depenses_annee' => $expensesAnnee,
            'solde_annee' => $revenuesAnnee - $expensesAnnee,
            'recettes_total' => $revenuesTotal,
            'depenses_total' => $expensesTotal,
            'solde_total' => $revenuesTotal - $expensesTotal,
            'evolution_6_mois' => $evolution6Mois,
            'repartition_recettes' => $repartitionRecettes,
            'repartition_depenses' => $repartitionDepenses,
        ];

        // ============================================
        // STATISTIQUES GROUPES
        // ============================================
        $groupQuery = Group::query();
        // Vérifier si la colonne paroisse_id existe avant de filtrer
        if ($paroisseId && Schema::hasColumn('groups', 'paroisse_id')) {
            $groupQuery->where('paroisse_id', $paroisseId);
        }

        $statsGroups = [
            'total' => $groupQuery->count(),
            'par_type' => [
                'chorale' => (clone $groupQuery)->where('type', 'chorale')->count(),
                'catéchisme' => (clone $groupQuery)->where('type', 'catéchisme')->count(),
                'mouvement' => (clone $groupQuery)->where('type', 'mouvement')->count(),
                'autre' => (clone $groupQuery)->where('type', 'autre')->count(),
            ],
        ];

        return view('dashboard', compact(
            'statsMembers',
            'statsEvents',
            'statsFinances',
            'statsGroups',
            'isSuperAdmin'
        ));
    }
}
