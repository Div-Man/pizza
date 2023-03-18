<?php

namespace MyProject\Controllers;

use MyProject\Models\Users\User as User;
use MyProject\Exceptions\InvalidArgumentException as InvalidArgumentException;
use MyProject\Models\Users\UserActivationService as UserActivationService;
use MyProject\Services\EmailSender as EmailSender;
use MyProject\Models\Users\UsersAuthService as UsersAuthService;
use MyProject\Models\Products\Product;

class UsersController extends MainController {

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        //$data = $_POST; //это нужно, если отключить fetch

        if (!empty($data)) {
            try {
                $user = User::signUp($data);
                if ($user instanceof User) {
                    $code = UserActivationService::createActivationCode($user);
                    
                    $emailSend = EmailSender::send($user, 'Активация', 'userActivation.php', [
                                'userId' => $user->getId(),
                                'code' => $code,
                                'email' => $user->email
                                
                    ]);
                }

                $data = json_encode(['msg' => 'success', 'emailSend' => $emailSend]);

                return $data;
            } catch (InvalidArgumentException $e) {

                $errorArray = explode(".", $e->getMessage());
                $error = [$errorArray[0] => $errorArray[1]];
                $data = json_encode($error);

                return $data;
            }
        }
    }

    public function activate(int $userId, string $activationCode) {
        $user = User::getById($userId);

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate($user->id);
            $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
            $user->updateUserToken($user->getAuthToken(), $user->getId());
            UsersAuthService::createToken($user);
            header('Location: /');
            exit();
            
            //UserActivationService::deleteActivationCode($user);
             //$this->view->render('/users/activate.php', []);
        }
    }

    public function login() {

        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            try {
                $user = User::login($data);
                UsersAuthService::createToken($user);
                $data = json_encode(['msg' => 'success']);
                return $data;
            } catch (InvalidArgumentException $e) {
                $errorArray = explode(".", $e->getMessage());
                $error = [$errorArray[0] => $errorArray[1]];
                $data = json_encode($error);
                return $data;
            }
        }
    }

    public function profile() {

        if (!isset($_SESSION["user"])) {
            header("Location: /");
            return false;
        }
        $products = new Product;
        $result = $products->getPayOrderProduct();
        $this->view->render('/users/profile.php', ['orders' => $result]);
    }

    public function logout() {
        UsersAuthService::deleteToken();
        $this->view->render('/');
    }

   
}
