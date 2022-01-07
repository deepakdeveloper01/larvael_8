<?php
namespace App\Http\Requests\CmsPageGallery;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
         $rules = [
            'name'       => 'required|min:4|max:255',
            'sort_order' =>'required',
            'image_path' =>'required',
            'cms_page_id'=>'required',
        ];  	 
		return $rules;
	}
}