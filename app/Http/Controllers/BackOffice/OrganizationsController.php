<?php


namespace App\Http\Controllers\BackOffice;

use App\Http\Common\Form\OrganizationForm;
use App\Http\Controllers\Controller;
use App\Src\UseCases\Domain\Organizations\CreateOrganization;
use App\Src\UseCases\Domain\Organizations\EditOrganization;
use App\Src\UseCases\Domain\Organizations\GetOrganization;
use App\Src\UseCases\Domain\Organizations\Invitation\AttachUserToAnOrganization;
use App\Src\UseCases\Domain\Organizations\Invitation\InviteUsersInOrganization;
use App\Src\UseCases\Domain\Organizations\Invitation\PrepareInvitationUsersInOrganization;
use App\Src\UseCases\Domain\Organizations\Invitation\RespondInvitationToAnOrganization;
use App\Src\UseCases\Domain\Organizations\ListOrganizations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{
    public function showAddForm()
    {
        return view('organizations/add_form');
    }

    public function processAdd(Request $request, CreateOrganization $createOrganization, OrganizationForm $form)
    {
        list($name, $address, $picture) = $form->process();
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

    public function processEdit(string $organizationId, Request $request, EditOrganization $editOrganization, OrganizationForm $form)
    {
        list($name, $address, $picture) = $form->process();
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
        switch($action['action']){
            case 'register':
                return $this->redirectToRegisterPage($request, $action);
            case 'accept_or_decline':
                return $this->showAcceptOrDeclinePage($request, $action);
            case 'logout-login':
                return $this->showLogoutToAcceptInvitation($request, $action, $token);
            case 'login':
                return $this->redirectToLogin($request, $action, $token);
            case 'logout-register':
                return $this->showLogoutToRegister($request, $action, $token);
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

    private function redirectToRegisterPage(Request $request, array $action): \Illuminate\Http\RedirectResponse
    {
        $request->session()->flash('should_attach_to_organization', $action['organization_id']);
        $request->session()->flash('user_to_register', $action['user']);
        return redirect()->route('register');
    }

    private function showAcceptOrDeclinePage(Request $request, array $action)
    {
        $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
        return view('organizations.accept-or-decline-invitation', [
            'old_organisation' => isset($action['old_organisation']) ? $action['old_organisation']->toArray() : null,
            'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
        ]);
    }

    private function showLogoutToAcceptInvitation(Request $request, array $action, $token)
    {
        $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
        $request->session()->flash('should_attach_to_organization_token', $token);
        $request->session()->flash('should_attach_to_organization_redirect', route('login'));
        return view('organizations.logout-to-accept-or-decline-invitation', [
            'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
        ]);
    }

    private function redirectToLogin(Request $request, array $action, $token): \Illuminate\Http\RedirectResponse
    {
        $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
        $request->session()->flash('should_attach_to_organization_token', $token);
        $request->session()->flash('should_attach_to_organization_redirect', route('login'));
        return redirect()->route('login');
    }

    /**
     * @param Request $request
     * @param array $action
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function showLogoutToRegister(Request $request, array $action, $token)
    {
        $request->session()->flash('should_attach_to_organization', $action['organization_to_join']->id());
        $request->session()->flash('should_attach_to_organization_token', $token);
        $request->session()->flash('should_attach_to_organization_redirect', route('register'));
        $request->session()->flash('user_to_register', $action['user']);
        return view('organizations.logout-to-accept-or-decline-invitation', [
            'organization_to_join' => isset($action['organization_to_join']) ? $action['organization_to_join']->toArray() : null
        ]);
    }
}
