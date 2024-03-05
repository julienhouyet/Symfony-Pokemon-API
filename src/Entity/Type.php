<?php

namespace App\Entity;

use App\Entity\Pokemon;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{

	/**
	 * The ID of the type
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * The Name of the type
	 */
	#[ORM\Column(length: 255)]
	private ?string $name = null;

	/**
	 * The Pokemons with this type
	 */
	#[ORM\ManyToMany(targetEntity: Pokemon::class, mappedBy: 'types')]
	#[Groups(['pokemon:read'])]
	private Collection $pokemons;

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

	/**
	 * @return Collection<int, Post>
	 */
	public function getPokemons(): Collection
	{
		return $this->pokemons;
	}

	public function addPokemon(Pokemon $pokemon): static
	{
		if (!$this->pokemons->contains($pokemon)) {
			$this->pokemons->add($pokemon);
			$pokemon->addType($this);
		}

		return $this;
	}

	public function removePokemon(Pokemon $pokemon): static
	{
		if ($this->pokemons->removeElement($pokemon)) {
			$pokemon->removeType($this);
		}

		return $this;
	}
}
