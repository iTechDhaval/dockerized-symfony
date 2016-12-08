<?php

namespace AppBundle\Handler;

use AppBundle\Exception\InvalidFormException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use AppBundle\Model\ProductInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @property EntityRepository repository
 * @property ProductInterface productEntity
 * @property EntityManager entityManager
 * @property FormFactoryInterface formFactory
 * @property FormTypeInterface formType
 */
class ProductHandler implements ProductHandlerInterface
{

    public function __construct(
        EntityManager $entityManager,
        ProductInterface $productEntity,
        FormFactoryInterface $formFactory,
        FormTypeInterface $formType
    ) {
        $this->entityManager = $entityManager;
        $this->productEntity = $productEntity;

        $this->repository = $entityManager->getRepository(get_class($productEntity));

        $this->formFactory = $formFactory;
        $this->formType = $formType;

    }

    /**
     * {@inheritdoc}
     */
    public function get($id) {
        return $this->repository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function post(array $parameters = [], array $options = [])
    {
        return $this->processForm(
            $this->productEntity,
            $parameters,
            $options,
            "POST"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function patch(array $parameters = [], array $options = []) {
        $product = $this->get($parameters['id']);

        return $this->processForm(
            $product,
            $parameters,
            $options,
            "PATCH"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function put(array $parameters = [], array $options = [])
    {
        $product = $this->get($parameters['id']);

        return $this->processForm(
            $product,
            $parameters,
            $options,
            "PUT"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function processForm(ProductInterface $product, array $parameters = [], array $options = [], $method)
    {
        $form = $this->formFactory->create(get_class($this->formType), $product, $options);

        $form->submit($parameters, 'PATCH' !== $method);

        if ($form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $product;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    public function delete($id)
    {
        $product = $this->get($id);

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

}