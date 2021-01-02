<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Helpers;

class ProductsController extends Controller
{


    //  public function __construct()
 //   {
   //     $this->middleware('auth');
 //   }

    public function create()
    {
        return view('products.create');
    }

    public function test(){



        return response('hello');

    }

    public function store(Request $request)
    {
     //   echo 'hello';

     //   die();

        $tags = explode(',',$request->post('tags'));
        $productId = self::getProductId();

        if(self::newProduct($productId, [
            'name' => $request->post('product_name'),
            'image' => $request->post('product_image'),
            'product_id' => $productId
        ])){
            self::addToTags($tags);
            self::addToProductTags($productId, $tags);
            self::addProductToTags($productId, $tags);
        }

        return redirect()->route('product.all');
    }

    public function viewProducts()
    {
    //    $tags = Redis::sMembers('tags');
    //    $products = self::getProducts();

        $redishelper = new Helpers\RedisHelper();

        $value = $redishelper ->testredis();

        return response($value);
    }

    /*
    * Increment product ID every time
    * a new product is added, and return
    * the ID to be used in product object
    */
    static function getProductId()
    {
        if(!Redis::exists('product_count'))
		   Redis::set('product_count',0);

		  return Redis::incr('product_count');
	 }

    /*
    * Create a hash map to hold a project object
    * e.g HMSET product:1 product "men jean" id 1 image "img-url.jpg"
    * Then add the product ID to a list hold all products ID's
    */
    static function newProduct($productId, $data) //: bool
    {
        self::addToProducts($productId);

        return Redis::hMset("product:$productId", $data);
    }

    /*
    * A Ordered Set holding all products ID with the
    * PHP time() when the product was added as the score
    * This ensures products are listed in DESC when fetched
    */
    static function addToProducts($productId)  //: void
    {
        Redis::zAdd('products', time(), $productId);
    }

    /*
    * A unique Sets of tags
    */
    static function addToTags(array $tags)
    {
        Redis::sAddArray('tags',$tags);
    }

    /*
    * A unique set of tags for a particular product
    * eg SADD product:1:tags jean men pants
    */
    static function addToProductTags($productId, $tags)
    {
        Redis::sAddArray("product:$productId:tags",$tags);
    }

    /*
    * A List of products carry this particular tag
    * ex1 RPUSH men 1 3
    * ex2 RPUSH women 2 4
    */
    static function addProductToTags($productId, $tags)
    {
        foreach ($tags as $tag){
            Redis::rPush($tag,$productId);
        }
    }

    /*
    * In a real live example, we will be returning
    * paginated data by calling the lRange command
    * lRange start end
    */
    static function getProducts($start = 0, $end = -1) // : array
    {
        $productIds = Redis::zRange('products', $start, $end, true);
        $products = [];

        foreach ($productIds as $productId => $score)
        {
            $products[$score]= Redis::hGetAll("product:$productId");
        }

        return $products;
    }

}
