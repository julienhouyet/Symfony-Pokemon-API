<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource]
class Type
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $name = null;

	#[ORM\ManyToMany(targetEntity: Pokemon::class, mappedBy: 'types')]
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
