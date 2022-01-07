<?php
namespace App\Http\Requests\CmsPage;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $rules = [
			'name'  		 => 'required|min:4|max:255',
			//'slug'			 => 'required|alpha_dash|unique:pages,slug',
			//'image_path' => 'image|mimes:jpeg,jpg,bmp,png|max:5120',
            'short_description'=>	'required|min:4|max:255',	
			'description' 	 => 'required|min:10|max:5000',
            'sort_order'=>'required',
        ];		 
		return $rules;
	}
}


 