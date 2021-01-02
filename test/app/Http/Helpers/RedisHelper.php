<?php

namespace App\Http\Helpers;



class RedisHelper
{

    public $redis;

    function __construct() {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }



    public function testredis(){

      //  $redis = new Redis();
     //   $redis->connect('127.0.0.1', 6379);

        $test1 = $this->redis;

      //  $test1->

     //   $redis->connect('127.0.0.1', 6379);

        $test1->rpush("languages", "french"); // [french]
        $test1->rpush("languages", "arabic"); // [french, arabic]

        $test1->lpush("languages", "english"); // [english, french, arabic]
        $test1->lpush("languages", "swedish"); // [swedish, english, french, arabic]

        $test1->lpop("languages"); // [english, french, arabic]
        $test1->rpop("languages"); // [english, french]

        $test1->llen("languages"); // 2

        $languages = $test1->lrange("languages", 0, -1); // returns all elements
        $test1->lrange("languages", 0, 1); // [english, french]

        foreach ($languages as $language)

            $test1->set('name1','David');

        $thename = $test1->get('name1');



        return $languages;

    }

}
