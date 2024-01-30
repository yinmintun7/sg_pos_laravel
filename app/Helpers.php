<?php

use App\Constant;
use App\Models\Category;
use App\Models\Item;
if (!function_exists('getParentCategory')) {
    function getParentCategory($parent_id, $screen)
    {
        $categories = Category::where('parent_id', 0)
                               ->whereNull('deleted_at')
                               ->where('status','=',Constant::ENABLE_STATUS)
                               ->get();
        foreach ($categories as $category){
            $dash = '';
            $space = '';
            $disable = '';
            $category_dis = '';
            $count = 1;
            $parent_cat_id =$category['id'];
            $parent_cat_name =$category['name'];
            if ($screen['item']) {
                $is_child_exit = checkChildCategoryExit($parent_cat_id);
                if ($is_child_exit) {
                    $disable = 'disabled';
                }
            }
            if ($screen['category']) {
                $item_exit = checkItemExitByCatId($parent_cat_id);
                if ($item_exit) {
                    $category_dis = 'disabled';
                }
            }
            if ($parent_id == $parent_cat_id) {
                echo "<option value='$parent_cat_id' selected $disable $category_dis>$parent_cat_name</option>";
            } else {
                echo "<option value='$parent_cat_id' $disable $category_dis>$parent_cat_name</option>";
            }

        }
       getChildCategory($parent_cat_id, $count,$screen, $parent_id);

    }

}

if (!function_exists('getChildCategory')) {
    function getChildCategory($parent_cat_id, $count,$screen, $parent_id)
    {
        $child_cats = Category::select('id', 'name')
                    ->where('parent_id', $parent_cat_id)
                    ->whereNull('deleted_at')
                    ->where('status', Constant::ENABLE_STATUS)
                    ->get();
    foreach($child_cats as $child_cat){
            $child_cat_id = $child_cat['id'];
            $child_cat_name = $child_cat['name'];
            $dash = '';
            $space = '';
            $disable = '';
            $category_dis = '';
            for ($i = 0; $i <= $count; $i++) {
                $dash .= '-';
                $space .= '&nbsp;&nbsp;';

            }
            if ($screen['item']) {
                $is_child_exit = checkChildCategoryExit( $child_cat_id);
                if ($is_child_exit) {
                    $disable = 'disabled';
                }
            }
            if ($screen['category']) {
                $item_exit = checkItemExitByCatId($child_cat_id);
                if ($item_exit) {
                    $category_dis = 'disabled';
                }
            }
            if ($parent_id == $child_cat_id) {
                echo "<option value='$child_cat_id' selected $disable $category_dis>" . $space . $dash . "$child_cat_name</option>";
            } else {
                echo "<option value='$child_cat_id' $disable $category_dis>" . $space . $dash . "$child_cat_name</option>";
            }
            $child_exist = Category::where('parent_id',$child_cat_id)
                                ->whereNull('deleted_at')
                                ->where('status',Constant::ENABLE_STATUS)
                                ->count();
            if ($child_exist > 0) {
                $count++;
                getChildCategory($child_cat_id, $count,$screen, $parent_id);
            }
        }
    }
}

if (!function_exists('checkItemExitByCatId')) {
    function checkItemExitByCatId($category_id)
    {
        $item_count = Item::where('category_id',$category_id)
                      ->whereNull('deleted_at')
                      ->where('status',Constant::ENABLE_STATUS)
                      ->count();
        return $item_count;
    }
    }

    if (!function_exists('checkChildCategoryExit')) {
    function checkChildCategoryExit($parent_cat_id)
    {
        $cat_count = Category::where('parent_id',$parent_cat_id)
                            ->whereNull('deleted_at')
                            ->where('status',Constant::ENABLE_STATUS)
                            ->count();
        return $cat_count;
    }
    }

