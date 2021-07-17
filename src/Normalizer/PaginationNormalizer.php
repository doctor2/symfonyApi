<?php

namespace App\Normalizer;

use JMS\Serializer\ArrayTransformerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationNormalizer
{
    private $arrayTransformer;

    public function __construct(ArrayTransformerInterface $arrayTransformer)
    {
        $this->arrayTransformer = $arrayTransformer;
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
            'items' => $this->arrayTransformer->toArray($pagination->getItems()),
        ];
    }
}
