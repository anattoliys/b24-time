<?php

class Chart
{
    public static function getColor()
    {
        $rgbColor = '';

        foreach(['r', 'g', 'b'] as $color) {
            $rgbColor .= mt_rand(0, 255) . ', ';
        }

        return $rgbColor;
    }
}
