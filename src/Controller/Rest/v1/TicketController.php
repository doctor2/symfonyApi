<?php

namespace App\Controller\Rest\v1;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketController extends AbstractController
{
    /**
     * @Operation(
     *     tags={"Ticket"},
     *     summary="Save ticket",
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
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 type="integer",
     *                 property="code",
     *                 example=200
     *             ),
     *             @SWG\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Ticket alternate has been saved"
     *             )
     *         )
     *     ),
     * )
     *
     * @Rest\Post("/ticket")
     */
    public function create(): JsonResponse
    {
        return $this->json([]);
    }

    /**
     * @SWG\Tag(name="Ticket")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the tickets",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             type="integer",
     *             property="code",
     *         ),
     *     )
     * )
     *
     * @Rest\Get("/ticket")
     */
    public function indexm(): JsonResponse
    {
        return $this->json([]);
    }
}
