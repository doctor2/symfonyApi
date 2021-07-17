<?php

namespace App\Controller\Rest\v1;

use App\Message\Command\CreateAirportMessage;
use App\Normalizer\PaginationNormalizer;
use App\Repository\AirportRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\ArrayTransformerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class AirportController extends AbstractFOSRestController
{
    private $arrayTransformer;

    public function __construct(ArrayTransformerInterface $arrayTransformer)
    {
        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * @Operation(
     *     tags={"Airport"},
     *     summary="Save airport",
     *     @SWG\RequestBody(
     *         required=true,
     *         @SWG\MediaType(
     *             mediaType="application/json",
     *             @SWG\Schema(
     *                 required={"name", "timezone"},
     *                 @SWG\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *                 @SWG\Property(
     *                     property="timezone",
     *                     type="string",
     *                 )
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return when success",
     *         @Model(type=\App\Entity\Airport::class),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid request data",
     *         @SWG\MediaType(
     *             mediaType="application/json",
     *             @SWG\Schema(
     *                @SWG\Property(type="integer", property="code"),
     *                @SWG\Property(type="string", property="message"),
     *                @SWG\Property(type="array", property="errors", @SWG\Items(type="string")),
     *            )
     *        )
     *    )
     * )
     *
     * @phan-suppress PhanUndeclaredMethod
     *
     * @Rest\Post("/airport")
     */
    public function create(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $requestData = $request->toArray();

        $envelope = $bus->dispatch(new CreateAirportMessage($requestData));
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        $airport = $handledStamp->getResult();

        return $this->json($this->arrayTransformer->toArray($airport), Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Airport")
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer"
     *     )
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return airports",
     *     @SWG\MediaType(
     *         mediaType="application/json",
     *         @SWG\Schema(
     *             @SWG\Property(type="integer", property="current_page_number"),
     *             @SWG\Property(type="integer", property="num_items_per_page"),
     *             @SWG\Property(type="integer", property="total_count"),
     *             @SWG\Property(property="items", type="array",
     *                  @SWG\Items(ref=@Model(type=\App\Entity\Airport::class))
     *             )
     *         )
     *     )
     * )
     *
     * @Rest\Get("/airport")
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        AirportRepository $airportRepository,
        PaginationNormalizer $paginationNormalizer
    ): JsonResponse {
        $pagination = $paginator->paginate(
            $airportRepository->findAllQueryBuilder(),
            $request->query->getInt('page', 1),
        );

        return $this->json(
            $paginationNormalizer->normalize($pagination)
        );
    }
}
