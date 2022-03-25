<?php
class GHAuthPermission extends GHelpers
{
    public function userHasPermission($permission)
    {
        $this->load->model('user');
        $user = $this->model_user->getUserById($this->userId);
        if (!$user) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        }
        $permissions = explode('|', $user['permissions']);
        if (!in_array($permission, $permissions)) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        } else {
            return $user;
        }
    }

    public function adminHasPermission($permission)
    {
        $requestData = $this->http->validate_jwt_token();
        if (!$requestData['isadmin']) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        }

        $this->userId = $requestData['bearer'];

        $this->load->model('admin');
        $admin = $this->model_admin->getAdministratorByUserId($this->userId);
        if (!$admin) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        }
        $permissions = explode('|', $admin['permissions']);
        if (!in_array($permission, $permissions)) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        } else {
            return $admin;
        }
    }

    public function isAdmin()
    {
        $requestData = $this->http->validate_jwt_token();
        if (!$requestData['isadmin']) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        }

        $this->userId = $requestData['bearer'];

        $this->load->model('admin');
        $admin = $this->model_admin->getAdministratorByUserId($this->userId);
        if (!$admin) {
            $this->http->emit([
                'status' => false,
                'message' => 'You do not have access to this resource',
                'code' => 403,
            ]);
        } else {
            return $admin;
        }
    }

    public function isUser()
    {
        $user = $this->request->validate_jwt_token();
        $this->userId = $user['bearer'];
        $this->load->model('user');
        $user = $this->model_user->getUserbyTradeId($this->userId);
        return $user;
    }
    public function isSeller()
    {
        $user = $this->isUser();
        $this->load->model('seller');

        $seller = $this->model_seller->getUserSellerProfile($user['userid']);
        if (!$seller) {
            $this->request->emit([
                'error' => true,
                'message' =>
                'Store not found! Please create a store or contact admin',
                'code' => 404,
            ]);
        }
        return $seller;
    }

    public function isSellerProduct()
    {
        $user = $this->isUser();
        $this->load->model('seller');

        $seller = $this->model_seller->getUserSellerProfile($user['userid']);
        if (!$seller) {
            $this->request->emit([
                'error' => true,
                'message' =>
                'Store not found! Please create a store or contact admin',
                'code' => 404,
            ]);
        }

        $productId = $this->request->get('productid');
        $this->load->model('product');
        $product = $this->model_product->getProductsById($productId);
        if (!$product) {
            $this->request->emit([
                'error' => true,
                'message' =>
                'Product not found!',
                'code' => 404,
            ]);
        }

        if ($product['storeid'] !== $seller['msellerid']) {
            $this->request->emit([
                'error' => true,
                'message' =>
                'Forbidden!',
                'code' => 403,
            ]);
        }
        return ['seller' => $seller, 'product' => $product];
    }
}
