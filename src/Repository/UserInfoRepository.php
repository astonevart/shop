<?php

namespace App\Repository;

use App\Dto\UserInfoDto;
use App\Entity\UserInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInfo>
 *
 * @method UserInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInfo[]    findAll()
 * @method UserInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInfo::class);
    }

    /**
     * @throws Exception
     *
     * @param UserInfoDto[] $userInfoDTOs
     */
    public function insertOrUpdate(array $userInfoDTOs): void
    {
        $sql = "INSERT INTO user_info (identifier, user_agent, ip, created_at, updated_at) VALUES ";

        $values = [];
        foreach ($userInfoDTOs as $userInfoDto) {
            $values[] = sprintf(
                "('%s', '%s', '%s', '%s', '%s')",
                $userInfoDto->identifier,
                $userInfoDto->userAgent,
                $userInfoDto->ip,
                $createdAt = $userInfoDto->createdAt->format('Y-m-d H:i:s'),
                $createdAt
            );
        }

        $sql .= implode(", ", $values);
        $sql .= " ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at)";

        $this->_em->getConnection()->executeStatement($sql);
    }
}
