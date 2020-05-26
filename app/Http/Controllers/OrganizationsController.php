<?php


namespace App\Http\Controllers;

use App\Src\UseCases\Domain\CreateOrganization;
use App\Src\UseCases\Domain\ListOrganizations;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function showAddForm()
    {
        return view('organizations/add_form');
    }

    public function processAdd(Request $request, CreateOrganization $createOrganization)
    {
        $name = $request->input('name') !== null ? $request->input('name') : '';
        $address1 = $request->input('address1') !== null ? $request->input('address1') : '';
        $address2 = $request->input('address2') !== null ? $request->input('address2') : '';
        $pc = $request->input('pc') !== null ? $request->input('pc') : '';
        $city = $request->input('city') !== null ? $request->input('city') : '';
        $address = [
            'address1' => $address1,
            'address2' => $address2,
            'pc' => $pc,
            'city' => $city,
        ];
        $id = $createOrganization->create($name, '', $address);
        dd($id);
    }

    public function list(ListOrganizations $listOrganizations)
    {
        $organizations = $listOrganizations->list(1, 10);
        $list = [];
        foreach ($organizations as $organization){
            $list[] = $organization->toArray();
        }
        return view('organizations/list', [
            'organizations' => $list
        ]);
    }
}
