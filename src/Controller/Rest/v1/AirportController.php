<?php

namespace App\Controller\Rest\v1;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;

class AirportController extends AbstractFOSRestController
{
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
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 type="integer",
     *                 property="code",
     *                 example=200
     *             ),
     *             @SWG\Property(
     *                 type="string",
     *                 property="message",
     *                 example="Airport alternate has been saved"
     *             )
     *         )
     *     ),
     * )
     *
     * @Rest\Post("/airport")
     */
    public function create(): JsonResponse
    {
        return $this->json([]);
    }


     /**
     * @SWG\Tag(name="Airport")
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Return airports",
     *     @SWG\Schema(
     *         @SWG\Property(
     *             type="integer",
     *             property="code",
     *         ),
     *     )
     * )
     *
     * @Rest\Get("/airport")
     */
    public function index(): JsonResponse
    {
        return $this->json([]);
    }
}
