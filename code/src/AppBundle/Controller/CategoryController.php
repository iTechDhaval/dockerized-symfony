<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Category;

/**
 * @Rest\Route("/category")
 */
class CategoryController extends FOSRestController
{
    /**
     * @Rest\Get
     * @Rest\View(serializerGroups={"product","category"})
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        if ($categories === null) {
            return new View("There are no categories exist", Response::HTTP_NOT_FOUND);
        }
        return new View($categories, Response::HTTP_OK);
    }

}
