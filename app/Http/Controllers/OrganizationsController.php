<?php


namespace App\Http\Controllers;

use App\Src\UseCases\Domain\CreateOrganization;
use App\Src\UseCases\Domain\Invitation\AttachUserToAnOrganization;
use App\Src\UseCases\Domain\Invitation\RespondInvitationToAnOrganization;
use App\Src\UseCases\Domain\InviteUsersInOrganization;
use App\Src\UseCases\Domain\ListOrganizations;
use App\Src\UseCases\Domain\PrepareInvitationUsersInOrganization;
use App\Src\UseCases\Organizations\EditOrganization;
use App\Src\UseCases\Organizations\GetOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{
    public function showAddForm()
    {
        return view('organizations/add_form');
    }

    public function processAdd(Request $request, CreateOrganization $createOrganization)
    {
        list($name, $address, $picture) = $this->processOrganizationForm($request);
        $createOrganization->create($name, $picture, $address);
        return redirect()->route('organization.list');
    }

    public function showEditForm(string $organisationId, GetOrganization $getOrganization)
    {
        $organization = $getOrganization->get($organisationId);
        return view('organizations/edit_form', [
            'organization' => $organization->toArray()
        ]);
    }

    public function processEdit(string $organizationId, Request $request, EditOrganization $editOrganization)
    {
        list($name, $address, $picture) = $this->processOrganizationForm($request);
        $editOrganization->edit($organizationId, $name, $picture, $address);
        $request->session()->flash('notif_msg', __('organizations.message.organization.updated'));
        return redirect()->back();
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
                route('organization.edit.form', ['id' => $org['uuid']])
            ];
        }

        return format($total, $list);
    }

    public function prepareInvitation(Request $request,  PrepareInvitationUsersInOrganization $prepareInvitationUsersInOrganization)
    {
        $users = $request->input('users');
        $users = explode(PHP_EOL, $users);
        $organizationId = $request->input('organization_id');

        $usersToProcess = $prepareInvitationUsersInOrganization->prepare($organizationId, $users);

        return view('organizations.prepare-invitation', [
            'organization_id' => $organizationId,
            'usersToProcess' => $usersToProcess
        ]);
    }

    public function sendInvitations(Request $request, InviteUsersInOrganization $inviteUsersInOrganization)
    {
        $users = json_decode($request->input('users'), true);
        $organizationId = $request->input('organization_id');

        $inviteUsersInOrganization->invite($organizationId, $users['users']);
        $request->session()->flash('notif_msg', __('organizations.message.organization.invitation_send'));
        return redirect()->route('organization.list');
    }

    public function acceptInvite(Request $request, RespondInvitationToAnOrganization $respondInvitationToAnOrganization)
    {
        $action = $respondInvitationToAnOrganization->respond($token = $request->input('token'));
        if($action['action'] == 'register'){
            $request->session()->flash('should_attach_to_organization', $action['organization_id']);
            $request->session()->flash('user_to_register', $action['user']);
            return redirect()->route('register');
        }
        if($action['action'] == 'accept_or_decline'){
            $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
            return view('organizations.accept-or-decline-invitation', [
                'old_organisation' => isset($action['old_organisation']) ? $action['old_organisation']->toArray() : null,
                'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
            ]);
        }
        if($action['action'] == 'logout-login'){
            $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
            $request->session()->flash('should_attach_to_organization_token', $token);
            $request->session()->flash('should_attach_to_organization_redirect', route('login'));
            return view('organizations.logout-to-accept-or-decline-invitation', [
                'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
            ]);
        }

        if($action['action'] == 'login'){
            $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
            $request->session()->flash('should_attach_to_organization_token', $token);
            $request->session()->flash('should_attach_to_organization_redirect', route('login'));
            return redirect()->route('login');
        }

        if($action['action'] == 'logout-register'){
            $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
            $request->session()->flash('should_attach_to_organization_token', $token);
            $request->session()->flash('should_attach_to_organization_redirect', route('register'));
            $request->session()->flash('user_to_register', $action['user']);
            return view('organizations.logout-to-accept-or-decline-invitation', [
                'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
            ]);
        }
    }

    public function joinOrganization(Request $request, AttachUserToAnOrganization $attachUserToAnOrganization)
    {
        $userId = Auth::user()->uuid;
        $organizationId = $request->session()->get('should_attach_to_organization');
        $attachUserToAnOrganization->attach($userId, $organizationId);
        $request->session()->flash('notif_msg', __('organizations.message.organization.joined'));
        return redirect()->route('home');
    }

    private function processOrganizationForm(Request $request): array
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
        if ($request->has('logo')) {
            $picture['path_picture'] = $request->file('logo')->path();
            $picture['original_name'] = $request->file('logo')->getClientOriginalName();
            $picture['mine_type'] = $request->file('logo')->getMimeType();
        }
        return [$name, $address, $picture];
    }
}
