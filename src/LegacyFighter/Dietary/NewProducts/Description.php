<?php

namespace LegacyFighter\Dietary\NewProducts;

class Description {

    /**
     * @var string
     */
    private $desc;

    /**
     * @var string
     */
    private $longDesc;

    /**
     * Description constructor.
     * @param string $desc
     * @param string $longDesc
     * @throws \Exception
     */
    public function __construct(?string $desc, ?string $longDesc)
    {
        if ($desc === null) {
            throw new \Exception("Cannot have a null description");
        }

        if ($longDesc === null) {
            throw new \Exception("Cannot have null long description");
        }

        $this->desc = $desc;
        $this->longDesc = $longDesc;
    }

    /**
     * @return string
     */
    public function formatted(): string
    {
        if (empty($this->desc) || empty($this->longDesc)) {
            return "";
        }

        return $this->desc . " *** " . $this->longDesc;
    }

    /**
     * @param string $charToReplace
     * @param string $replaceWith
     * @return Description
     * @throws \Exception
     */
    public function replace(string $charToReplace, string $replaceWith): Description
    {
        return new Description(
            str_replace($charToReplace, $replaceWith, $this->desc),
            str_replace($charToReplace, $replaceWith, $this->longDesc)
        );
    }
}
