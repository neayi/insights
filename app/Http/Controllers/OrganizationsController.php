<?php


namespace App\Http\Controllers;

use App\Src\UseCases\Domain\CreateOrganization;
use App\Src\UseCases\Domain\ListOrganizations;
use App\Src\UseCases\Domain\PrepareInvitationUsersInOrganization;
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
        $picture = [];
        if($request->has('logo')){
            $picture['path_picture'] = $request->file('logo')->path();
            $picture['original_name'] = $request->file('logo')->getClientOriginalName();
            $picture['mine_type'] = $request->file('logo')->getMimeType();
        }

        $createOrganization->create($name, $picture, $address);
        return redirect()->route('organization.list');
    }

    public function list()
    {
        return view('organizations/list');
    }

    public function listOrganizations(Request $request, ListOrganizations $listOrganizations)
    {
        $page = $request->input('start')/10 + 1;
        $organizations = $listOrganizations->list($page, 10);
        $total = isset($organizations['total']) ? $organizations['total'] : 0;
        $list = [];
        foreach ($organizations['list'] as $organization){
            $org = $organization->toArray();
            $list[] = [
                $org['name'],
                $org['url_picture'],
                '',
                $org['uuid'],
            ];
        }

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $list,
        ];
    }

    public function prepareInvitation(Request $request,  PrepareInvitationUsersInOrganization $prepareInvitationUsersInOrganization)
    {
        $users = $request->input('users');
        $users = explode(PHP_EOL, $users);
        $organizationId = $request->input('organization_id');

        $usersToProcess = $prepareInvitationUsersInOrganization->prepare($organizationId, $users);

        dd($usersToProcess);
        return view('organizations.prepare-invitation', [
            'organization_id' => $organizationId,
            'list' => $usersToProcess
        ]);
    }

    public function sendInvitations(Request $request)
    {

    }
}
