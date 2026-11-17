<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Http\Requests\StoreAddressRequest;
use App\Modules\Customer\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function create(): View
    {
        return view('customer::addresses.create');
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();

        $isFirst = ! $customer->addresses()->exists();

        $customer->addresses()->create($request->validated() + ['is_default' => $isFirst]);

        return redirect()->route('account')->with('success', 'Alamat berhasil ditambahkan.');
    }
}
