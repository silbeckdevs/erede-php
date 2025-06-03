<?php

namespace Rede;

class Transaction implements RedeSerializable, RedeUnserializable
{
    use ResponseTrait;

    public const CREDIT = 'credit';

    public const DEBIT = 'debit';

    public const PIX = 'Pix';

    public const ORIGIN_EREDE = 1;

    public const ORIGIN_VISA_CHECKOUT = 4;

    public const ORIGIN_MASTERPASS = 6;

    private ?Additional $additional = null;

    private ?Authorization $authorization = null;

    private ?string $authorizationCode = null;

    private ?int $brandTid = null;

    private ?Brand $brand = null;

    private ?string $cancelId = null;

    private bool|Capture|null $capture = null;

    private ?string $cardBin = null;

    private ?string $cardHolderName = null;

    private ?string $cardNumber = null;

    private ?Cart $cart = null;

    private ?\DateTime $dateTime = null;

    private ?int $distributorAffiliation = null;

    private int|string|null $expirationMonth = null;

    private int|string|null $expirationYear = null;

    private ?Iata $iata = null;

    private ?int $installments = null;

    private ?string $kind = null;

    private ?string $last4 = null;

    private ?string $nsu = null;

    private ?int $origin = null;

    private ?\DateTime $refundDateTime = null;

    private ?string $refundId = null;

    /**
     * @var array<Refund>
     */
    private array $refunds = [];

    private ?\DateTime $requestDateTime = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private ?string $securityCode = null;

    private ?string $softDescriptor = null;

    private ?int $storageCard = null;

    private ?bool $subscription = null;

    private ?ThreeDSecure $threeDSecure = null;

    private ?string $tid = null;

    /**
     * @var array<Url>
     */
    private array $urls = [];

    private ?SubMerchant $subMerchant = null;

    private ?string $paymentFacilitatorID = null;

    private ?int $amount = null;

    private ?QrCode $qrCode = null;

    /**
     * Transaction constructor.
     */
    public function __construct(int|float|null $amount = null, private ?string $reference = null)
    {
        if (null !== $amount) {
            $this->setAmount($amount);
        }
    }

    /**
     * @return $this
     */
    public function addUrl(string $url, string $kind = Url::CALLBACK): static
    {
        $this->urls[] = new Url($url, $kind);

        return $this;
    }

    /**
     * @return $this
     */
    public function additional(?int $gateway = null, ?int $module = null): static
    {
        $this->additional = new Additional();

        if (null !== $gateway) {
            $this->additional->setGateway($gateway);
        }

        if (null !== $module) {
            $this->additional->setModule($module);
        }

        return $this;
    }

    /**
     * @return $this this transaction
     */
    public function creditCard(
        string $cardNumber,
        string $cardCvv,
        int|string $expirationMonth,
        int|string $expirationYear,
        string $holderName,
    ): static {
        return $this->setCard(
            $cardNumber,
            $cardCvv,
            $expirationMonth,
            $expirationYear,
            $holderName,
            Transaction::CREDIT
        );
    }

    /**
     * @return $this this transaction
     */
    public function setCard(
        string $cardNumber,
        string $securityCode,
        int|string $expirationMonth,
        int|string $expirationYear,
        string $cardHolderName,
        string $kind,
    ): static {
        $this->setCardNumber($cardNumber);
        $this->setSecurityCode($securityCode);
        $this->setExpirationMonth($expirationMonth);
        $this->setExpirationYear($expirationYear);
        $this->setCardHolderName($cardHolderName);
        $this->setKind($kind);

        return $this;
    }

    /**
     * @return $this this transaction
     */
    public function debitCard(
        string $cardNumber,
        string $cardCvv,
        int|string $expirationMonth,
        int|string $expirationYear,
        string $holderName,
    ): static {
        $this->capture();

        return $this->setCard(
            $cardNumber,
            $cardCvv,
            $expirationMonth,
            $expirationYear,
            $holderName,
            Transaction::DEBIT
        );
    }

    /**
     * @return $this
     */
    public function capture(bool $capture = true): static
    {
        if (!$capture && Transaction::DEBIT === $this->kind) {
            throw new \InvalidArgumentException('Debit transactions will always be captured');
        }

        $this->capture = $capture;

        return $this;
    }

