<?php
require_once("src/isdk.php");
$debug = false;
$app = new iSDK;

if ($app->cfgCon("conn"))
{

    // look up products in categories
    $returnFields = array('Id', 'ProductId', 'ProductCategoryId');
    $query = array('ProductCategoryId' => '%');
    $products = $app->dsQuery("ProductCategoryAssign",10,0,$query,$returnFields);

    // instantiation
    $bigProducts = [];
    $mediumProducts = [];
    $smallProducts = [];

    // add array to big products
    foreach ($products as $product)
    {
        if ($product['ProductCategoryId'] === 3)
        {
            array_push($bigProducts, $product);
        }
    }

    // add array to small products
    foreach ($products as $product)
    {
        if ($product['ProductCategoryId'] === 5)
        {
            array_push($smallProducts, $product);
        }
    }

    // add array to medium products
    foreach ($products as $product)
    {
        if ($product['ProductCategoryId'] === 7)
        {
            array_push($mediumProducts, $product);
        }
    }

    // looks up productInfo
    function productLookUp ($productId)
    {
        global $app;

        // look up product info by categoryId and productId
        $returnFields = array('ProductName', 'ProductPrice', 'Description', 'LargeImage');
        $query = array('Id' => $productId);
        $productInfo = $app->dsQuery("Product",10,0,$query,$returnFields);

        // assign key fields to vars
        $productName = $productInfo[0]['ProductName'];
        $productPrice = $productInfo[0]['ProductPrice'];
        $productDescription = $productInfo[0]['Description'];
        $productImage = $productInfo[0]['LargeImage'];

        // create product listing
        echo '<section data-productId='. $productId .'>';
            echo '<h2>' . $productName . '</h2>';
            echo '<h4>Price: $' . $productPrice . '</h4>';
            echo '<input data-productid='. $productId .' type="text" placeholder="Quantity (Ex. 4)">';
        echo '</section>';
    }

    if ($debug)
    {
        echo '<h2>Big Products</h2></br><pre>';
            print_r($bigProducts);
        echo '</pre>';

        echo '<h2>Medium Products</h2></br><pre>';
            print_r($mediumProducts);
        echo '</pre>';

        echo '<h2>Small Products</h2></br><pre>';
            print_r($smallProducts);
        echo '</pre>';
    }

    // define app
    $appName = 'vf267';

    // create the bundle link
    $bundleLink = 'https://'. $appName .'.infusionsoft.com/app/manageCart/processBundle';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Proof of Concept</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js" type="text/javascript"></script>
<style>
section {
    width: 30%;
    margin: 1%;
    vertical-align: top;
}
section h2, section h4, section input {
    display: inline-block;
    margin: 0 1%;
}
</style>
<?php
echo "<script type='text/javascript'>
    jQuery(function()
    {
        jQuery('.buyNow').on('click', function()
        {
            // setting up link vars
            var bundleVars = '?';
            var bundleLink = '". $bundleLink ."';

            // get any input field with a value and add it to the bundle link
            jQuery('section input').each(function()
            {
                if(this.value){
                    var prodQuant = this.value;
                    var prodId = jQuery(this).data('productid');
                    bundleVars += 'productId=' + prodId + '&productQuantity=' + prodQuant + '&';
                }
            });
            var finalLink = bundleLink + bundleVars + '&wholeParam=1';
            jQuery('.buyNow').attr('href', finalLink);
        });
    });
</script>";
?>
</head>
<body>
    <?php
    // call productLookUp on each product in category
    echo '<h2>Big Products</h2>';
    foreach($bigProducts as $bigProduct)
    {
        productLookUp($bigProduct['ProductId']);
    }

    echo '<h2>Medium Products</h2>';
    foreach($mediumProducts as $mediumProduct)
    {
        productLookUp($mediumProduct['ProductId']);
    }

    echo '<h2>Small Products</h2>';
    foreach($smallProducts as $smallProduct)
    {
        productLookUp($smallProduct['ProductId']);
    }
    ?>

    <a style="display: block; background-color: black; color: white; padding: 2% 4%; border-radius: 4px;" class="buyNow">Buy Now</a>
</body>
</html>
