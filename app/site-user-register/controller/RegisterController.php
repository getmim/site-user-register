<?php
/**
 * RegisterController
 * @package site-user-register
 * @version 0.0.1
 */

namespace SiteUserRegister\Controller;

use SiteUserRegister\Library\Meta;
use LibForm\Library\Form;
use LibUserAuthCookie\Authorizer\Cookie;
use LibUserMain\Model\User;
use SiteUserRegister\Model\UserVerification as UVerification;

class RegisterController extends \Site\Controller
{
	public function createAction() {
        $next = $this->req->getQuery('next');
        if(!$next)
            $next = $this->router->to('siteHome');

        if($this->user->isLogin())
            return $this->res->redirect($next);

        $form = new Form('site.me.register');

        $params = [
            '_meta' => [
                'title' => 'Register'
            ],
            'form'  => $form,
            'meta' => Meta::register(),
            'errors' => []
        ];

        if(!($valid = $form->validate()) || !$form->csrfTest('noob')){
            $params['errors'] = $form->getErrors();
            $this->res->render('me/register', $params);
            return $this->res->send();
        }

        $valid->password = $this->user->hashPassword($valid->password);

        if(!$id = User::create((array)$valid))
        	deb(User::lastError());

        $user = User::getOne(['id'=>$id]);

        $params['meta'] = Meta::registerSuccess($user);

        // create verification object
        $verif = [
            'user'    => $id,
            'expires' => date('Y-m-d H:i:s', strtotime('+2 hour')),
            'hash'    => '',
            'next'    => $next
        ];

        while(true){
            $verif['hash'] = md5(time() . '-' . uniqid() . '-' . $id);
            if(!UVerification::getOne(['hash'=>$verif['hash']]))
                break;
        }
        UVerification::create($verif);

        $params['verify_url'] = $this->router->to('siteMeVerify', ['hash'=>$verif['hash']]);

        $this->res->render('me/register-success', $params);
        return $this->res->send();
    }

    public function verifyAction(){
        $hash = $this->req->param->hash;

        $verifier = UVerification::getOne(['hash'=>$hash]);
        if(!$verifier)
            return $this->show404();

        UVerification::remove(['id'=>$verifier->id]);

        $expires = strtotime($verifier->expires);
        if($expires < time())
            return $this->show404();

        $user = User::getOne(['id'=>$verifier->user]);
        if(!$user)
            return $this->show404();

        if($user->status != 2)
            return $this->show404();

        User::set(['status'=>3], ['id'=>$user->id]);

        $params = [
            '_meta' => [
                'title' => 'Verification'
            ],
            'meta' => Meta::verification(),
            'next' => $verifier->next ?? $this->router->to('siteHome')
        ];

        Cookie::setKeep(false);
        Cookie::loginById($user->id);
        
        $this->res->render('me/verification', $params);
        return $this->res->send();
    }
}