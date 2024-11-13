<?php

namespace Rede;

class Consumer implements RedeSerializable
{
    use SerializeTrait;

    public const MALE = 'M';

    public const FEMALE = 'F';

    /**
     * @var array<object>
     */
    private array $documents = [];

    private ?string $gender = null;

    private ?Phone $phone = null;

    /**
     * Consumer constructor.
     */
    public function __construct(private string $name, private string $email, private string $cpf)
    {
    }

    /**
     * @return $this
     */
    public function addDocument(string $type, string $number): static
    {
        $document = new \stdClass();
        $document->type = $type;
        $document->number = $number;

        $this->documents[] = $document;

        return $this;
    }

    /**
     * @return \ArrayIterator<int,object>
     */
    public function getDocumentsIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->documents);
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): Consumer
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    /**
     * @return $this
     */
    public function setPhone(string $ddd, string $number, int $type = Phone::CELLPHONE): static
    {
        $this->phone = new Phone($ddd, $number, $type);

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Consumer
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Consumer
    {
        $this->email = $email;

        return $this;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): Consumer
    {
        $this->cpf = $cpf;

        return $this;
    }
}
