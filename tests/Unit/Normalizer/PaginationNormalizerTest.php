<?php

namespace Tests\Unit\Normalizer;

use App\Normalizer\PaginationNormalizer;
use JMS\Serializer\ArrayTransformerInterface;
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

        $normalizer = new PaginationNormalizer($this->createArrayTransformer());

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

    private function createArrayTransformer(): ArrayTransformerInterface
    {
        $arrayTransformer = $this->createMock(ArrayTransformerInterface::class);
        $arrayTransformer->method('toArray')
            ->willReturn(['test']);

        return $arrayTransformer;
    }
}
