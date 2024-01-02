<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PinnedArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'article_id' => 'required|string',
            'title' => 'required|string',
            'url' => 'required|url',
            'section' => 'required|string',
            'platform' => 'required|string|in:the_guardian',
            'publication_date' => 'required|string',
            'thumbnail' => 'required|string',
        ];
    }
}
