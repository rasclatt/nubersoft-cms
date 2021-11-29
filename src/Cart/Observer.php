<?php
namespace NubersoftCms\Cart;

class Observer extends \NubersoftCms\Observer
{
    /**
     *	@description	Example product array
     */
    public function getProducts()
    {
        $this->nApp->ajaxResponse([
            "1011400" => [
                'title' => "Widget Express",
                'description' => "This widget is the product for you and is clearly awesome",
                'cv' => 100,
                'sh' => 17.99,
                'price' => 99.99
            ]
        ]);
    }
}