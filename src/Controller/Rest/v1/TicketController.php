<?php

namespace App\Controller\Rest\v1;

use App\Message\CreateTicketMessage;
use App\Repository\TicketRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class TicketController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Operation(
     *     tags={"Ticket"},
     *     summary="Save ticket",
     *     @SWG\RequestBody(
     *         required=true,
     *         @SWG\MediaType(
     *             mediaType="application/json",
     *             @SWG\Schema(
     *                 required={"departureAirportId", "departureTime", "arrivalAirportId", "arrivalTime"},
     *                 @SWG\Property(property="departureAirportId", type="integer"),
     *                 @SWG\Property(property="departureTime", type="string", format="datetime"),
     *                 @SWG\Property(property="arrivalAirportId", type="integer"),
     *                 @SWG\Property(property="arrivalTime", type="string", format="datetime"),
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return when success",
     *         @Model(type=\App\Entity\Ticket::class),
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
     *             )
     *        )
     *    )
     * )
     *
     * @Rest\Post("/ticket")
     */
    public function create(Request $request, MessageBusInterface $bus): Response
    {
        $requestData = $request->toArray();

        $envelope = $bus->dispatch(new CreateTicketMessage($requestData));
        $handledStamp = $envelope->last(HandledStamp::class);
        $ticket = $handledStamp->getResult();

        $response = new Response();
        $response->setContent($this->serializer->serialize($ticket, 'json'));
        $response->setStatusCode(Response::HTTP_CREATED);
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
    }

    /**
     * @SWG\Tag(name="Ticket")
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer"
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return tickets",
     *     @SWG\MediaType(
     *         mediaType="application/json",
     *         @SWG\Schema(
     *            @SWG\Property(type="integer", property="current_page_number"),
     *            @SWG\Property(type="integer", property="num_items_per_page"),
     *            @SWG\Property(type="integer", property="total_count"),
     *            @SWG\Property(property="items", type="array",
     *                 @SWG\Items(ref=@Model(type=\App\Entity\Ticket::class))
     *            )
     *        )
     *     )
     * )
     *
     * @Rest\Get("/ticket")
     */
    public function index(Request $request, PaginatorInterface $paginator, TicketRepository $ticketRepository): Response
    {
        $pagination = $paginator->paginate(
            $ticketRepository->findAllQueryBuilder(),
            $request->query->getInt('page', 1),
        );

        $response = new Response();
        $response->setContent($this->serializer->serialize($pagination, 'json'));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
    }
}
