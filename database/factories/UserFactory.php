<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $random = $this->faker->randomElement([0, 1]);
        switch ($random) {
            case 0:
                $firstname = $this->faker->firstNameMale;
                $lastname = $this->faker->lastName;
                $avatar = 'man.png';
                break;
            case 1:
                $firstname = $this->faker->firstNameFemale;
                $lastname = $this->faker->lastName;
                $avatar = 'woman.png';
                break;
        }
        $role = $this->faker->randomElement(['formateur', 'formateur', 'formateur', 'formateur', 'admin']);

        return [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => strtolower($firstname) . '.' . strtolower($lastname) . '@' . $this->faker->safeEmailDomain,
            'password' => bcrypt($this->faker->unique()->password),
            'fonction' => 'formateur',
            'role' => $role,
            'avatar' => $avatar,
        ];
    }
}
