<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Exception\InvalidFormException;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;

/**
 * @Rest\Route("/product")
 */
class ProductController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\Get
     * @Rest\View(serializerGroups={"product","category"})
     *
     * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing products.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="5", description="How many products to return.")
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function listAction()
    {
        return $this->get('appbundle.product.handler')->getAll();
    }

    /**
     * @Rest\Get("/{id}")
     * @Rest\View(serializerGroups={"product","category"})
     */
    public function getAction($id)
    {
        return $this->get('appbundle.product.handler')->get($id);
    }

    /**
     * @Rest\Post
     *
     * TODO: handle category for new product using handler
     */
    public function postAction(Request $request)
    {
        try {
            return $this->get('appbundle.product.handler')->post(
                $request->request->get(
                    $this->get('appbundle.product.form_type')->getName()
                )
            );
        } catch (InvalidFormException $e) {
            return new View($e->getForm(), Response::HTTP_BAD_REQUEST);
        }

        $data = new Product();
        $name = $request->get('name');
        $category = $request->get('category');
        $sku = $request->get('sku');
        $price = $request->get('price');
        $quantity = $request->get('quantity');

        if(empty($name) || empty($category) || empty($sku) || empty($price) || empty($quantity))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        $em = $this->getDoctrine()->getManager();

        $categoryObject = $this->getDoctrine()
                               ->getRepository('AppBundle:Category')
                               ->findOneBy(array('name' => $category));
        if ($categoryObject == null) {
            $categoryObject = new Category();
            $categoryObject->setName($category);
            $em->persist($categoryObject);
            $em->flush();
        }

        $data->setName($name);
        $data->setCategory($categoryObject);
        $data->setSku($sku);
        $data->setPrice($price);
        $data->setQuantity($quantity);
        $em->persist($data);
        $em->flush();
        return new View("Product Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/{id}")
     *
     * TODO: handle category for update product using handler
     */
    public function updateAction($id, Request $request)
    {
        try {
            return $this->get('appbundle.product.handler')->put(
                $request->request->get(
                    $this->get('appbundle.product.form_type')->getName()
                )
            );
        } catch (InvalidFormException $e) {
            return new View($e->getForm(), Response::HTTP_BAD_REQUEST);
        }

        $name = $request->get('name');
        $category = $request->get('category');
        $sku = $request->get('sku');
        $price = $request->get('price');
        $quantity = $request->get('quantity');

        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()
                        ->getRepository('AppBundle:Product')
                        ->find($id);
        if (empty($product)) {
            return new View("Product not found", Response::HTTP_NOT_FOUND);
        }

        $updateData = false;
        if (!empty($name)) {
            $product->setName($name);
            $updateData = true;
        }

        if (!empty($category)) {
            $categoryObject = $this->getDoctrine()
                                   ->getRepository('AppBundle:Category')
                                   ->findOneBy(array('name' => $category));
            if ($categoryObject == null) {
                $categoryObject = new Category();
                $categoryObject->setName($category);
                $em->persist($categoryObject);
                $em->flush();

                $product->setCategory($categoryObject);
                $updateData = true;
            }

            if ($product->getCategory()->getId() !== $categoryObject->getId()) {
                $product->setCategory($categoryObject);
                $updateData = true;
            }
        }

        if (!empty($sku)) {
            $product->setSku($sku);
            $updateData = true;
        }
        if (!empty($price)) {
            $product->setPrice($price);
            $updateData = true;
        }
        if (!empty($quantity)) {
            $product->setQuantity($quantity);
            $updateData = true;
        }

        if ($updateData) {
            $em->persist($product);
            $em->flush();
            return new View("Product updated successfully", Response::HTTP_OK);
        }

        return new View("Please verify post data. No data found to update for Product", Response::HTTP_NOT_ACCEPTABLE);

    }

    /**
     * @Rest\Delete("/{id}")
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        return $this->get('appbundle.product.handler')->delete($id);

        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (empty($product)) {
            return new View("Product not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $em->remove($product);
            $em->flush();
        }
        return new View("Product deleted successfully", Response::HTTP_OK);
    }

}
