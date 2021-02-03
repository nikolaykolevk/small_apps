<?php

class Product
{

    public $id, $category, $name, $price, $imgSrc, $rating, $description;

    function __construct($row)
    {
        $this->id = $row["ID"];
        $this->category = $row["category"];
        $this->name = $row["name"];
        $this->price = $row["price"];
        $this->imgSrc = $row["imgSrc"];
        $this->rating = $row["rating"];
        $this->description = $row["description"];
    }
}

?>