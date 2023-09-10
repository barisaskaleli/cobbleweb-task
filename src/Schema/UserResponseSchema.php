<?php

namespace App\Schema;

use App\Entity\Photo;

class UserResponseSchema extends AbstractAPIResponseSchema
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var string
     */
    private $avatar;

    /**
     * @var array
     */
    private $photos;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setPhotos(array $photos): self
    {
        if (!empty($photos)) {
            /**
             * @var Photo $photo
             */
            foreach ($photos as $photo) {
                $this->photos[] = [
                    'name' => $photo->getName(),
                    'url' => $photo->getUrl()
                ];
            }
        }

        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');

        return $this;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt->format('Y-m-d H:i:s');

        return $this;
    }

    public function getResponse(?array $response = null): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'active' => $this->active,
            'avatar' => $this->avatar,
            'photos' => $this->photos,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}