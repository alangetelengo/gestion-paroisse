<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Paroisse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Prénoms et noms congolais pour plus de cohérence
        $prenomsHommes = [
            'Jean', 'Paul', 'Pierre', 'Alain', 'Serge',
            'Christian', 'Didier', 'Henri', 'Michel', 'François',
        ];

        $prenomsFemmes = [
            'Marie', 'Thérèse', 'Jeanne', 'Clarisse', 'Brigitte',
            'Sylvie', 'Catherine', 'Chantal', 'Monique', 'Hélène',
        ];

        $nomsCongolais = [
            'Mabiala', 'Ngoma', 'Obounou', 'Makaya', 'Nkouka',
            'Massamba', 'Ondongo', 'Moukoko', 'Lissouba', 'Yambot',
            'Kibangou', 'Kodia', 'Loufoua', 'Ndinga', 'Koumba',
        ];

        $sexe = $this->faker->randomElement(['M', 'F']);

        $prenom = $sexe === 'M'
            ? $this->faker->randomElement($prenomsHommes)
            : $this->faker->randomElement($prenomsFemmes);

        $nom = $this->faker->randomElement($nomsCongolais);

        // Téléphone au format Congo (+242 06 xxx xx xx) compatible avec le regex paramétré
        // utilisation de "unique" pour limiter les doublons visibles.
        $numero = '06' . $this->faker->unique()->numerify('#######'); // 9 chiffres au total après +242
        $telephone = '+242' . $numero;

        // Paroisse aléatoire (si plusieurs)
        $paroisse = Paroisse::inRandomOrder()->first();

        // Statuts strictement conformes à l'ENUM de la table:
        // ['actif', 'inactif', 'décédé']
        $statuts = ['actif', 'inactif', 'décédé'];

        return [
            'prenom' => $prenom,
            'nom' => Str::upper($nom),
            'date_naissance' => $this->faker->dateTimeBetween('-70 years', '-18 years'),
            'sexe' => $sexe,
            'adresse' => $this->faker->streetAddress(),
            'telephone' => $telephone,
            'email' => Str::ascii(Str::lower($prenom . '.' . $nom)) . $this->faker->unique()->numerify('##') . '@example.cg',
            'statut' => $this->faker->randomElement($statuts),
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'paroisse_id' => $paroisse?->id,
        ];
    }
}

