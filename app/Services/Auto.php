<?php

namespace App\Services;

class Auto
{
    protected $farbe;


    /**
     * Get the value of farbe
     */
    public function getFarbe()
    {
        return $this->farbe;
    }

    /**
     * Set the value of farbe
     *
     * @return  self
     */
    public function setFarbe($farbe)
    {
        $this->farbe = $farbe;

        return $this;
    }
}
