<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PokemonRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ApiResource(
	description: 'The Pokemon List'
)]
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
	 * The Name of the Pokemon
	 */
	#[ORM\Column(length: 255)]
	private ?string $name = null;

	/**
	 * The Height of the Pokemon
	 */
	#[ORM\Column]
	private ?float $height = null;

	/**
	 * The Weight of the Pokemon
	 */
	#[ORM\Column]
	private ?float $weight = null;

	/**
	 * The Base Experience of the Pokemon
	 */
	#[ORM\Column]
	private ?int $baseExperience = null;

	/**
	 * The Types of the Pokemon
	 */
	#[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'pokemons')]
	#[Groups(['pokemon:read'])]
	private Collection $types;

	public function __construct()
	{
		$this->types = new ArrayCollection();
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
	 * @return Collection<int, Type>
	 */
	public function getTypes(): Collection
	{
		return $this->types;
	}

	public function addType(Type $type): static
	{
		if (!$this->types->contains($type)) {
			$this->types->add($type);
		}

		return $this;
	}

	public function removeType(Type $type): static
	{
		$this->types->removeElement($type);

		return $this;
	}
}
