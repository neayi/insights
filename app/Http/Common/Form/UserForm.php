<?php


namespace App\Http\Common\Form;


class UserForm
{
    public function process()
    {
        $request = request();
        $firstname = $request->input('firstname') !== null ? $request->input('firstname') : '';
        $lastname = $request->input('lastname') !== null ? $request->input('lastname') : '';
        $email = $request->input('email') !== null ? $request->input('email') : '';
        $picture = [];
        if ($request->has('logo')) {
            $picture['path_picture'] = $request->file('logo')->path();
            $picture['original_name'] = $request->file('logo')->getClientOriginalName();
            $picture['mine_type'] = $request->file('logo')->getMimeType();
        }
        return [$firstname, $lastname, $email, $picture];
    }
}
