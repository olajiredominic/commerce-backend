<?php
class GHValidatorsUser extends GHelpers
{
    public function signup()
    {
        $params = $this->request->JSONPost(['email', 'telephone', 'password', 'username']);
        extract($params);
        if (!$this->validator->username($username, 2, 20)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Username must be between 2 - 20 characters',
                'data' => ['field' => 'username'],
            ]);
        }

        if (!$this->validator->telephone($telephone)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Invalid Parameter',
                'data' => ['field' => 'telephone'],
            ]);
        }
        if (!$this->validator->password($password)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Invalid Parameter',
                'data' => ['field' => 'password'],
            ]);
        }
        return $params;
    }

    public function profile($params)
    {
        extract($params);
        if (!$this->validator->telephone($telephone)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Invalid telephone number',
                'data' => ['field' => 'telephone'],
                'code' => 400,
            ]);
        }
        if (!$this->validator->string($firstname)) {
            $this->request->emit([
                'status' => false,
                'message' =>
                'Firstname can only be letters and must be at least 1 character',
                'data' => ['field' => 'firstname'],
                'code' => 400,
            ]);
        }
        if (!$this->validator->string($lastname)) {
            $this->request->emit([
                'status' => false,
                'message' =>
                'Lastname can only be letters and must be at least 1 character',
                'code' => 400,
                'data' => ['field' => 'lastname'],
            ]);
        }
        if (strlen($othername) > 0 && !$this->validator->string($othername)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Othername can only be letters',
                'code' => 400,
                'data' => ['field' => 'othername'],
            ]);
        }

        if (strlen($username) > 0 && !$this->validator->string($username)) {
            $this->request->emit([
                'status' => false,
                'message' =>
                'Username can only contain letters, numbers and underscore (_) character',
                'code' => 400,
                'data' => ['field' => 'username'],
            ]);
        }
        if (strlen($countryid) > 0 && !$this->validator->string($countryid)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Please select one of the provided countries',
                'code' => 400,
                'data' => ['field' => 'countryid'],
            ]);
        }
        if (strlen($regionid) > 0 && !$this->validator->string($regionid)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Please select one of the provided regions',
                'code' => 400,
                'data' => ['field' => 'regionid'],
            ]);
        }
    }

    public function login()
    {
        extract($this->request->JSONPost(['password', 'loginid']));
        if (!$this->validator->string($loginid)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Invalid username or password',
            ]);
        }
        if (!$this->validator->string($password)) {
            $this->request->emit([
                'status' => false,
                'message' => 'Invalid username or password',
            ]);
        }
        return ['password' => $password, 'loginId' => $loginid];
    }
}
