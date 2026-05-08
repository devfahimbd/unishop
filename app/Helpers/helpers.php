<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '$' . number_format((float)$amount, 2);
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($num)
    {
        return number_format((int)$num);
    }
}
