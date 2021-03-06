<?php

namespace App\Controller\Rest\v1;

use App\Message\Ticket\CreateTicketMessage;
use App\Message\Ticket\TicketSearchMessage;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\ArrayTransformerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class TicketController extends AbstractController
{
    private $arrayTransformer;

    public function __construct(ArrayTransformerInterface $arrayTransformer)
    {
        $this->arrayTransformer = $arrayTransformer;
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
     *            )
     *        )
     *    )
     * )
     *
     * @phan-suppress PhanUndeclaredMethod
     *
     * @Rest\Post("/ticket")
     */
    public function create(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $requestData = $request->toArray();

        $envelope = $messageBus->dispatch(new CreateTicketMessage($requestData));
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        $ticket = $handledStamp->getResult();

        return $this->json($this->arrayTransformer->toArray($ticket), Response::HTTP_CREATED);
    }

    /**
     * @SWG\Tag(name="Ticket")
     *
     * @SWG\Parameter(
     *     name="departureAirportId",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer"
     *     )
     * ),
     * @SWG\Parameter(
     *     name="arrivalAirportId",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer"
     *     )
     * ),
     * @SWG\Parameter(
     *     name="departureTime",
     *     in="query",
     *     @SWG\Schema(
     *         type="string", format="date"
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return tickets",
     *     @SWG\MediaType(
     *         mediaType="application/json",
     *         @SWG\Schema(
     *             @SWG\Property(property="tickets", type="array",
     *                  @SWG\Items(ref=@Model(type=\App\Entity\Ticket::class))
     *             ),
     *             @SWG\Property(property="oneStopTickets", type="array",
     *                  @SWG\Items(ref=@Model(type=\App\Entity\Ticket::class))
     *             )
     *         )
     *     )
     * )
     *
     * @phan-suppress PhanUndeclaredMethod
     *
     * @Rest\Get("/ticket")
     */
    public function index(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $envelope = $messageBus->dispatch(new TicketSearchMessage($request->query->all()));
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);
        $tickets = $handledStamp->getResult();

        return $this->json($this->arrayTransformer->toArray($tickets));
    }
}
