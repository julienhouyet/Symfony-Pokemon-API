<?php

namespace App\Factory;

use App\Entity\PokemonStat;
use App\Repository\PokemonStatRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PokemonStat>
 *
 * @method        PokemonStat|Proxy                     create(array|callable $attributes = [])
 * @method static PokemonStat|Proxy                     createOne(array $attributes = [])
 * @method static PokemonStat|Proxy                     find(object|array|mixed $criteria)
 * @method static PokemonStat|Proxy                     findOrCreate(array $attributes)
 * @method static PokemonStat|Proxy                     first(string $sortedField = 'id')
 * @method static PokemonStat|Proxy                     last(string $sortedField = 'id')
 * @method static PokemonStat|Proxy                     random(array $attributes = [])
 * @method static PokemonStat|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PokemonStatRepository|RepositoryProxy repository()
 * @method static PokemonStat[]|Proxy[]                 all()
 * @method static PokemonStat[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PokemonStat[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PokemonStat[]|Proxy[]                 findBy(array $attributes)
 * @method static PokemonStat[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PokemonStat[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PokemonStatFactory extends ModelFactory
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
			'baseStat' => self::faker()->numberBetween(0, 100),
			'effort' => self::faker()->boolean ? 1 : 0,
			'stat' => StatFactory::randomOrCreate(),
		];
	}

	/**
	 * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
	 */
	protected function initialize(): self
	{
		return $this
			// ->afterInstantiate(function(PokemonStat $pokemonStat): void {})
		;
	}

	protected static function getClass(): string
	{
		return PokemonStat::class;
	}
}
