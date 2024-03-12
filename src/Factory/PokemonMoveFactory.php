<?php

namespace App\Factory;

use App\Entity\PokemonMove;
use App\Repository\PokemonMoveRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PokemonMove>
 *
 * @method        PokemonMove|Proxy                     create(array|callable $attributes = [])
 * @method static PokemonMove|Proxy                     createOne(array $attributes = [])
 * @method static PokemonMove|Proxy                     find(object|array|mixed $criteria)
 * @method static PokemonMove|Proxy                     findOrCreate(array $attributes)
 * @method static PokemonMove|Proxy                     first(string $sortedField = 'id')
 * @method static PokemonMove|Proxy                     last(string $sortedField = 'id')
 * @method static PokemonMove|Proxy                     random(array $attributes = [])
 * @method static PokemonMove|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PokemonMoveRepository|RepositoryProxy repository()
 * @method static PokemonMove[]|Proxy[]                 all()
 * @method static PokemonMove[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PokemonMove[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PokemonMove[]|Proxy[]                 findBy(array $attributes)
 * @method static PokemonMove[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PokemonMove[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PokemonMoveFactory extends ModelFactory
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
			'move' => MoveFactory::randomOrCreate(),
			'level' => self::faker()->randomNumber(1, 99),
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(PokemonMove $pokemonMove): void {})
		;
	}

	protected static function getClass(): string
	{
		return PokemonMove::class;
	}
}
