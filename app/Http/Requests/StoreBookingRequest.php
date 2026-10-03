<?php

namespace App\Http\Requests;

use App\Services\BookingService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|min:9|max:20',
            'plate_number' => ['required', 'string', 'max:15'],
            'motorcycle_type' => 'required|string|max:100',
            'service_date' => 'required|date|after_or_equal:today',
            'service_time' => 'required|string|in:' . implode(',', BookingService::OPERATIONAL_SLOTS),
            'service_package_id' => 'required|exists:service_packages,id',
            'selected_parts' => 'nullable|array',
            'selected_parts.*' => 'exists:service_parts,id',
            'complaints' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom Indonesian validation messages.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama lengkap pelanggan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp / HP pelanggan wajib diisi.',
            'plate_number.required' => 'Nomor plat motor wajib diisi (contoh: B 1234 ABC).',
            'motorcycle_type.required' => 'Tipe motor Honda wajib dipilih atau diisi.',
            'service_date.required' => 'Tanggal servis wajib ditentukan.',
            'service_date.after_or_equal' => 'Tanggal servis tidak boleh tanggal lampau.',
            'service_time.required' => 'Jam servis wajib dipilih.',
            'service_time.in' => 'Jam servis tidak sesuai dengan jadwal operasional AHASS.',
            'service_package_id.required' => 'Silakan pilih salah satu paket servis Honda terlebih dahulu.',
            'service_package_id.exists' => 'Paket servis Honda yang dipilih tidak valid.',
        ];
    }

    /**
     * Handle failed validation for AJAX / JSON response.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422));
    }
}
