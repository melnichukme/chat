<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    private function loadUser(ObjectManager $manager): void
    {
        foreach (
            $this->getUserData(
            ) as [$userName, $roles, $password]
        ) {
            $entity = new User();
            $entity->setUsername($userName);
            $entity->setRoles($roles);
            $entity->setPassword($this->hasher->hashPassword($entity, $password));

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @return array[]
     */
    private function getUserData(): array
    {
        return [
            // $data = [$userName, $roles, $password];
            [
                'user1',
                [User::ROLE_ADMIN],
                '123456'
            ],
            [
                'user2',
                [User::ROLE_ADMIN],
                '123456'
            ]
        ];
    }
}
