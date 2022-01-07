<?php
namespace App\Http\Requests\CmsPage;
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
			'name'           => 'required|min:4|max:255',
			//'slug'			 => 'required|alpha_dash|unique:pages,slug,'.$this->CmsPage->id,
			'short_description'=>   'required|min:4|max:255',   
            'description'    => 'required|min:10|max:5000',
            'sort_order'=>'required',
        ];		 
		return $rules;
	}
}