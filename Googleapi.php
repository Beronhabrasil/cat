<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Symfony\Component\DomCrawler\Crawler;
use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

class Googleapi extends Controller
{
   



    private function api()
    {
    //    $url = 'https://www.google.com/basepages/producttype/taxonomy-with-ids.gb-GB.txt';

        $lang = "gb";
        $googleCategories = GoogleShopping::categories($lang);
        unset($googleCategories[0]);
        return $googleCategories;
    //      $bytes_written = File::put('C:\data\children_tree.txt',$googleCategories);
    //    if($bytes_written === false){
    //     die("Error writing to file");
    //    }

    }
    public function index(){
        $google_categories = $this->api();
        $relation_array = [];
        $final_array = [];
        $i = 0;
    
        foreach($google_categories as $key => $category){
            $relation_array[$category] = $key;
        }
    
        foreach($google_categories as $key => $category){
            $count = substr_count($category, '>');
            if($count > 1){
                $reversed_string = strrev($category);
                $remove_divider = strstr($reversed_string, '>');
                $remove_divider = substr_replace($remove_divider, "", 0, strlen('>'));
                $remove_divider = substr_replace($remove_divider, "", 0, strlen(' '));
                $restore_reversion_string = strrev($remove_divider);
                $remove_arrows = str_replace(" > ", "/", $category);
                $final_array[$i]['category_id'] = $key;
                $final_array[$i]['parent_id'] = $relation_array[$restore_reversion_string];
                $final_array[$i]['name'] = $remove_arrows;
            } else if($count === 1){
                $reversed_string = strrev($category);
                $remove_divider = strstr($reversed_string, '>');
                $remove_divider = substr_replace($remove_divider, "", 0, strlen('>'));
                $remove_divider = substr_replace($remove_divider, "", 0, strlen(' '));
                $restore_reversion_string = strrev($remove_divider);
                $remove_arrows = str_replace(" > ", "/", $category);
                $final_array[$i]['category_id'] = $key;
                $final_array[$i]['parent_id'] = $relation_array[$restore_reversion_string];
                $final_array[$i]['name'] = $remove_arrows;
            }
            else{
                $final_array[$i]['category_id'] = $key;
                $final_array[$i]['parent_id'] = 0;
                $final_array[$i]['name'] = $category;
            }
                $i++;
        }
                dd($final_array);
    }
                     
}







