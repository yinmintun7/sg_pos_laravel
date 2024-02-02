<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Category;
class CategoryUpdateRequest extends FormRequest

{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    //  public function prepareForValidation()
    //  {
    //      // If the 'image' is not present in the request, add the old image
    //      if (!$this->hasFile('image')) {
    //          $category = Category::findOrFail($this->id);
    //          $this->merge(['image' => $category->image]);
    //      }
    //  }
    public function rules()
    {

        return [
            'id'         =>['required','numeric'],
            'name'       =>['required',
                            Rule::unique('category')->where(function ($query) {
                                   return $query
                                   ->where('name', $this->name)
                                   ->whereNull('deleted_at');
                            })->ignore($this->id),
                           ],
            'parent_id'  =>['required'],
             //'image'      =>['required','mimes:png,jpg,jpeg,gif']

        ];
        if ($this->has('image')) {
            $rules['image'] = ['required', 'mimes:png,jpg,jpeg,gif'];
        }

        return $rules;

    }


    public function messages()
    {
        return [
            'id.required'          =>'Id required',
            'name.reqired'         =>'Please fill category name!',
            'parent_id.required'   =>'Please select sarent id!',
            'image.required'       =>'Please select photo for category!',
            'image.mimes'          =>'Please upload valid image extension(jpg,jpeg,png,gig)!'
        ];

    }
}
