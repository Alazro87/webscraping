<?php

/*
 * @author: Alazro87
 */

$jsonProducts = var_dump(init());

function init() {
    include "simple_html_dom.php";

    $html = new simple_html_dom();
    $html = file_get_html('https://videx.comesconnected.com');

// creating an array of elements
    $products = [];
    $discount = false;
    foreach ($html->find('div.row-subscriptions') as $e) {
        foreach ($e->find('div.package') as $all) {
            foreach ($all->find('div.header') as $title) {
                $title = $title->plaintext;
            }
            foreach ($all->find('div.package-name') as $description) {
                $description = $description->plaintext;
            }
            foreach ($all->find('span.price-big') as $price) {
                $price = $price->plaintext;
            }
            foreach ($all->find('p') as $discount) {
                $discount = $discount->plaintext;
            }
            $price = str_replace('£', '', $price);
            $calculatePrice = $discount ? $price : $price * 12;

            $products[] = [
                'title' => $title,
                'description' => $description,
                'price' => '£' . $calculatePrice,
                'discount' => $discount ? $discount : 0
            ];
        }
    }
    $price = array_column($products, 'price');
    $price = str_replace('£', '', $price);

    array_multisort($price, SORT_DESC, $products);
    return json_encode($products);
}

?>