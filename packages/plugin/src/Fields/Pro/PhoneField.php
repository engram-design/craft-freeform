<?php

namespace Solspace\Freeform\Fields\Pro;

use Solspace\Freeform\Attributes\Field\Property;
use Solspace\Freeform\Attributes\Field\Type;
use Solspace\Freeform\Fields\TextField;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\ExtraFieldInterface;
use Solspace\Freeform\Library\Composer\Components\Fields\Interfaces\PhoneMaskInterface;
use Solspace\Freeform\Library\Composer\Components\Validation\Constraints\PhoneConstraint;

#[Type(
    name: 'Phone',
    typeShorthand: 'phone',
    iconPath: __DIR__.'/../Icons/text.svg',
)]
class PhoneField extends TextField implements PhoneMaskInterface, ExtraFieldInterface
{
    protected string $customInputType = 'tel';

    #[Property(
        instructions: "Custom phone pattern (e.g. '(000) 000-0000' or '+0 0000 000000'), where '0' stands for a digit between 0-9. If left blank, any number and dash, dot, space, parentheses and optional + ath the beginning will be validated.",
    )]
    protected ?string $pattern = null;

    #[Property(
        label: 'Use JS validation',
        instructions: 'Enable this to force JS to validate the input on this field based on the pattern.',
    )]
    protected bool $useJsMask = false;

    public function getType(): string
    {
        return self::TYPE_PHONE;
    }

    public function isUseJsMask(): bool
    {
        return $this->useJsMask;
    }

    public function getPattern(): ?string
    {
        return !empty($this->pattern) ? $this->pattern : null;
    }

    public function getConstraints(): array
    {
        $constraints = parent::getConstraints();
        $constraints[] = new PhoneConstraint(
            $this->translate('Invalid phone number'),
            $this->getPattern()
        );

        return $constraints;
    }

    public function getInputHtml(): string
    {
        if (!$this->isUseJsMask()) {
            return parent::getInputHtml();
        }

        $pattern = $this->getPattern();
        $pattern = str_replace('x', '0', $pattern);

        $this
            ->addInputAttribute('class', 'form-phone-pattern-field')
            ->addInputAttribute('data-masked-input', $pattern)
            ->addInputAttribute('data-pattern', $pattern)
        ;

        return parent::getInputHtml();
    }
}