    /**
     * @see          \JsonSerializable::jsonSerialize()
     *
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        $capture = null;

        if (is_bool($this->capture)) {
            $capture = $this->capture ? 'true' : 'false';
        }

        return array_filter(
            [
                'capture' => $capture,
                'cart' => $this->cart,
                'kind' => $this->kind,
                'threeDSecure' => $this->threeDSecure,
                'reference' => $this->reference,
                'amount' => $this->amount,
                'installments' => $this->installments,
                'cardHolderName' => $this->cardHolderName,
                'cardNumber' => $this->cardNumber,
                'expirationMonth' => $this->expirationMonth,
                'expirationYear' => $this->expirationYear,
                'securityCode' => $this->securityCode,
                'softDescriptor' => $this->softDescriptor,
                'subscription' => $this->subscription,
                'origin' => $this->origin,
                'distributorAffiliation' => $this->distributorAffiliation,
                'storageCard' => $this->storageCard,
                'urls' => $this->urls ?: null,
                'iata' => $this->iata,
                'additional' => $this->additional,
                'qrCode' => $this->qrCode,
            ],
            function ($value) {
                return !is_null($value);
            }
        );
    }

    public function getQrCode(): ?QrCode
    {
        return $this->qrCode;
    }

    public function setQrCode(QrCode $qrCode): static
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function createQrCode(\DateTimeInterface $dateTimeExpiration): static
    {
        $this->qrCode = new QrCode();
        $this->qrCode->setDateTimeExpiration($dateTimeExpiration);
        $this->setKind(Transaction::PIX);

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return $this
     */
    public function setAmount(int|float $amount): static
    {
        $this->amount = (int) round($amount * 100);

        return $this;
    }

