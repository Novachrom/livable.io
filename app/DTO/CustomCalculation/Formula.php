<?php

namespace App\DTO\CustomCalculation;

use Illuminate\Http\Request;

class Formula
{
    /** @var string */
    private $var_name;

    /** @var string */
    private $formula;

    /** @var string */
    private $description;

    public function __construct(string $var_name, string $formula, string $description)
    {
        $this->var_name = $var_name;
        $this->formula = $formula;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getVarName(): string
    {
        return $this->var_name;
    }

    /**
     * @return string
     */
    public function getFormula(): string
    {
        return $this->formula;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->get('var_name'),
            $request->get('formula'),
            $request->get('description', '')
        );
    }
}
