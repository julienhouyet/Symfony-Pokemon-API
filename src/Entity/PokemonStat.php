<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PokemonStatRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonStatRepository::class)]
class PokemonStat
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemonStat:read'])]
	private ?int $baseStat = null;

	#[ORM\Column]
	#[Groups(['pokemon:read', 'pokemonStat:read'])]
	private ?int $effort = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonStats')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['pokemon:read', 'pokemonStat:read'])]
	private ?Pokemon $pokemon = null;

	#[ORM\ManyToOne(inversedBy: 'pokemonStats')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['pokemon:read', 'pokemonStat:read'])]
	private ?Stat $stat = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getBaseStat(): ?int
	{
		return $this->baseStat;
	}

	public function setBaseStat(int $baseStat): static
	{
		$this->baseStat = $baseStat;

		return $this;
	}

	public function getEffort(): ?int
	{
		return $this->effort;
	}

	public function setEffort(int $effort): static
	{
		$this->effort = $effort;

		return $this;
	}

	public function getPokemon(): ?Pokemon
	{
		return $this->pokemon;
	}

	public function setPokemon(?Pokemon $pokemon): static
	{
		$this->pokemon = $pokemon;

		return $this;
	}

	public function getStat(): ?Stat
	{
		return $this->stat;
	}

	public function setStat(?Stat $stat): static
	{
		$this->stat = $stat;

		return $this;
	}
}