    public function getAuthorization(): ?Authorization
    {
        return $this->authorization;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    public function getCancelId(): ?string
    {
        return $this->cancelId;
    }

    public function getCapture(): bool|Capture|null
    {
        return $this->capture;
    }

    public function getCardBin(): ?string
    {
        return $this->cardBin;
    }

    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    /**
     * @return $this
     */
    public function setCardHolderName(string $cardHolderName): static
    {
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * @return $this
     */
    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @return $this
     */
    public function setCart(Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function getDistributorAffiliation(): ?int
    {
        return $this->distributorAffiliation;
    }

    /**
     * @return $this
     */
    public function setDistributorAffiliation(int $distributorAffiliation): static
    {
        $this->distributorAffiliation = $distributorAffiliation;

        return $this;
    }

    public function getExpirationMonth(): int|string|null
    {
        return $this->expirationMonth;
    }

    /**
     * @return $this
     */
    public function setExpirationMonth(int|string $expirationMonth): static
    {
        $this->expirationMonth = $expirationMonth;

        return $this;
    }

    public function getExpirationYear(): int|string|null
    {
        return $this->expirationYear;
    }

    /**
     * @return $this
     */
    public function setExpirationYear(int|string $expirationYear): static
    {
        $this->expirationYear = $expirationYear;

        return $this;
    }

    public function getIata(): ?Iata
    {
        return $this->iata;
    }

    /**
     * @return $this
     */
    public function setIata(string $code, string $departureTax): static
    {
        $this->iata = new Iata();
        $this->iata->setCode($code);
        $this->iata->setDepartureTax($departureTax);

        return $this;
    }

    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    /**
     * @return $this
     */
    public function setInstallments(int $installments): static
    {
        $this->installments = $installments;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * @return $this
     */
    public function setKind(string $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getLast4(): ?string
    {
        return $this->last4;
    }

    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    public function getOrigin(): ?int
    {
        return $this->origin;
    }

    /**
     * @return $this
     */
    public function setOrigin(int $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getRefundDateTime(): ?\DateTime
    {
        return $this->refundDateTime;
    }

    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    /**
     * @return Refund[]
     */
    public function getRefunds(): array
    {
        return $this->refunds;
    }

    public function getRequestDateTime(): ?\DateTime
    {
        return $this->requestDateTime;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function getSecurityCode(): ?string
    {
        return $this->securityCode;
    }

    /**
     * @return $this
     */
    public function setSecurityCode(string $securityCode): static
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    public function getSoftDescriptor(): ?string
    {
        return $this->softDescriptor;
    }

    /**
     * @return $this
     */
    public function setSoftDescriptor(string $softDescriptor): static
    {
        $this->softDescriptor = $softDescriptor;

        return $this;
    }

    public function getStorageCard(): ?int
    {
        return $this->storageCard;
    }

    /**
     * @return $this
     */
    public function setStorageCard(int $storageCard): static
    {
        $this->storageCard = $storageCard;

        return $this;
    }

    /**
     * @return $this
     */
    public function iata(string $code, string $departureTax): static
    {
        return $this->setIata($code, $departureTax);
    }

    public function isSubscription(): bool
    {
        return $this->subscription ?? false;
    }

    /**
     * @return $this
     */
    public function setSubscription(bool $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getThreeDSecure(): ThreeDSecure
    {
        return $this->threeDSecure ?? new ThreeDSecure();
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    /**
     * @return $this
     */
    public function setTid(string $tid): static
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * @return \ArrayIterator<int,Url>
     */
    public function getUrlsIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->urls);
    }

    /**
     * @return $this
     */
    public function mcc(string $softDescriptor, string $paymentFacilitatorID, SubMerchant $subMerchant): static
    {
        $this->setSoftDescriptor($softDescriptor);
        $this->setPaymentFacilitatorID($paymentFacilitatorID);
        $this->setSubMerchant($subMerchant);

        return $this;
    }

    public function getSubMerchant(): ?SubMerchant
    {
        return $this->subMerchant;
    }

    /**
     * @return $this
     */
    public function setSubMerchant(SubMerchant $subMerchant): static
    {
        $this->subMerchant = $subMerchant;

        return $this;
    }

    public function getPaymentFacilitatorID(): ?string
    {
        return $this->paymentFacilitatorID;
    }

    /**
     * @return $this
     */
    public function setPaymentFacilitatorID(string $paymentFacilitatorID): static
    {
        $this->paymentFacilitatorID = $paymentFacilitatorID;

        return $this;
    }

    /**
     * @return $this
     */
    public function threeDSecure(
        Device $device,
        string $onFailure = ThreeDSecure::DECLINE_ON_FAILURE,
        string $mpi = ThreeDSecure::MPI_REDE,
        string $directoryServerTransactionId = '',
        ?string $userAgent = null,
        int $threeDIndicator = 2,
    ): static {
        $threeDSecure = new ThreeDSecure($device, $onFailure, $mpi, $userAgent);
        $threeDSecure->setThreeDIndicator($threeDIndicator);
        $threeDSecure->setDirectoryServerTransactionId($directoryServerTransactionId);

        $this->threeDSecure = $threeDSecure;

        return $this;
    }

    public function getBrandTid(): ?int
    {
        return $this->brandTid;
    }

    /**
     * @return $this
     */
    public function setBrandTid(int $brandTid): static
    {
        $this->brandTid = $brandTid;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @return $this
     */
    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    public function jsonUnserialize(string $serialized): static
    {
        $properties = json_decode($serialized);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf('JSON: %s', json_last_error_msg()));
        }

        foreach (get_object_vars($properties) as $property => $value) {
            // TODO verify why use urls in request and not use links in response
            if ('links' === $property || null === $value) {
                continue;
            }

            match ($property) {
                'refunds' => $this->unserializeRefunds($property, $value),
                'urls' => $this->unserializeUrls($property, $value),
                'capture' => $this->unserializeCapture($property, $value),
                'authorization' => $this->unserializeAuthorization($property, $value),
                'additional' => $this->unserializeAdditional($property, $value),
                'threeDSecure' => $this->unserializeThreeDSecure($property, $value),
                'requestDateTime', 'dateTime', 'refundDateTime' => $this->unserializeRequestDateTime($property, $value),
                'brand' => $this->unserializeBrand($property, $value),
                'qrCodeResponse' => $this->unserializeQrCode($property, $value),
                default => $this->{$property} = $value,
            };
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    private function unserializeRefunds(string $property, mixed $value): void
    {
        if ('refunds' === $property && is_array($value)) {
            $this->refunds = [];

            foreach ($value as $refundValue) {
                $this->refunds[] = (new Refund())->populate($refundValue);
            }
        }
    }

    private function unserializeUrls(string $property, mixed $value): void
    {
        if ('urls' === $property && is_array($value)) {
            $this->urls = [];

            foreach ($value as $urlValue) {
                $this->urls[] = new Url($urlValue->url, $urlValue->kind);
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeCapture(string $property, mixed $value): void
    {
        if ('capture' === $property && is_object($value)) {
            $this->capture = (new Capture())->populate($value);
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeAuthorization(string $property, mixed $value): void
    {
        if ('authorization' == $property && is_object($value)) {
            $this->authorization = (new Authorization())->populate($value);
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeAdditional(string $property, mixed $value): void
    {
        if ('additional' == $property && is_object($value)) {
            $this->additional = (new Additional())->populate($value);
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeThreeDSecure(string $property, mixed $value): void
    {
        if ('threeDSecure' == $property && is_object($value)) {
            $this->threeDSecure = (new ThreeDSecure())->populate($value);
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeRequestDateTime(string $property, mixed $value): void
    {
        if ('requestDateTime' == $property || 'dateTime' == $property || 'refundDateTime' == $property) {
            $value = new \DateTime($value);
        }

        $this->{$property} = $value;
    }

    /**
     * @throws \Exception
     */
    private function unserializeBrand(string $property, mixed $value): void
    {
        if ('brand' == $property) {
            $this->brand = (new Brand())->populate($value);
        }
    }

    /**
     * @throws \Exception
     */
    private function unserializeQrCode(string $property, mixed $value): void
    {
        if (('qrCodeResponse' === $property) && is_object($value)) {
            $this->qrCode = (new QrCode())->populate($value);
        }
    }

    public function getFirstAuthorizationCode(): ?string
    {
        return $this->getAuthorizationCode()
            ?: $this->getBrand()?->getAuthorizationCode()
            ?: $this->getAuthorization()?->getAuthorizationCode()
            ?: $this->getAuthorization()?->getBrand()?->getAuthorizationCode();
    }
}
