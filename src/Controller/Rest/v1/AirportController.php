<?php

namespace App\Controller\Rest\v1;

use App\Message\CreateAirportMessage;
use App\Normalizer\PaginationNormalizer;
use App\Repository\AirportRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class AirportController extends AbstractFOSRestController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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
     * @Rest\Post("/airport")
     */
    public function create(Request $request, MessageBusInterface $bus): Response
    {
        $requestData = $request->toArray();

        $envelope = $bus->dispatch(new CreateAirportMessage($requestData));
        $handledStamp = $envelope->last(HandledStamp::class);
        $airport = $handledStamp->getResult();

        $response = new Response();
        $response->setContent($this->serializer->serialize($airport, 'json'));
        $response->setStatusCode(Response::HTTP_CREATED);
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
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
    ): Response {
        $pagination = $paginator->paginate(
            $airportRepository->findAllQueryBuilder(),
            $request->query->getInt('page', 1),
        );

        return $this->json(
            $paginationNormalizer->normalize($pagination)
        );
    }
}
