<?php
// Test function for Gemini Code Review
function calculateTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'];
    }
    return $total;
}
