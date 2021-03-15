<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIBaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;

class CategoriesController extends APIBaseController
{
    public function indexCat() {
      $category = Category::all();
      return $this->sendResponse($category);
    }

    public function indexSubCat() {
      $subcategory = SubCategory::with('category:id,name_category')->get();
      return $this->sendResponse($subcategory);
    }

    public function indexChCat(){
      $childcategory = ChildCategory::with('sub_category_id:id,name_sub_category')->get();
      return $this->sendResponse($childcategory);
    }

    public function detailCat(Request $request, $id) {
      $category = Category::findOrFail($id);
      $data = $category->subcategory->where('category_id', $id)->all();
      return $this->sendResponse($data);
    }

    public function detailSubCat (Request $request, $id) {
      $subcategory = SubCategory::findOrFail($id);
      $data = $subcategory->childcategory->where('sub_category_id', $id)->all();
      return $this->sendResponse($data);
    }
}
