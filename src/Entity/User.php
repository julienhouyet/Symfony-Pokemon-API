<?php

namespace App\Entity;

use App\Entity\ApiToken;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
	shortName: 'User',
	operations: [
		new Get(
			security: 'is_granted("ROLE_ADMIN") or (object and object == user)'
		),
		new GetCollection(),
		new Post(
			security: 'is_granted("PUBLIC_ACCESS")'
		),
		new Put(
			security: 'is_granted("ROLE_ADMIN") or (object and object == user)'
		),
		new Patch(
			security: 'is_granted("ROLE_ADMIN") or (object and object == user)'
		),
		new Delete()
	],
	normalizationContext: ['groups' => ['user:read']],
	denormalizationContext: ['groups' => ['user:write']],
	security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiFilter(SearchFilter::class, properties: [
	'email' => 'partial',
	'username' => 'partial'
])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email.')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username.')]
#[ORM\HasLifecycleCallbacks]
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
	private ?string $password = null;

	#[Groups(['user:write'])]
	#[SerializedName('password')]
	private ?string $plainPassword = null;

	#[ORM\Column(length: 255, unique: true)]
	#[Groups(['user:read', 'user:write'])]
	#[Assert\NotBlank]
	private ?string $username = null;

	#[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: ApiToken::class, cascade: ['persist', 'remove'])]
	private Collection $apiTokens;

	#[ORM\PrePersist]
	public function generateApiTokenOnCreate(): void
	{
		$apiToken = new ApiToken();
		$apiToken->setOwnedBy($this);

		$expiresAt = new \DateTimeImmutable();
		$newExpireAt = $expiresAt->modify('+1 month');

		$apiToken->setExpiresAt($newExpireAt);

		$this->addApiToken($apiToken);
	}

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
		$roles = $this->roles;

		// Scopes at configure later
		// if (null === $this->accessTokenScopes) {
		// 	// logged in via the full user mechanism
		// 	$roles = $this->roles;
		// 	$roles[] = 'ROLE_FULL_USER';
		// } else {
		// 	$roles = $this->accessTokenScopes;
		// }

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

	public function setPassword(string $password): self
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
		$this->plainPassword = null;
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

	public function setPlainPassword(string $plainPassword): User
	{
		$this->plainPassword = $plainPassword;
		return $this;
	}
	public function getPlainPassword(): ?string
	{
		return $this->plainPassword;
	}
}
