<?php


namespace App\Http\Controllers;


class OrganizationsController extends Controller
{
    public function showAddForm()
    {
        return view('organizations/add_form');
    }

    public function processAdd()
    {
    }
}
