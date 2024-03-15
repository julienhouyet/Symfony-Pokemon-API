<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PokemonRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ApiResource(
	shortName: 'Pokemon',
	description: 'The Pokemon List',
	operations: [
		new Get(
			uriTemplate: '/pokemons/{id}',
			security: 'is_granted("PUBLIC_ACCESS")'
		),
		new GetCollection(
			uriTemplate: '/pokemons',
			security: 'is_granted("PUBLIC_ACCESS")'
		),
		new Post(uriTemplate: '/pokemons'),
		new Put(uriTemplate: '/pokemons/{id}'),
		new Patch(uriTemplate: '/pokemons/{id}'),
		new Delete(uriTemplate: '/pokemons/{id}'),
	],
	normalizationContext: ['groups' => ['pokemon:read']],
	denormalizationContext: ['groups' => ['pokemon:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiFilter(SearchFilter::class, properties: [
	'name' => 'partial'
])]

class Pokemon
{
	/**
	 * The ID of the Pokemon
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * The Pokedex Number of the Pokemon
	 */
	#[ORM\Column(unique: true)]
	#[Groups(['pokemon:read', 'pokemon:write'])]
	#[Assert\NotBlank]
	private ?int $pokedexNumber = null;

	/**
	 * The Name of the Pokemon
	 */
	#[ORM\Column(length: 255)]
	#[Groups(['pokemon:read', 'pokemon:write'])]
	#[Assert\NotBlank]
	private ?string $name = null;

	/**
	 * The Height of the Pokemon
	 */
	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemon:write'])]
	#[Assert\NotBlank]
	private ?float $height = null;

	/**
	 * The Weight of the Pokemon
	 */
	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemon:write'])]
	#[Assert\NotBlank]
	private ?float $weight = null;

	/**
	 * The Base Experience of the Pokemon
	 */
	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemon:write'])]
	#[Assert\NotBlank]
	private ?int $baseExperience = null;

	#[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonType::class)]
	#[Groups(['pokemon:read'])]
	private Collection $pokemonTypes;

	#[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonStat::class)]
	#[Groups(['pokemon:read'])]
	private Collection $pokemonStats;

	#[ORM\OneToMany(mappedBy: 'Pokemon', targetEntity: PokemonMove::class)]
	#[Groups(['pokemon:read'])]
	private Collection $pokemonMoves;

	public function __construct()
	{
		$this->pokemonTypes = new ArrayCollection();
		$this->pokemonStats = new ArrayCollection();
		$this->pokemonMoves = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getHeight(): ?float
	{
		return $this->height;
	}

	public function setHeight(float $height): static
	{
		$this->height = $height;

		return $this;
	}

	public function getWeight(): ?float
	{
		return $this->weight;
	}

	public function setWeight(float $weight): static
	{
		$this->weight = $weight;

		return $this;
	}

	public function getBaseExperience(): ?int
	{
		return $this->baseExperience;
	}

	public function setBaseExperience(int $baseExperience): static
	{
		$this->baseExperience = $baseExperience;

		return $this;
	}

	/**
	 * @return Collection<int, PokemonType>
	 */
	public function getPokemonTypes(): Collection
	{
		return $this->pokemonTypes;
	}

	public function addPokemonType(PokemonType $pokemonType): static
	{
		if (!$this->pokemonTypes->contains($pokemonType)) {
			$this->pokemonTypes->add($pokemonType);
			$pokemonType->setPokemon($this);
		}

		return $this;
	}

	public function removePokemonType(PokemonType $pokemonType): static
	{
		if ($this->pokemonTypes->removeElement($pokemonType)) {
			// set the owning side to null (unless already changed)
			if ($pokemonType->getPokemon() === $this) {
				$pokemonType->setPokemon(null);
			}
		}

		return $this;
	}

	public function getPokedexNumber(): ?int
	{
		return $this->pokedexNumber;
	}

	public function setPokedexNumber(int $pokedexNumber): static
	{
		$this->pokedexNumber = $pokedexNumber;

		return $this;
	}

	/**
	 * @return Collection<int, PokemonStat>
	 */
	public function getPokemonStats(): Collection
	{
		return $this->pokemonStats;
	}

	public function addPokemonStat(PokemonStat $pokemonStat): static
	{
		if (!$this->pokemonStats->contains($pokemonStat)) {
			$this->pokemonStats->add($pokemonStat);
			$pokemonStat->setPokemon($this);
		}

		return $this;
	}

	public function removePokemonStat(PokemonStat $pokemonStat): static
	{
		if ($this->pokemonStats->removeElement($pokemonStat)) {
			// set the owning side to null (unless already changed)
			if ($pokemonStat->getPokemon() === $this) {
				$pokemonStat->setPokemon(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, PokemonMove>
	 */
	public function getPokemonMoves(): Collection
	{
		return $this->pokemonMoves;
	}

	public function addPokemonMove(PokemonMove $pokemonMove): static
	{
		if (!$this->pokemonMoves->contains($pokemonMove)) {
			$this->pokemonMoves->add($pokemonMove);
			$pokemonMove->setPokemon($this);
		}

		return $this;
	}

	public function removePokemonMove(PokemonMove $pokemonMove): static
	{
		if ($this->pokemonMoves->removeElement($pokemonMove)) {
			// set the owning side to null (unless already changed)
			if ($pokemonMove->getPokemon() === $this) {
				$pokemonMove->setPokemon(null);
			}
		}

		return $this;
	}
}
