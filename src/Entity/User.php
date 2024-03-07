<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
	shortName: 'User',
	operations: [
		new Get(),
		new GetCollection(),
		new Post(),
		new Put(),
		new Patch(),
		new Delete()
	],
	normalizationContext: ['groups' => ['user:read']],
	denormalizationContext: ['groups' => ['user:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email.')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	/**
	 * The ID of the user
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 180, unique: true)]
	#[Groups(['user:read', 'user:write'])]
	#[Assert\NotBlank]
	#[Assert\Email]
	private ?string $email = null;

	/**
	 * @var list<string> The user roles
	 */
	#[ORM\Column]
	#[Groups(['user:read'])]
	private array $roles = [];

	/* Scopes given during API authentication */
	private ?array $accessTokenScopes = null;

	/**
	 * @var string The hashed password
	 */
	#[ORM\Column]
	#[Groups(['user:write'])]
	private ?string $password = null;

	#[ORM\Column(length: 255, unique: true)]
	#[Groups(['user:read', 'user:write'])]
	#[Assert\NotBlank]
	private ?string $username = null;

	#[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: ApiToken::class)]
	private Collection $apiTokens;

	public function __construct()
	{
		$this->apiTokens = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string) $this->email;
	}

	/**
	 * @see UserInterface
	 *
	 * @return list<string>
	 */
	public function getRoles(): array
	{
		if (null === $this->accessTokenScopes) {
			// logged in via the full user mechanism
			$roles = $this->roles;
			$roles[] = 'ROLE_FULL_USER';
		} else {
			$roles = $this->accessTokenScopes;
		}

		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';
		return array_unique($roles);
	}

	/**
	 * @param list<string> $roles
	 */
	public function setRoles(array $roles): static
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials(): void
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): static
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @return Collection<int, ApiToken>
	 */
	public function getApiTokens(): Collection
	{
		return $this->apiTokens;
	}

	public function addApiToken(ApiToken $apiToken): static
	{
		if (!$this->apiTokens->contains($apiToken)) {
			$this->apiTokens->add($apiToken);
			$apiToken->setOwnedBy($this);
		}

		return $this;
	}

	public function removeApiToken(ApiToken $apiToken): static
	{
		if ($this->apiTokens->removeElement($apiToken)) {
			// set the owning side to null (unless already changed)
			if ($apiToken->getOwnedBy() === $this) {
				$apiToken->setOwnedBy(null);
			}
		}

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getValidTokenStrings(): array
	{
		return $this->getApiTokens()
			->filter(fn (ApiToken $token) => $token->isValid())
			->map(fn (ApiToken $token) => $token->getToken())
			->toArray();
	}

	public function markAsTokenAuthenticated(array $scopes)
	{
		$this->accessTokenScopes = $scopes;
	}
}
