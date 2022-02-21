<?php declare(strict_types=1);

namespace Assignment\Calculator;

use Assignment\Calculator\Exception\CannotCalculateException;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\ProductFinderInterface;
use Assignment\Product\ProductQuantityReference;
use Exception;

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
     * @var ProductQuantityReference[]
     */
    private $products;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param ProductFinderInterface $productFinder
     * @param CalculatedOutcomeFactory $calculatedOutcomeFactory
     * @param ProductQuantityReference[] $products
     * @param string $countryCode
     */
    public function __construct(
        ProductFinderInterface   $productFinder,
        CalculatedOutcomeFactory $calculatedOutcomeFactory,
        array                    $products,
        string                   $countryCode
    )
    {
        $this->productFinder = $productFinder;
        $this->calculatedOutcomeFactory = $calculatedOutcomeFactory;
        $this->products = $products;
        $this->countryCode = $countryCode;
    }

    /**
     * @inheritDoc
     */
    public function calculate(): array
    {
        try {
            return $this->getCalculatedOutcomes();
        } catch (Exception $exception) {
            throw CannotCalculateException::create($exception);
        }
    }

    /**
     * @return CalculatedOutcome[]
     * @throws CannotFindProductException
     */
    private function getCalculatedOutcomes(): array
    {
        $outcomes = [];

        foreach ($this->products as $productQuantityReference) {
            $productId = $productQuantityReference->getProductId();
            $quantity = $productQuantityReference->getQuantity();
            $product = $this->productFinder->findProduct($productId, $this->countryCode);

            $unitTotalPrice = round($product->getPrice() * $quantity, 2);
            $taxedPrice = round(($unitTotalPrice / 100) * $product->getTax(), 2);
            $totalPrice = $unitTotalPrice + $taxedPrice;

            $outcomes[] = $this->calculatedOutcomeFactory->createCalculatedOutcome($productId, $quantity, $unitTotalPrice, $taxedPrice, $totalPrice);
        }

        return $outcomes;
    }
}
