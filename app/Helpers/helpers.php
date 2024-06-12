<?php

if (!function_exists('redirectNotAuthorized')) {

    /**
     * Function to compacting redirect->with
     */
    function redirectNotAuthorized($url)
    {
        return redirect($url)->with('warning', 'Not Authorized');
    }
}

if (!function_exists('redirectWithAlert')) {

    /**
     * Function to compacting redirect->with
     */
    function redirectWithAlert($url, $mode, $msg)
    {
        return redirect($url)->with($mode, $msg);
    }
}

if (!function_exists('countPph')) {

    /**
     * Function to count pph21 for 1 year
     */
    function countPph($pkp)
    {
        $first_layer = 50000000;
        $second_layer = 250000000;
        $third_layer = 500000000;

        if ($pkp <= $first_layer) {
            return $pkp * 0.05;
        }
        $pajak = $first_layer * 0.05;
        $sisa = $pkp - $first_layer;
        if ($sisa <= $second_layer - $first_layer) {
            return $pajak + $sisa * 0.15;
        }
        $pajak = $pajak + ($second_layer - $first_layer) * 0.15;
        $sisa = $sisa - ($second_layer - $first_layer);
        if ($sisa <= $third_layer - ($second_layer + $first_layer)) {
            return $pajak + $sisa * 0.25;
        }
        $pajak = $pajak + ($third_layer - ($second_layer + $first_layer)) * 0.25;
        $sisa = $sisa - ($third_layer - ($second_layer + $first_layer));
        $pajak = $pajak + $sisa * 0.30;
        return $pajak;
    }
}
