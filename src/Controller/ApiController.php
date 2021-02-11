<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\SpecialiteRepository;
use App\Repository\TagRepository;

/**
 * @Route("/api.specialites.vlabs", name="api")
 */
class ApiController extends AbstractController
{
    private $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="apiRoot")
     */
    public function index(): Response
    {
        return $this->json(['healthy' => true]);
    }

    /**
     * @Route("/all_specialites", name="apiAllSpecialites")
     */
    public function getAllSpecialites(SpecialiteRepository $specialiteRepository): Response
    {
        $specialites = $specialiteRepository->findAll();

        $jsonContent = $this->serializer->serialize($specialites, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/specialites_by_libelle/{libelle}", name="apiSomeSpecialites")
     */
    public function getSpecialitesByLibelle(SpecialiteRepository $specialiteRepository, String $libelle): Response
    {
        $specialites = $specialiteRepository->findByLibelle($libelle);

        $jsonContent = $this->serializer->serialize($specialites, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/all_tags", name="apiAllTags")
     */
    public function getAllTags(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        $jsonContent = $this->serializer->serialize($tags, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/specialites_by_tags/{tags}", name="apiSpecialitesByTags")
     */
    public function getSpecialitesByTags(TagRepository $tagRepository, String $tags): Response
    {
        if(str_contains($tags, '-')) {
            $tagsArray = explode('-', $tags);

            $specialitesArray = [];
            foreach($tagsArray as $tagId) {
                $tag = $tagRepository->find($tagId);
                $specialitesArray = array_merge($specialitesArray, $tag->getSpecialites()->toArray());
            }

            usort($specialitesArray, function($a, $b) { return strcmp($a->getLibelle(), $b->getLibelle()); });

            $res = $specialitesArray;
        }
        else {
            $tag = $tagRepository->find($tags);
            $res = $tag->getSpecialites();
        }

        $jsonContent = $this->serializer->serialize($res, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/specialites_by_tags_plus_libelle/{tags}/{libelle}", name="apiSpecialitesByTagsPlusLibelle")
     */
    public function getSpecialitesByTagsPlusLibelle(TagRepository $tagRepository, String $tags, String $libelle): Response
    {
        if(str_contains($tags, '-')) {
            $tagsArray = explode('-', $tags);

            $specialitesArray = [];
            foreach($tagsArray as $tagId) {
                $tag = $tagRepository->find($tagId);
                $specialitesArray = array_merge($specialitesArray, $tag->getSpecialites()->toArray());
            }

            usort($specialitesArray, function($a, $b) { return strcmp($a->getLibelle(), $b->getLibelle()); });

            $res = $specialitesArray;
        }
        else {
            $tag = $tagRepository->find($tags);
            $res = $tag->getSpecialites();
        }

        $res = array_filter($res->toArray(), function($ares) use ($libelle) {
            return (stripos($ares->getLibelle(), $libelle) !== FALSE);
        });

        $jsonContent = $this->serializer->serialize(array_values($res), 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
