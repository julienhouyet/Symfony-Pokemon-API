<?php

namespace App\DataFixtures;

use App\Factory\StatFactory;
use App\Factory\TypeFactory;
use App\Factory\UserFactory;
use App\Factory\PokemonFactory;
use App\Factory\PokemonStatFactory;
use App\Factory\PokemonTypeFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$this->createAdminUser();

		TypeFactory::createMany(10);
		StatFactory::createMany(8);
		PokemonFactory::createMany(40);

		$stats = StatFactory::repository()->findAll();
		$pokemons = PokemonFactory::repository()->findAll();

		foreach ($pokemons as $pokemon) {
			$numberOfTypes = random_int(1, 2);
			for ($slot = 1; $slot <= $numberOfTypes; $slot++) {
				PokemonTypeFactory::createOne([
					'pokemon' => $pokemon,
					'slot' => $slot,
				]);
			}

			shuffle($stats);

			$numberOfStats = random_int(1, 6);
			$selectedStats = array_slice($stats, 0, $numberOfStats);

			foreach ($selectedStats as $stat) {
				PokemonStatFactory::createOne([
					'pokemon' => $pokemon,
					'stat' => $stat,
				]);
			}
		}

		$manager->flush();
		UserFactory::createMany(10);
		$manager->flush();
	}

	private function createAdminUser(): void
	{
		$existingAdmin = UserFactory::repository()->findOneBy(['email' => 'admin@pokemonmail.com']);

		if (!$existingAdmin) {
			UserFactory::new()->create([
				'email' => 'admin@pokemonmail.com',
				'roles' => ['ROLE_ADMIN'],
				'password' => 'root',
				'username' => 'admin',
			]);
		}
	}
}
