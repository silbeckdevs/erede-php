<?php

namespace Rede;

class ThreeDSecure implements RedeSerializable
{
    use CreateTrait;
    use SerializeTrait;

    public const DATA_ONLY = 'DATA_ONLY';

    public const CONTINUE_ON_FAILURE = 'continue';

    public const DECLINE_ON_FAILURE = 'decline';

    public const MPI_REDE = 'mpi_rede';

    public const MPI_THIRD_PARTY = 'mpi_third_party';

    private ?string $cavv = null;

    private ?string $eci = null;

    private ?string $url = null;

    private ?string $xid = null;

    private int $threeDIndicator = 2;

    private ?string $directoryServerTransactionId = null;

    private ?string $ipAddress = null;

    private string $userAgent;

    private bool $embedded;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private ?string $challengePreference = null;

    /**
     * ThreeDSecure constructor.
     *
     * @param Device|null $device    user device data
     * @param string      $onFailure what to do in case of failure
     * @param string      $mpi       the MPI is from Rede or third party
     * @param string|null $userAgent the user' user-agent
     */
    public function __construct(
        private readonly ?Device $device = null,
        private string $onFailure = self::DECLINE_ON_FAILURE,
        string $mpi = ThreeDSecure::MPI_REDE,
        ?string $userAgent = null,
    ) {
        if (null === $userAgent) {
            $userAgent = eRede::USER_AGENT;

            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
            }
        }

        $this->embedded = ThreeDSecure::MPI_REDE === $mpi;
        $this->userAgent = $userAgent;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function getThreeDIndicator(): int
    {
        return $this->threeDIndicator;
    }

    /**
     * @return $this
     */
    public function setThreeDIndicator(int $threeDIndicator): static
    {
        /*
         * Support for 3DS 1 will be discontinued.
         */
        if ($threeDIndicator < 2) {
            trigger_error(
                'Effective 15 October 2022, support for 3DS 1 and all related technology is discontinued.',
                time() > strtotime('2022-10-15') ? E_USER_ERROR : E_USER_DEPRECATED
            );
        }

        $this->threeDIndicator = $threeDIndicator;

        return $this;
    }

    public function getDirectoryServerTransactionId(): ?string
    {
        return $this->directoryServerTransactionId;
    }

    /**
     * @return $this
     */
    public function setDirectoryServerTransactionId(string $directoryServerTransactionId): static
    {
        $this->directoryServerTransactionId = $directoryServerTransactionId;

        return $this;
    }

    public function getCavv(): ?string
    {
        return $this->cavv;
    }

    /**
     * @return $this
     */
    public function setCavv(string $cavv): static
    {
        $this->cavv = $cavv;

        return $this;
    }

    public function getEci(): ?string
    {
        return $this->eci;
    }

    /**
     * @return $this
     */
    public function setEci(string $eci): static
    {
        $this->eci = $eci;

        return $this;
    }

    public function getOnFailure(): string
    {
        return $this->onFailure;
    }

    /**
     * @return $this
     */
    public function setOnFailure(string $onFailure): static
    {
        $this->onFailure = $onFailure;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return $this
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @return $this
     */
    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getXid(): ?string
    {
        return $this->xid;
    }

    /**
     * @return $this
     */
    public function setXid(string $xid): static
    {
        $this->xid = $xid;

        return $this;
    }

    public function isEmbedded(): bool
    {
        return $this->embedded;
    }

    /**
     * @return $this
     */
    public function setEmbedded(bool $embedded): static
    {
        $this->embedded = $embedded;

        return $this;
    }

    public function getChallengePreference(): ?string
    {
        return $this->challengePreference;
    }

    public function setChallengePreference(?string $challengePreference): ThreeDSecure
    {
        $this->challengePreference = $challengePreference;

        return $this;
    }
}
