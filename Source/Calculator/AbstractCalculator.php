<?php declare(strict_types=1);

namespace Assignment\Calculator;

use Assignment\Calculator\Exception\CannotCalculateException;
use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\Exception\MissingProductException;
use Assignment\Product\ProductFinderInterface;
use Assignment\Product\ProductQuantityReference;
use Exception;

abstract class AbstractCalculator implements CalculatorInterface
{

    /**
     * @var ProductFinderInterface
     */
    private $productFinder;

    /**
     * @var ConsolidatedCalculationOutcomeFactory
     */
    private $consolidatedCalculationOutcomeFactory;

    /**
     * @var ProductCalculationOutcomeFactory
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
     * @param ProductCalculationOutcomeFactory $calculatedOutcomeFactory
     * @param ConsolidatedCalculationOutcomeFactory $consolidatedCalculationOutcomeFactory
     * @param ProductQuantityReference[] $products
     * @param string $countryCode
     */
    public function __construct(
        ProductFinderInterface                $productFinder,
        ProductCalculationOutcomeFactory      $calculatedOutcomeFactory,
        ConsolidatedCalculationOutcomeFactory $consolidatedCalculationOutcomeFactory,
        array                                 $products,
        string                                $countryCode
    )
    {
        $this->productFinder = $productFinder;
        $this->calculatedOutcomeFactory = $calculatedOutcomeFactory;
        $this->products = $products;
        $this->countryCode = $countryCode;
        $this->consolidatedCalculationOutcomeFactory = $consolidatedCalculationOutcomeFactory;
    }

    /**
     * @return float
     */
    abstract protected function getPreCalculationPrice(): float;

    /**
     * @return float
     */
    abstract protected function getPostCalculationPrice(): float;

    /**
     * @inheritDoc
     */
    public function calculate(): ConsolidatedCalculationOutcome
    {
        $outcome = $this->createConsolidatedCalculationOutcome();
        $outcome->setPreCalculationPrice($this->getPreCalculationPrice());
        $this->addProductCalculationOutcomes($outcome);
        $outcome->setPostCalculationPrice($this->getPostCalculationPrice());

        return $outcome;
    }

    /**
     * @param ConsolidatedCalculationOutcome $consolidatedCalculationOutcome
     * @return void
     * @throws CannotFindProductException
     */
    private function addProductCalculationOutcomes(ConsolidatedCalculationOutcome $consolidatedCalculationOutcome): void
    {
        foreach ($this->products as $productQuantityReference) {
            try {
                $productId = $productQuantityReference->getProductId();
                $quantity = $productQuantityReference->getQuantity();

                if ($quantity < 1) {
                    throw new Exception(sprintf('Quantity must be positive number: %s given', $quantity));
                }

                $product = $this->productFinder->findProduct($productId, $this->countryCode);

                if (null === $product) {
                    throw MissingProductException::create($productId, $this->countryCode);
                }

                $basePrice = round($product->getPrice() * $quantity, 2);

                $taxedPrice = round(($basePrice / 100) * $product->getTax(), 2);
                $totalPrice = $basePrice + $taxedPrice;

                $consolidatedCalculationOutcome->addProductCalculationOutcome(
                    $this->calculatedOutcomeFactory->createCalculatedOutcome(
                        $productId,
                        $quantity,
                        $basePrice,
                        $taxedPrice,
                        $totalPrice
                    )
                );
            } catch (Exception $exception) {
                throw CannotCalculateException::create($exception);
            }
        }
    }

    /**
     * @return ConsolidatedCalculationOutcome
     */
    private function createConsolidatedCalculationOutcome(): ConsolidatedCalculationOutcome
    {
        return $this->consolidatedCalculationOutcomeFactory->create();
    }
}
