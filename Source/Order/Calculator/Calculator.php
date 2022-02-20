<?php declare(strict_types=1);

namespace Assignment\Order\Calculator;

use Assignment\Order\Controller\Form\ProductQuantityReference;
use Assignment\Order\Resource\OrderResource;
use Assignment\Order\Resource\OrderResourceFactory;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\ProductFinderInterface;

class Calculator implements CalculatorInterface
{

    /**
     * @var ProductFinderInterface
     */
    private $productFinder;

    /**
     * @var CalculatedOutcomeFactory
     */
    private $calculatedOutcomeFactory;

    /**
     * @var OrderResourceFactory
     */
    private $invoiceResourceFactory;

    /**
     * @var ProductQuantityReference[]
     */
    private $products;

    /**
     * @var string
     */
    private $country;

    /**
     * @var float
     */
    private $grandTotalPrice = 0;

    /**
     * @var float
     */
    private $grandTotalTaxedPrice = 0;

    /**
     * @param ProductFinderInterface $productFinder
     * @param CalculatedOutcomeFactory $calculatedOutcomeFactory
     * @param OrderResourceFactory $invoiceResourceFactory
     * @param ProductQuantityReference[] $products
     * @param string $country
     */
    public function __construct(
        ProductFinderInterface   $productFinder,
        CalculatedOutcomeFactory $calculatedOutcomeFactory,
        OrderResourceFactory     $invoiceResourceFactory,
        array                    $products,
        string                   $country
    )
    {
        $this->productFinder = $productFinder;
        $this->calculatedOutcomeFactory = $calculatedOutcomeFactory;
        $this->invoiceResourceFactory = $invoiceResourceFactory;
        $this->products = $products;
        $this->country = $country;
    }


    /**
     * @inheritDoc
     */
    public function calculate(): OrderResource
    {
        $preCalculatedTotal = $this->getPreCalculatedTotal();
        $calculatedOutcomes = $this->getCalculatedOutcomes();
        $postCalculatedTotal = $this->getPostCalculatedTotal();

        $totalCost = $preCalculatedTotal + $this->grandTotalPrice + $postCalculatedTotal;

        return $this
            ->invoiceResourceFactory
            ->createInvoiceResource(
                $totalCost,
                $this->grandTotalTaxedPrice,
                $calculatedOutcomes
            );
    }

    /**
     * @return int
     */
    private function getPreCalculatedTotal(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    private function getPostCalculatedTotal(): int
    {
        return 0;
    }

    /**
     * @return array
     * @throws CannotFindProductException
     */
    private function getCalculatedOutcomes(): array
    {
        $outcomes = [];

        foreach ($this->products as $productQuantityReference) {
            $productId = $productQuantityReference->getProductId();
            $quantity = $productQuantityReference->getQuantity();
            $product = $this->productFinder->findProduct($productId, $this->country);

            $unitTotalPrice = $product->getPrice() * $quantity;
            $taxedPrice = round(($unitTotalPrice / 100) * $product->getTax(), 2);
            $totalPrice = $unitTotalPrice + $taxedPrice;

            $this->grandTotalPrice += $totalPrice;
            $this->grandTotalTaxedPrice += $taxedPrice;

            $outcomes[] = $this->calculatedOutcomeFactory->createCalculatedOutcome($productId, $quantity, $unitTotalPrice, $taxedPrice, $totalPrice);
        }

        return $outcomes;
    }
}
