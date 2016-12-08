<?php

namespace AppBundle\Handler;

use AppBundle\Model\ProductInterface;

interface ProductHandlerInterface
{

    /**
     * Gets a product by id
     *
     * @api
     * @param integer $id
     *
     * @return ProductInterface
     */
    public function get($id);

    /**
     * Gets all products
     *
     * @api
     *
     * @return ProductInterface[]
     */
    public function getAll();

    /**
     *
     * Create a new product
     *
     * @api
     * @param array $parameters
     * @param array $options
     *
     * @return ProductInterface
     */
    public function post(array $parameters = [], array $options = []);

    /**
     *
     * Update partial product data
     *
     * @api
     * @param array $parameters
     * @param array $options
     *
     * @return ProductInterface
     */
    public function patch(array $parameters = [], array $options = []);

    /**
     *
     * Updated product
     *
     * @api
     * @param array $parameters
     * @param array $options
     *
     * @return ProductInterface
     */
    public function put(array $parameters = [], array $options = []);

    /**
     *
     * Process the form
     *
     * @api
     * @param ProductInterface $product
     * @param array $parameters
     * @param array $options
     * @param $method
     *
     * @return ProductInterface
     */
    public function processForm(ProductInterface $product, array $parameters = [], array $options = [], $method);

    /**
     * Returns the result of removal
     *
     * @api
     * @param integer $id
     *
     * @return string
     */
    public function delete($id);
}