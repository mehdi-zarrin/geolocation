<?php

namespace App\Repository;

use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Request\AddressRequestDto;
use App\DataTransferObject\Response\CoordinatesResponseDto;
use App\DataTransferObject\Response\EmptyResponseDto;
use App\Entity\ResolvedAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResolvedAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolvedAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolvedAddress[]    findAll()
 * @method ResolvedAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolvedAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolvedAddress::class);
    }

    /**
     * @param AddressRequestDto $address
     * @return ResolvedAddress|null
     */
    public function getByAddress(ServiceRequestInterface $address): ?ResolvedAddress
    {
        return $this->findOneBy([
            'countryCode' => $address->getCountryCode(),
            'city' => $address->getCity(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode()
        ]);
    }

    /**
     * @param AddressRequestDto $address
     * @param CoordinatesResponseDto|null $coordinates
     */
    public function saveResolvedAddress(ServiceRequestInterface $address, ?ServiceResponseInterface $coordinates): void
    {
        $resolvedAddress = new ResolvedAddress();
        $resolvedAddress
            ->setCountryCode($address->getCountryCode())
            ->setCity($address->getCity())
            ->setStreet($address->getStreet())
            ->setPostcode($address->getPostcode());

        if ($coordinates !== null) {
            $resolvedAddress
                ->setLat((string) $coordinates->getLat())
                ->setLng((string) $coordinates->getLng());
        }

        $this->getEntityManager()->persist($resolvedAddress);
        $this->getEntityManager()->flush();
    }
}
