<?php

namespace Tests\Unit\Normalizer;

use App\Normalizer\PaginationNormalizer;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Tests\Unit\TestCase;

class PaginationNormalizerTest extends TestCase
{
    public function testNormalize(): void
    {
        $expectedData = [
            'current_page_number' => 2,
            'num_items_per_page' => 3,
            'total_count' => 4,
            'items' => ['test'],
        ];

        $normalizer = new PaginationNormalizer($this->createSerializer());

        $this->assertEquals($expectedData, $normalizer->normalize($this->createPagination()));
    }

    private function createPagination(): PaginationInterface
    {
        $pagination = $this->createMock(PaginationInterface::class);
        $pagination->method('getCurrentPageNumber')
            ->willReturn(2);
        $pagination->method('getItemNumberPerPage')
            ->willReturn(3);
        $pagination->method('getTotalItemCount')
            ->willReturn(4);
        $pagination->method('getItems')
            ->willReturn([]);

        return $pagination;
    }

    private function createSerializer(): SerializerInterface
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('serialize')
            ->willReturn(json_encode(['test']));

        return $serializer;
    }
}
