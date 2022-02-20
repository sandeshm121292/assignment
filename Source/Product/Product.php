<?php declare(strict_types=1);

namespace Assignment\Product;

use Assignment\Product\Exception\CannotFindProductException;
use Assignment\Product\Exception\MissingProductException;
use Exception;
use PDO;

class Product implements ProductInterface, ProductFinderInterface
{
    const ID = 'id';
    const TITLE = 'title';
    const COUNTRY_CODE = 'countryCode';
    const TAX = 'tax';
    const PRICE = 'price';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $tax;

    /**
     * @var float
     */
    private $price;

    /**
     * @var PDO
     */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function findProduct(string $id, string $countryCode): ProductInterface
    {
        $query = <<<SQL
SELECT p.*, pt.tax, pt.countryCode FROM products AS p
INNER JOIN product_taxes AS pt ON p.id = pt.productId
WHERE p.id = "%s" AND pt.countryCode = "%s"
SQL;

        try {
            $queryString = sprintf($query, $id, $countryCode);
            $productArray = $this->db->query($queryString)->fetch(PDO::FETCH_ASSOC);

            if (false === $productArray) {
                throw MissingProductException::create($id, $countryCode);
            }

            return $this->createProductFromArray($productArray);
        } catch (Exception $exception) {
            throw CannotFindProductException::create($id, $countryCode, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Product
     */
    public function setId(string $id): Product
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return Product
     */
    public function setCountryCode(string $countryCode): Product
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Product
     */
    public function setTitle(string $title): Product
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     * @return Product
     */
    public function setTax(float $tax): Product
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param array $productArray
     * @return ProductInterface
     */
    private function createProductFromArray(array $productArray): ProductInterface
    {
        return $this
            ->setId($productArray[self::ID])
            ->setTitle($productArray[self::TITLE])
            ->setCountryCode($productArray[self::COUNTRY_CODE])
            ->setTax((float)$productArray[self::TAX])
            ->setPrice((float)$productArray[self::PRICE]);
    }
}