<?php

namespace App\Services;

use App\Entities\User;
use Somecode\Framework\Authentication\AuthUserInterface;
use Somecode\Framework\Authentication\UserServiceInterface;
use Somecode\Framework\Dbal\EntityService;

class UserService implements UserServiceInterface
{
    public function __construct(
        private EntityService $service
    ) {
    }

    public function save(User $user): User
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'created_at' => $user->getCreateAt()->format('Y-m-d H:i:s'),
            ])->executeQuery();
        $id = $this->service->save($user);
        $user->setId($id);

        return $user;
    }

    public function findByEmail(string $email): ?AuthUserInterface
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $user = $result->fetchAssociative();

        if (! $user) {
            return null;
        }

        return User::create(
            $user['email'],
            $user['password'],
            $user['name'],
            new \DateTimeImmutable($user['created_at']),
            $user['id'],
        );
    }

    public function getPassword()
    {

    }
}
