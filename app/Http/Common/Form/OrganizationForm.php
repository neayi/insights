<?php


namespace App\Http\Common\Form;


class OrganizationForm
{
    public function process()
    {
        $request = request();
        $name = $request->input('name') !== null ? $request->input('name') : '';
        $address1 = $request->input('address1') !== null ? $request->input('address1') : '';
        $address2 = $request->input('address2') !== null ? $request->input('address2') : '';
        $pc = $request->input('pc') !== null ? $request->input('pc') : '';
        $city = $request->input('city') !== null ? $request->input('city') : '';
        $address = $this->processAddress($address1, $address2, $pc, $city);
        $picture = $this->processPicture($request);
        return [$name, $address, $picture];
    }

    private function processPicture($request): array
    {
        $picture = [];
        if ($request->has('logo')) {
            $picture['path_picture'] = $request->file('logo')->path();
            $picture['original_name'] = $request->file('logo')->getClientOriginalName();
            $picture['mine_type'] = $request->file('logo')->getMimeType();
        }
        return $picture;
    }

    private function processAddress(string $address1, string $address2, string $pc, string $city): array
    {
        $address = [
            'address1' => $address1,
            'address2' => $address2,
            'pc' => $pc,
            'city' => $city,
        ];
        return $address;
    }
}
