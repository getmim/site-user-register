<?php
/**
 * RegisterController
 * @package site-user-register
 * @version 0.0.1
 */

namespace SiteUserRegister\Controller;

use SiteUserRegister\Library\Meta;
use LibForm\Library\Form;
use LibUserMain\Model\User;

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

        $this->res->render('me/register-success', $params);
        return $this->res->send();
    }
}