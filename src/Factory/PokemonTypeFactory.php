<?php

namespace App\Factory;

use App\Entity\PokemonType;
use App\Repository\PokemonTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PokemonType>
 *
 * @method        PokemonType|Proxy                     create(array|callable $attributes = [])
 * @method static PokemonType|Proxy                     createOne(array $attributes = [])
 * @method static PokemonType|Proxy                     find(object|array|mixed $criteria)
 * @method static PokemonType|Proxy                     findOrCreate(array $attributes)
 * @method static PokemonType|Proxy                     first(string $sortedField = 'id')
 * @method static PokemonType|Proxy                     last(string $sortedField = 'id')
 * @method static PokemonType|Proxy                     random(array $attributes = [])
 * @method static PokemonType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PokemonTypeRepository|RepositoryProxy repository()
 * @method static PokemonType[]|Proxy[]                 all()
 * @method static PokemonType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PokemonType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PokemonType[]|Proxy[]                 findBy(array $attributes)
 * @method static PokemonType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PokemonType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PokemonTypeFactory extends ModelFactory
{
	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
	 *
	 * @todo inject services if required
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
	 *
	 * @todo add your default values here
	 */
	protected function getDefaults(): array
	{
		return [
			'pokemon' => PokemonFactory::randomOrCreate(),
			'slot' => self::faker()->numberBetween(1, 2),
			'type' => TypeFactory::randomOrCreate(),
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(PokemonType $pokemonType): void {})
		;
	}

	protected static function getClass(): string
	{
		return PokemonType::class;
	}
}
