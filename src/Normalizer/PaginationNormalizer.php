<?php

namespace App\Normalizer;

use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationNormalizer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return mixed[]
     */
    public function normalize(PaginationInterface $pagination): array
    {
        return [
            'current_page_number' => $pagination->getCurrentPageNumber(),
            'num_items_per_page' => $pagination->getItemNumberPerPage(),
            'total_count' => $pagination->getTotalItemCount(),
            'items' => json_decode($this->serializer->serialize($pagination->getItems(), 'json'), true),
        ];
    }
}
